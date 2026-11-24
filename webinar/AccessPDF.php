<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

session_start();

ini_set('display_errors', 0);
error_reporting(E_ALL);

$errorMessage = '';
$successMessage = '';
$transactionId = trim((string) ($_GET['transactionId'] ?? ''));
$record = null;
$pdfViewUrl = '';
$showPdfCta = false;
$canRequestCode = false;
$canVerifyCode = false;

function pageText(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function accessPdfLog(string $message, array $context = []): void
{
    $safeContext = [];

    foreach ($context as $key => $value) {
        if (preg_match('/pass|password|secret|token/i', (string) $key)) {
            $safeContext[$key] = '[redacted]';
        } else {
            $safeContext[$key] = $value;
        }
    }

    $line = date('Y-m-d H:i:s') . ' | ' . $message;

    if (!empty($safeContext)) {
        $line .= ' | ' . json_encode($safeContext);
    }

    $logPath = __DIR__ . '/access_pdf_mail.log';

    if (file_put_contents($logPath, $line . PHP_EOL, FILE_APPEND) === false) {
        error_log('AccessPDF log write failed: ' . $logPath . ' | ' . $line);
    }
}

function sanitizeSmtpDebug(string $message): string
{
    $message = trim($message);

    if (preg_match('/(AUTH PLAIN|AUTH LOGIN|AUTH XOAUTH2|PASSWORD)/i', $message)) {
        return preg_replace('/:(.*)$/', ': [smtp auth output redacted]', $message);
    }

    if (preg_match('/^[A-Za-z0-9+\/=]{16,}$/', $message)) {
        return '[possible encoded smtp credential redacted]';
    }

    return $message;
}

accessPdfLog('AccessPDF request received', [
    'method' => $_SERVER['REQUEST_METHOD'] ?? '',
    'transactionId' => $transactionId,
    'post_action' => $_POST['action'] ?? '',
]);

function maskedEmail(string $email): string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'your email';
    }

    [$name, $domain] = explode('@', $email, 2);
    $visible = substr($name, 0, 1);

    return $visible . str_repeat('*', max(strlen($name) - 1, 1)) . '@' . $domain;
}

function smtpDebugEnabled(): bool
{
    return isset($_GET['smtp_debug'])
        && $_GET['smtp_debug'] === '1';
}

function accessFormAction(string $transactionId): string
{
    $query = [
        'transactionId' => $transactionId,
    ];

    if (smtpDebugEnabled()) {
        $query['smtp_debug'] = '1';
    }

    return 'AccessPDF.php?' . http_build_query($query);
}

function generateAccessCode(): string
{
    return (string) random_int(100000, 999999);
}

function accessCodeIsActive(array $record): bool
{
    if (empty($record['access_code']) || empty($record['expiry_code'])) {
        return false;
    }

    return new DateTime() <= new DateTime((string) $record['expiry_code']);
}

function pdfAccessSessionIsActive(string $transactionId, array $record): bool
{
    $sessionAccess = $_SESSION['pdf_access'][$transactionId] ?? null;

    if (
        !is_array($sessionAccess) ||
        empty($sessionAccess['verified']) ||
        empty($sessionAccess['expires_at']) ||
        empty($record['expiry_code'])
    ) {
        return false;
    }

    $sessionExpiry = new DateTime((string) $sessionAccess['expires_at']);
    $recordExpiry = new DateTime((string) $record['expiry_code']);
    $currentTime = new DateTime();

    return $currentTime <= $sessionExpiry && $currentTime <= $recordExpiry;
}

function pdfViewerUrl(string $transactionId): string
{
    return 'ViewPDF.php?' . http_build_query(['transactionId' => $transactionId]);
}

function generateAndStoreAccessCode(PDO $pdo, string $transactionId, array $record): array
{
    $accessCode = generateAccessCode();
    $expiryTime = new DateTime('+5 minutes');
    $expiryCode = $expiryTime->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("
        UPDATE customers
        SET access_code = :access_code,
            expiry_code = :expiry_code
        WHERE transactionId = :transactionId
        LIMIT 1
    ");

    $stmt->execute([
        ':access_code' => $accessCode,
        ':expiry_code' => $expiryCode,
        ':transactionId' => $transactionId,
    ]);

    accessPdfLog('Access code generated and stored', [
        'transactionId' => $transactionId,
        'expiry_code' => $expiryCode,
    ]);

    $record['access_code'] = $accessCode;
    $record['expiry_code'] = $expiryCode;

    return $record;
}

function sendAccessCodeEmail(array $record): void
{
    require_once __DIR__ . '/../vendor/autoload.php';

    $email = trim((string) ($record['email'] ?? ''));
    $accessCode = trim((string) ($record['access_code'] ?? ''));
    $expiryCode = trim((string) ($record['expiry_code'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Customer email is not valid.');
    }

    if ($accessCode === '') {
        throw new RuntimeException('Access code is missing.');
    }

    $smtpHost = getenv('SMTP_HOST') ?: '';
    $smtpUser = getenv('SMTP_USERNAME') ?: getenv('SMTP_USER') ?: '';
    $smtpPass = getenv('SMTP_PASSWORD') ?: getenv('SMTP_PASS') ?: '';
    $smtpPort = (int) (getenv('SMTP_PORT') ?: 465);

    accessPdfLog('Preparing access code email', [
        'recipient' => $email,
        'has_access_code' => $accessCode !== '',
        'has_expiry_code' => $expiryCode !== '',
        'smtp_host' => $smtpHost,
        'smtp_user' => $smtpUser,
        'smtp_port' => $smtpPort,
        'has_smtp_password' => $smtpPass !== '',
    ]);

    if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '') {
        throw new RuntimeException('SMTP credentials are missing.');
    }

    $mail = new PHPMailer(true);
    $mail->isSMTP();

    if (smtpDebugEnabled()) {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = function ($message, $level) {

            $safeMessage = sanitizeSmtpDebug((string) $message);

            accessPdfLog('PHPMailer SMTP debug', [
                'level' => $level,
                'message' => $safeMessage,
            ]);

            echo '<div style="background:#111;color:#00ff88;font-family:monospace;font-size:13px;padding:8px 12px;border-bottom:1px solid #333;word-break:break-word;">';
            echo '[SMTP DEBUG ' . (int) $level . '] ' . htmlspecialchars($safeMessage, ENT_QUOTES, 'UTF-8');
            echo '</div>';
        };
    } else {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
    }

    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $smtpPort;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($smtpUser, 'The PHELONPRENEUR Network');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your PDF Access Code';

    $expiryText = $expiryCode !== '' ? date('F j, Y \a\t g:i A', strtotime($expiryCode)) : 'the listed expiration time';
    $codeSafe = pageText($accessCode);
    $expirySafe = pageText($expiryText);

    $mail->Body = "
        <div style='margin:0;padding:32px;background:#0f0d09;color:#f7efe0;font-family:Arial,sans-serif;'>
            <div style='max-width:560px;margin:0 auto;background:#17130d;border:1px solid rgba(234,211,154,.28);padding:28px;border-radius:10px;'>
                <p style='margin:0 0 10px;color:#c6a15b;font-size:12px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;'>PDF Access</p>
                <h1 style='margin:0 0 18px;font-size:26px;line-height:1.3;color:#fff;'>Your access code</h1>
                <p style='margin:0 0 20px;color:#d8c9aa;line-height:1.7;'>Use the code below on the access page to verify your PDF access.</p>
                <div style='margin:22px 0;padding:18px;text-align:center;background:#ead39a;color:#111;border-radius:8px;font-size:26px;font-weight:800;letter-spacing:.08em;'>{$codeSafe}</div>
                <p style='margin:0;color:#d8c9aa;line-height:1.7;'>This code expires on <strong style='color:#fff;'>{$expirySafe}</strong>.</p>
            </div>
        </div>
    ";
    $mail->AltBody = "Your PDF access code is {$accessCode}. It expires on {$expiryText}.";

    $mail->send();

    accessPdfLog('Access code email sent', [
        'recipient' => $email,
        'smtp_host' => $smtpHost,
    ]);
}

if ($transactionId === '') {
    $errorMessage = 'No transaction ID provided.';
} else {
    try {
        require_once __DIR__ . '/../env.php';

        if (!isset($pdo) || !($pdo instanceof PDO)) {
            throw new RuntimeException('Database connection is not available.');
        }

        $stmt = $pdo->prepare("
            SELECT transactionId, access_code, expiry_code, email, total
            FROM customers
            WHERE transactionId = :transactionId
            LIMIT 1
        ");

        $stmt->execute([
            ':transactionId' => $transactionId,
        ]);

        $record = $stmt->fetch();

        if (!$record) {
            accessPdfLog('Customer lookup failed', [
                'transactionId' => $transactionId,
            ]);
            $errorMessage = 'Invalid access link.';
        } elseif (empty($record['access_code']) || empty($record['expiry_code'])) {
            accessPdfLog('Customer lookup missing access fields', [
                'transactionId' => $transactionId,
                'has_access_code' => !empty($record['access_code']),
                'has_expiry_code' => !empty($record['expiry_code']),
                'has_email' => !empty($record['email']),
                'total' => $record['total'] ?? '',
            ]);
            $canRequestCode = true;
            $successMessage = 'Click Send Code to my Email to receive a new code. It will expire in 5 minutes.';
        } else {
            accessPdfLog('Customer lookup successful', [
                'transactionId' => $transactionId,
                'has_access_code' => !empty($record['access_code']),
                'has_expiry_code' => !empty($record['expiry_code']),
                'has_email' => !empty($record['email']),
                'total' => $record['total'] ?? '',
            ]);

            $expiryTime = new DateTime($record['expiry_code']);
            $currentTime = new DateTime();

            if ($currentTime > $expiryTime) {
                accessPdfLog('Access code expired', [
                    'transactionId' => $transactionId,
                    'expiry_code' => $record['expiry_code'],
                ]);
                unset($_SESSION['pdf_access'][$transactionId]);
                $canRequestCode = true;
                $canVerifyCode = false;
                $successMessage = 'Your previous code expired. Click Send Code to my Email to receive a new 5-minute code.';
            } elseif (pdfAccessSessionIsActive($transactionId, $record)) {
                $showPdfCta = true;
                $pdfViewUrl = pdfViewerUrl($transactionId);
                $canRequestCode = false;
                $canVerifyCode = false;
                $successMessage = 'Your PDF access is still active until ' . $expiryTime->format('F j, Y \a\t g:i A') . '.';
            } else {
                $canRequestCode = false;
                $canVerifyCode = true;
                $successMessage = 'Enter the access code sent to your email. You may request another code after this one expires.';
            }
        }
    } catch (Throwable $e) {
        accessPdfLog('AccessPDF database/link error', [
            'transactionId' => $transactionId,
            'error' => $e->getMessage(),
        ]);
        error_log('AccessPDF error: ' . $e->getMessage());
        $errorMessage = 'We could not verify this access link right now.';
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && $record && $errorMessage === '') {
    $action = trim((string) ($_POST['action'] ?? ''));

    if ($action === 'send_code') {
        try {
            if (!isset($pdo) || !($pdo instanceof PDO)) {
                throw new RuntimeException('Database connection is not available.');
            }

            if (accessCodeIsActive($record)) {
                accessPdfLog('Access code resend blocked because current code is still active', [
                    'transactionId' => $transactionId,
                    'expiry_code' => $record['expiry_code'] ?? '',
                ]);

                $canRequestCode = false;
                $canVerifyCode = true;
                $successMessage = 'A code is already active. Please check your email or wait until it expires before requesting another.';
            } else {
                $record = generateAndStoreAccessCode($pdo, $transactionId, $record);
                sendAccessCodeEmail($record);
                $canRequestCode = false;
                $canVerifyCode = true;
                $successMessage = 'Access code sent to ' . maskedEmail((string) $record['email']) . '. It expires in 5 minutes.';
            }

        } catch (Throwable $e) {
            accessPdfLog('Access code email failed', [
                'recipient' => $record['email'] ?? '',
                'error' => $e->getMessage(),
            ]);
            error_log('AccessPDF mail error: ' . $e->getMessage());
            $errorMessage = 'We could not send the code right now. Please contact support.';
        }
    } else {
        $submittedCode = trim((string) ($_POST['access_code'] ?? ''));
        $storedCode = trim((string) $record['access_code']);

        if ($submittedCode === '') {
            $errorMessage = 'Please enter your access code.';
        } elseif (empty($record['expiry_code']) || new DateTime() > new DateTime((string) $record['expiry_code'])) {
            $canRequestCode = true;
            $canVerifyCode = false;
            $errorMessage = 'This access code has expired. Click Send Code to my Email to receive a new 5-minute code.';
        } elseif (hash_equals(strtoupper($storedCode), strtoupper($submittedCode))) {
            $showPdfCta = true;
            $pdfViewUrl = pdfViewerUrl($transactionId);
            $_SESSION['pdf_access'][$transactionId] = [
                'verified' => true,
                'expires_at' => (string) $record['expiry_code'],
                'total' => (string) ($record['total'] ?? ''),
            ];
            $canRequestCode = false;
            $canVerifyCode = true;
            $successMessage = 'Access code verified. Your PDF is ready to view.';
            accessPdfLog('Access code verified', [
                'transactionId' => $transactionId,
                'pdf' => $pdfViewUrl,
            ]);
        } else {
            $canRequestCode = false;
            $canVerifyCode = true;
            $errorMessage = 'Invalid access code. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Access PDF | The PHELONPRENEUR Network</title>
  <meta name="description" content="Enter your access code to verify your PDF access." />
  <script src="../js/tailwind-cdn.js"></script>
  <script src="../js/tailwind-index.js"></script>
  <link rel="stylesheet" href="webinar.css" />
  <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      background: #0f0d09;
      color: #f7efe0;
      font-family: Inter, Arial, sans-serif;
    }

    .access-page {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 24px;
    }

    .access-form {
      width: min(100%, 420px);
      display: grid;
      gap: 16px;
    }

    .access-divider {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      gap: 12px;
      color: rgba(247, 239, 224, .55);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .12em;
      text-align: center;
      text-transform: uppercase;
    }

    .access-divider::before,
    .access-divider::after {
      content: "";
      height: 1px;
      background: rgba(234, 211, 154, .22);
    }

    .access-input {
      width: 100%;
      min-height: 58px;
      border: 1px solid rgba(234, 211, 154, .45);
      background: rgba(255, 255, 255, .08);
      color: #fff;
      border-radius: 8px;
      padding: 0 16px;
      font-size: 18px;
      font-weight: 700;
      letter-spacing: 1px;
      text-align: center;
      text-transform: uppercase;
      outline: none;
    }

    .access-input:focus {
      border-color: #ead39a;
      box-shadow: 0 0 0 4px rgba(234, 211, 154, .14);
    }

    .access-button {
      min-height: 54px;
      border: 0;
      border-radius: 8px;
      background: linear-gradient(135deg, #c6a15b, #ead39a);
      color: #111;
      cursor: pointer;
      font-size: 14px;
      font-weight: 800;
      letter-spacing: .12em;
      text-transform: uppercase;
      text-align: center;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .access-message {
      min-height: 24px;
      margin: 0;
      text-align: center;
      font-size: 14px;
      line-height: 1.6;
    }

    .access-message--error {
      color: #ffb4a8;
    }

    .access-message--success {
      color: #bff0c2;
    }
  </style>
</head>
<body>
  <main class="access-page">
    <form class="access-form" method="post" action="<?php echo pageText(accessFormAction($transactionId)); ?>">
      <?php if ($showPdfCta): ?>
        <a class="access-button" href="<?php echo pageText($pdfViewUrl); ?>" target="_blank" rel="noopener">
          View PDF
        </a>
      <?php elseif ($canRequestCode): ?>
        <button
          class="access-button"
          type="submit"
          name="action"
          value="send_code"
          <?php echo ($transactionId === '' || $record === null) ? 'disabled' : ''; ?>
        >
          Send Code to my Email
        </button>
      <?php elseif ($canVerifyCode): ?>
        <input
          class="access-input"
          type="text"
          name="access_code"
          placeholder="Enter access code"
          autocomplete="one-time-code"
          maxlength="100"
          autofocus
        />

        <button
          class="access-button"
          type="submit"
          name="action"
          value="verify_code"
        >
          Verify
        </button>
      <?php endif; ?>

      <?php if ($errorMessage !== ''): ?>
        <p class="access-message access-message--error"><?php echo pageText($errorMessage); ?></p>
      <?php elseif ($successMessage !== ''): ?>
        <p class="access-message access-message--success"><?php echo pageText($successMessage); ?></p>
      <?php else: ?>
        <p class="access-message" aria-hidden="true">&nbsp;</p>
      <?php endif; ?>
    </form>
  </main>
</body>
</html>
