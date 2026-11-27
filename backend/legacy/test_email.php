<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/vendor/autoload.php';

$smtpHost = getenv('SMTP_HOST') ?: '';
$smtpUser = getenv('SMTP_USERNAME') ?: getenv('SMTP_USER') ?: '';
$smtpPass = getenv('SMTP_PASSWORD') ?: getenv('SMTP_PASS') ?: '';
$smtpPort = (int) (getenv('SMTP_PORT') ?: 465);

try {
    if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '') {
        throw new RuntimeException('SMTP credentials are missing.');
    }

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $smtpPort;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($smtpUser, 'The PHELONPRENEUR Network');
    $mail->addAddress('vtmnsgmply@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test Email';
    $mail->Body = '<p>This is a simple SMTP test email from The PHELONPRENEUR Network.</p>';
    $mail->AltBody = 'This is a simple SMTP test email from The PHELONPRENEUR Network.';

    $mail->send();

    echo json_encode([
        'success' => true,
        'message' => 'Test email sent.',
        'to' => 'vtmnsgmply@gmail.com',
    ]);
} catch (Throwable $e) {
    error_log('test_email.php failed: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Test email could not be sent.',
        'error' => $e->getMessage(),
    ]);
}
