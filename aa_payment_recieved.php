<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


function webhookLog($message, $context = null) {
    $logFile = __DIR__ . '/webhook_debug.log';

    $entry = '[' . date('Y-m-d H:i:s') . '] ' . $message;

    if ($context !== null) {
        $entry .= ' | ' . json_encode($context, JSON_PRETTY_PRINT);
    }

    $entry .= PHP_EOL;

    file_put_contents($logFile, $entry, FILE_APPEND);
}

function loadEnvFile() {
    $envPath = __DIR__ . '/.env';

    if (!file_exists($envPath)) {
        $envPath = __DIR__ . '/../.env';
    }

    if (!file_exists($envPath)) {
        throw new Exception('.env file not found.');
    }

    $envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($envLines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

        if ($key && $value !== null) {
            $_ENV[trim($key)] = trim($value);
        }
    }
}


function insertIntakeRecord($payload) {
    $pdo = createPDOConnection();

    $firstName = extractValue($payload, [
        'customData.firstName',
        'contact.firstName',
        'contact.first_name',
        'first_name',
        'firstName',
        'payment.customer.first_name',
    ]);

    $lastName = extractValue($payload, [
        'customData.lastName',
        'contact.lastName',
        'contact.last_name',
        'last_name',
        'lastName',
        'payment.customer.last_name',
    ]);

    $email = extractValue($payload, [
        'customData.email',
        'contact.email',
        'email',
        'payment.customer.email',
    ]);

    $phone = extractValue($payload, [
        'customData.phone',
        'contact.phone',
        'phone',
        'payment.customer.phone',
    ]);

    $only_start_date = extractValue($payload, [
        'customData.date',
        'appointment.only_start_date',
        'appointment.date',
    ]);

    $only_start_time = extractValue($payload, [
        'customData.time',
        'appointment.only_start_time',
        'appointment.time',
    ]);

    $appointment_id = extractValue($payload, [
        'customData.appointmentId',
        'calendar.appointmentId',
        'appointment.id',
        'appointment.appointmentId',
    ]);

    $amount_paid = extractValue($payload, [
        'payment.total_amount',
        'customData.amount_paid',
        'total_amount',
    ]);

    $sql = "
        INSERT INTO intake (
            firstName,
            lastName,
            email,
            phone,
            only_start_date,
            only_start_time,
            appointment_id,
            amount_paid,
            date_created
        ) VALUES (
            :firstName,
            :lastName,
            :email,
            :phone,
            :only_start_date,
            :only_start_time,
            :appointment_id,
            :amount_paid,
            NOW()
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':firstName'       => $firstName ?: null,
        ':lastName'        => $lastName ?: null,
        ':email'           => $email ?: null,
        ':phone'           => $phone ?: null,
        ':only_start_date' => $only_start_date ?: null,
        ':only_start_time' => $only_start_time ?: null,
        ':appointment_id'  => $appointment_id ?: null,
        ':amount_paid'     => $amount_paid !== '' ? $amount_paid : null,
    ]);

    return $pdo->lastInsertId();
}

function createPDOConnection() {
    loadEnvFile();

    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';

    if (!$db || !$user) {
        throw new Exception('Database credentials are missing.');
    }

    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";

    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

// ─── Raw input ───────────────────────────────────────────────────────────────

$input = file_get_contents('php://input');
$data  = json_decode($input, true);



// file_put_contents(
//     __DIR__ . '/webhook_debug_' . date('Ymd_His') . '_' . uniqid() . '.json',
//     json_encode($data, JSON_PRETTY_PRINT)
// );

// ─── Helpers ─────────────────────────────────────────────────────────────────

function extractValue($data, $keys) {
    foreach ($keys as $key) {
        $parts = explode('.', $key);
        $temp  = $data;

        foreach ($parts as $part) {
            if (is_array($temp) && array_key_exists($part, $temp)) {
                $temp = $temp[$part];
            } else {
                continue 2;
            }
        }

        if ($temp !== null && $temp !== '') {
            if (is_array($temp)) {
                return implode(', ', array_filter($temp, function ($item) {
                    return $item !== null && $item !== '';
                }));
            }

            return trim((string) $temp);
        }
    }

    return '';
}

function cleanOutput($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function webhookHasPayment($data) {
    $paymentStatus = strtolower(trim(extractValue($data, [
        'payment.payment_status',
        'payment.status',
        'paymentStatus',
        'payment_status',
        'status',
    ])));

    $transactionId = extractValue($data, [
        'payment.transaction_id',
        'payment.transactionId',
        'transaction_id',
        'transactionId',
        'payment.id',
    ]);

    $amountPaid = extractValue($data, [
        'payment.total_amount',
        'payment.amount',
        'customData.amount_paid',
        'total_amount',
        'amount_paid',
    ]);



    $successStatuses = [
        'succeeded',
        'success',
        'successful',
        'paid',
        'completed',
        'complete',
    ];

    if (in_array($paymentStatus, $successStatuses, true)) {
        return true;
    }

    if (!empty($transactionId) && !empty($amountPaid)) {
        return true;
    }

    return false;
}

function getWebhookKey($data) {
    $contact_id = extractValue($data, [
        'contact_id',
        'payment.customer.id',
        'customer.id',
    ]);

    $calendar_id = extractValue($data, [
        'calendar.id',
        'payment.calendar.id',
        'contact.lastAttributionSource.mediumId',
        'contact.attributionSource.mediumId',
    ]);

    if (empty($contact_id) || empty($calendar_id)) {
        return '';
    }

    return md5($contact_id . '|' . $calendar_id);
}

function cacheAppointmentPayload($data) {
    $key = getWebhookKey($data);

    if (empty($key)) {
        return false;
    }

    $cacheDir = __DIR__ . '/webhook_cache';

    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    file_put_contents(
        $cacheDir . '/' . $key . '.json',
        json_encode($data, JSON_PRETTY_PRINT)
    );

    return true;
}

function loadAppointmentPayload($data) {
    $key = getWebhookKey($data);

    if (empty($key)) {
        return [];
    }

    $cacheFile = __DIR__ . '/webhook_cache/' . $key . '.json';

    if (!file_exists($cacheFile)) {
        return [];
    }

    $cached = json_decode(file_get_contents($cacheFile), true);

    return is_array($cached) ? $cached : [];
}

function waitForAppointmentPayload($data, $maxSeconds = 8) {
    $appointmentPayload = loadAppointmentPayload($data);

    if (!empty($appointmentPayload)) {
        return $appointmentPayload;
    }

    for ($i = 0; $i < $maxSeconds; $i++) {
        sleep(1);

        $appointmentPayload = loadAppointmentPayload($data);

        if (!empty($appointmentPayload)) {
            return $appointmentPayload;
        }
    }

    return [];
}

function mergeAppointmentIntoPaymentPayload($paymentData, $appointmentData) {
    if (empty($appointmentData)) {
        return $paymentData;
    }

    if (!isset($paymentData['customData']) || !is_array($paymentData['customData'])) {
        $paymentData['customData'] = [];
    }

    $fieldsToMerge = [
        'appointmentId',
        'date',
        'time',
        'add_to_google_calendar',
        'add_to_ical_outlook',
        'reschedule_link',
    ];

    foreach ($fieldsToMerge as $field) {
        if (
            empty($paymentData['customData'][$field]) &&
            !empty($appointmentData['customData'][$field])
        ) {
            $paymentData['customData'][$field] = $appointmentData['customData'][$field];
        }
    }

    if (empty($paymentData['calendar']) && !empty($appointmentData['calendar'])) {
        $paymentData['calendar'] = $appointmentData['calendar'];
    }

    return $paymentData;
}

/**
 * Renders a styled two-column table row.
 * Returns '' if $value is empty so blank fields are hidden automatically.
 *
 * $bgStyle   — inline background style for the <tr>  e.g. 'background:#1e1912;'
 * $highlight — gold label + larger gold value (Date / Time rows)
 * $isId      — monospace dimmed style for ID fields
 */
function tableRow($label, $value, $bgStyle = '', $highlight = false, $isId = false) {
    if ($value === '' || $value === null) {
        return '';
    }

    $trAttr     = $bgStyle ? " style=\"{$bgStyle}\"" : '';
    $labelColor = $highlight ? '#c9a24a' : '#7a6d57';
    $labelPad   = $highlight ? '18px 20px' : '14px 20px';
    $valueColor = $highlight ? '#e6c98b'  : ($isId ? '#8a7d63' : '#d4c4a0');
    $valueSize  = $highlight ? '16px'     : ($isId ? '13px'    : '15px');
    $valuePad   = $highlight ? '18px 20px' : '14px 20px';
    $valueFont  = $isId
        ? 'font-family:Arial,sans-serif;letter-spacing:0.05em;'
        : 'font-family:Georgia,serif;';

    return <<<HTML

          <tr{$trAttr}>
            <td style="padding:{$labelPad};font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:{$labelColor};font-weight:600;border-bottom:1px solid #231e14;width:42%;">{$label}</td>
            <td style="padding:{$valuePad};{$valueFont}font-size:{$valueSize};color:{$valueColor};border-bottom:1px solid #231e14;">{$value}</td>
          </tr>
HTML;
}

function createMailer() {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@phelonpreneurnetwork.com';
    $mail->Password   = '+5olw$*a^pT';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('no-reply@phelonpreneurnetwork.com', 'The Phelonpreneur Network');

    return $mail;
}

if (!webhookHasPayment($data)) {
    $cached = cacheAppointmentPayload($data);

  

    http_response_code(200);
    exit('Appointment webhook cached. Waiting for payment webhook.');
}

$appointmentPayload = waitForAppointmentPayload($data, 8);
$data = mergeAppointmentIntoPaymentPayload($data, $appointmentPayload);



$transaction_id = extractValue($data, [
    'payment.transaction_id',
    'payment.transactionId',
    'transaction_id',
    'transactionId',
    'payment.id',
]);

if (empty($transaction_id)) {


    http_response_code(200);
    exit('Payment webhook ignored because transaction ID is missing.');
}

$processedDir = __DIR__ . '/webhook_processed';

if (!is_dir($processedDir)) {
    mkdir($processedDir, 0755, true);
}

$transactionHash = md5($transaction_id);

$processedFile = $processedDir . '/' . $transactionHash . '.txt';
$lockFile      = $processedDir . '/' . $transactionHash . '.lock';

if (file_exists($processedFile)) {
    http_response_code(200);
    exit('Duplicate payment webhook ignored.');
}


$lockHandle = @fopen($lockFile, 'x');

if ($lockHandle === false) {
    http_response_code(200);
    exit('Duplicate payment webhook currently processing.');
}

fwrite($lockHandle, date('Y-m-d H:i:s') . PHP_EOL);
fclose($lockHandle);

// ─── Database insert ──────────────────────────────────────────────────────────
try {
    $intakeId = insertIntakeRecord($data);
    webhookLog('Database insert successful', ['intake_id' => $intakeId]);
} catch (Exception $e) {
    webhookLog('Database insert failed', ['error' => $e->getMessage()]);
    if (file_exists($lockFile)) unlink($lockFile);
    http_response_code(500);
    exit('Database insert failed: ' . $e->getMessage());
}


$firstName = extractValue($data, [
    'customData.firstName',
    'contact.firstName',
    'contact.first_name',
    'first_name',
    'firstName',
    'payment.customer.first_name',
]);

$lastName = extractValue($data, [
    'customData.lastName',
    'contact.lastName',
    'contact.last_name',
    'last_name',
    'lastName',
    'payment.customer.last_name',
]);

$email = extractValue($data, [
    'customData.email',
    'contact.email',
    'email',
    'payment.customer.email',
]);

$rawPhone = extractValue($data, [
    'customData.phone',
    'contact.phone',
    'phone',
    'payment.customer.phone',
]);

$appointmentId = extractValue($data, [
    'customData.appointmentId',
    'calendar.appointmentId',
    'appointment.id',
    'appointment.appointmentId',
]);

$intakeFormUrl = '';

if (!empty($appointmentId)) {
    $intakeFormUrl = 'https://phelonpreneurnetwork.com/Intake.php?appointmentId=' . urlencode($appointmentId);
}

$intakeFormUrlSafe = htmlspecialchars($intakeFormUrl, ENT_QUOTES, 'UTF-8');

$date_chosen = extractValue($data, [
    'customData.date',
    'appointment.only_start_date',
    'appointment.date',
]);

$time_chosen = extractValue($data, [
    'customData.time',
    'appointment.only_start_time',
    'appointment.time',
]);

$time_zone = extractValue($data, [
    'customData.timeZone',
    'customData.timezone',
    'calendar.selectedTimezone',
    'appointment.timezone',
    'appointment.time_zone',
    'timezone',
    'Time Zone',
]);

$add_to_google_calendar = extractValue($data, [
    'customData.add_to_google_calendar',
    'appointment.add_to_google_calendar',
]);

$add_to_ical_outlook = extractValue($data, [
    'customData.add_to_ical_outlook',
    'appointment.add_to_ical_outlook',
]);

$reschedule_link = extractValue($data, [
    'customData.reschedule_link',
    'appointment.reschedule_link',
]);


$calendar_id = extractValue($data, [
    'payment.calendar.id',
    'calendar.id',
    'customData.calendarId',
]);

$calendar_name = extractValue($data, [
    'payment.calendar.name',
    'calendar.calendarName',
    'calendar.name',
    'customData.calendarName',
    'customData.calendar_name',
]);

$total_amount = extractValue($data, [
    'payment.total_amount',
    'customData.amount_paid',
    'total_amount',
]);

$currency_symbol = extractValue($data, [
    'payment.currency_symbol',
]);

$currency_code = extractValue($data, [
    'payment.currency_code',
]);



$phone = trim($rawPhone);
$phone = preg_replace('/[^\d\+\(\)\-\s]/', '', $phone);
$phone = preg_replace('/\s+/', ' ', $phone);

if (preg_match('/^1?(\d{10})$/', preg_replace('/\D/', '', $phone), $m)) {
    $digits = $m[1];
    $phone  = '+1 (' . substr($digits, 0, 3) . ') ' .
              substr($digits, 3, 3) . '-' .
              substr($digits, 6);
}



$payment_total_display = '';

if ($total_amount !== '') {
    $symbol = !empty($currency_symbol) ? $currency_symbol : '$';
    $payment_total_display = $symbol . number_format((float) $total_amount, 2);

    if (!empty($currency_code)) {
        $payment_total_display .= ' ' . $currency_code;
    }
}



$firstName             = cleanOutput($firstName);
$lastName              = cleanOutput($lastName);
$email                 = cleanOutput($email);
$phone                 = cleanOutput($phone);
$appointmentId         = cleanOutput($appointmentId);
$date_chosen           = cleanOutput($date_chosen);
$time_chosen           = cleanOutput($time_chosen);
$time_zone             = cleanOutput($time_zone);
$calendar_id           = cleanOutput($calendar_id);
$calendar_name         = cleanOutput($calendar_name);
$payment_total_display = cleanOutput($payment_total_display);


$clientRows =
    tableRow('First Name',    $firstName) .
    tableRow('Last Name',     $lastName,   'background:#1e1912;') .
    tableRow('Email',         $email) .
    tableRow('Phone',         $phone,      'background:#1e1912;') .
    tableRow('Appointment ID',$appointmentId, '', false, true) .
    tableRow('Date',          $date_chosen,'background:#231e14;', true) .
    tableRow('Time',          $time_chosen,'background:#231e14;', true) .
    tableRow('Program',       $calendar_name, 'background:#1e1912;') .
    tableRow('Total Paid',    $payment_total_display);

// Admin details rows
$adminClientRows =
    tableRow('First Name',    $firstName) .
    tableRow('Last Name',     $lastName,   'background:#1e1912;') .
    tableRow('Email',         $email) .
    tableRow('Phone',         $phone,      'background:#1e1912;');

$adminApptRows =
    tableRow('Date',          $date_chosen,'background:#231e14;', true) .
    tableRow('Time',          $time_chosen,'background:#231e14;', true) .
    tableRow('Time Zone',     $time_zone) .
    tableRow('Appointment ID',$appointmentId, '', false, true) .
    tableRow('Calendar ID',   $calendar_id, 'background:#1e1912;', false, true) .
    tableRow('Program',       $calendar_name) .
    tableRow('Total Paid',    $payment_total_display, 'background:#1e1912;');


$calendarButtons = '';

if (!empty($add_to_google_calendar)) {
    $calendarButtons .= "<a href='" . htmlspecialchars($add_to_google_calendar, ENT_QUOTES, 'UTF-8') . "'
       style='display:inline-block;margin:6px;padding:12px 22px;background:#c9a24a;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
       Add to Google Calendar</a>";
}

if (!empty($add_to_ical_outlook)) {
    $calendarButtons .= "<a href='" . htmlspecialchars($add_to_ical_outlook, ENT_QUOTES, 'UTF-8') . "'
       style='display:inline-block;margin:6px;padding:12px 22px;background:#1a1208;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
       Add to Outlook / iCal</a>";
}

if (!empty($reschedule_link)) {
    $calendarButtons .= "<a href='" . htmlspecialchars($reschedule_link, ENT_QUOTES, 'UTF-8') . "'
       style='display:inline-block;margin:6px;padding:12px 22px;background:#555;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
       Reschedule Appointment</a>";
}

$reschedule_link_safe = htmlspecialchars($reschedule_link, ENT_QUOTES, 'UTF-8');


$details = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Confirmation &#8211; The Phelonpreneur Network</title>
</head>

<body style="margin:0;padding:0;background-color:#0e0c08;font-family:Georgia,serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0e0c08;padding:40px 16px;">
<tr><td align="center">

  <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1510;border-radius:4px;overflow:hidden;border:1px solid #2e2618;max-width:600px;width:100%;">

    <!-- Top accent bar -->
    <tr>
      <td style="background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:3px;font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    <!-- Header -->
    <tr>
      <td style="padding:44px 48px 36px;text-align:center;background:#1a1510;">
        <img src="https://phelonpreneurnetwork.com/images/logo.png"
          alt="The Phelonpreneur Network"
          style="max-width:200px;width:100%;height:auto;display:block;margin:0 auto 28px;">

        <table width="100%" cellpadding="0" cellspacing="0"><tr>
          <td style="border-top:1px solid #2e2618;"></td>
          <td width="48" style="padding:0 14px;text-align:center;color:#c9a24a;font-size:16px;white-space:nowrap;">&#10086;</td>
          <td style="border-top:1px solid #2e2618;"></td>
        </tr></table>

        <p style="margin:22px 0 6px;font-family:Georgia,serif;font-size:11px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a24a;">
          Program Confirmed
        </p>
        <h1 style="margin:0 0 18px;font-family:Georgia,serif;font-size:30px;font-weight:400;color:#f0e6cc;line-height:1.35;letter-spacing:-0.01em;">
          Your $calendar_name <br>Request Has Been Received
        </h1>
        <p style="margin:0;font-family:Arial,sans-serif;font-size:15px;color:#9e8f72;line-height:1.7;">
          Thank you for choosing <span style="color:#c9a24a;">The Phelonpreneur Network</span>.<br>
          Please complete your intake form so our team can review your case and prepare your next steps.
        </p>
      </td>
    </tr>

    <!-- Details block -->
    <tr>
      <td style="padding:0 48px 36px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #2e2618;border-radius:3px;overflow:hidden;">

          <tr>
            <td colspan="2" style="padding:14px 20px;background:#231e14;border-bottom:1px solid #2e2618;">
              <span style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;">Booking Details</span>
            </td>
          </tr>

          {$clientRows}

        </table>
      </td>
    </tr>

    <!-- Message -->
    <tr>
      <td style="padding:0 48px 36px;text-align:center;">
        <p style="margin:0;font-family:Arial,sans-serif;font-size:14px;color:#7a6d57;line-height:1.8;">
          Need to reschedule? Simply reply to this email<br>and our team will be happy to assist you.
        </p>
      </td>
    </tr>


    <!-- Intake Form CTA -->
  <tr>
    <td style="padding:0 48px 40px;text-align:center;">
      <p style="margin:0 0 18px;font-family:Arial,sans-serif;font-size:14px;color:#9e8f72;line-height:1.8;">
        To begin reviewing your case, please complete your required intake form.
        This form helps our team understand your situation and prepare the next steps for your selected service.
      </p>

      <a href="{$intakeFormUrlSafe}"
        style="display:inline-block;padding:16px 38px;background:linear-gradient(135deg,#9a7028 0%,#c9a24a 50%,#e6c98b 100%);color:#0e0c08;text-decoration:none;border-radius:2px;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;">
        Complete Intake Form
      </a>

      <p style="margin:18px 0 0;font-family:Arial,sans-serif;font-size:12px;color:#6f624d;line-height:1.7;">
        Please use the secure link above. Your intake form is connected to your appointment ID so our team can match your submission to your service request.
      </p>
    </td>
  </tr>

    <!-- Calendar / action buttons -->
    <!-- <tr>
      <td style="padding:0 48px 40px;text-align:center;">
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;"><tr>
          <td style="border-top:1px solid #2e2618;"></td>
          <td style="padding:0 16px;white-space:nowrap;font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:#4a4130;">Manage Appointment</td>
          <td style="border-top:1px solid #2e2618;"></td>
        </tr></table>
        {$calendarButtons}
      </td>
    </tr> -->

    <!-- Bottom accent bar -->
    <tr>
      <td style="background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:1px;font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="padding:24px 48px;text-align:center;background:#120f0a;">
        <p style="margin:0 0 6px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.1em;color:#4a4130;text-transform:uppercase;">
          The Phelonpreneur Network
        </p>
        <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;color:#302a1e;">
          Readiness support only &nbsp;&middot;&nbsp; No legal advice provided.
        </p>
      </td>
    </tr>

  </table>

</td></tr>
</table>

</body>
</html>
HTML;



$adminDetails = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Booking &#8211; {$calendar_name}</title>
</head>

<body style="margin:0;padding:0;background-color:#0e0c08;font-family:Georgia,serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0e0c08;padding:40px 16px;">
<tr><td align="center">

  <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1510;border-radius:4px;overflow:hidden;border:1px solid #2e2618;max-width:600px;width:100%;">

    <!-- Top accent bar -->
    <tr>
      <td style="background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:3px;font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    <!-- Header -->
    <tr>
      <td style="padding:36px 48px 28px;text-align:center;">
        <img src="https://phelonpreneurnetwork.com/images/logo.png"
          alt="The Phelonpreneur Network"
          style="max-width:180px;width:100%;height:auto;display:block;margin:0 auto 26px;">

        <table width="100%" cellpadding="0" cellspacing="0"><tr>
          <td style="border-top:1px solid #2e2618;"></td>
          <td width="48" style="padding:0 14px;text-align:center;color:#c9a24a;font-size:14px;">&#10086;</td>
          <td style="border-top:1px solid #2e2618;"></td>
        </tr></table>

        <p style="margin:20px 0 4px;font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a24a;">
          Admin Notification
        </p>
        <h1 style="margin:0;font-family:Georgia,serif;font-size:26px;font-weight:400;color:#f0e6cc;letter-spacing:-0.01em;line-height:1.3;">
          New $calendar_name Booked
        </h1>
      </td>
    </tr>

    <!-- Alert badge -->
    <tr>
      <td style="padding:0 48px 28px;text-align:center;">
        <span style="display:inline-block;background:#231e14;border:1px solid #3a3020;border-radius:2px;padding:8px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:#c9a24a;">
          &bull; &nbsp;Action Required &mdash; Review New Booking
        </span>
      </td>
    </tr>

    <!-- Details table -->
    <tr>
      <td style="padding:0 48px 36px;">
        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #2e2618;border-radius:3px;overflow:hidden;">

          <!-- Client section -->
          <tr>
            <td colspan="2" style="padding:13px 20px;background:#231e14;border-bottom:1px solid #2e2618;">
              <span style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;">Client Information</span>
            </td>
          </tr>

          {$adminClientRows}

          <!-- Appointment section -->
          <tr>
            <td colspan="2" style="padding:13px 20px;background:#231e14;border-bottom:1px solid #2e2618;border-top:1px solid #2e2618;">
              <span style="font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;">Appointment Details</span>
            </td>
          </tr>

          {$adminApptRows}

        </table>
      </td>
    </tr>

    <!-- CTA -->
    <tr>
      <td style="padding:0 48px 44px;text-align:center;">
        <a href="{$reschedule_link_safe}"
          style="display:inline-block;padding:15px 38px;background:linear-gradient(135deg,#9a7028 0%,#c9a24a 50%,#e6c98b 100%);color:#0e0c08;text-decoration:none;border-radius:2px;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;">
          Manage Appointment
        </a>
      </td>
    </tr>

    <!-- Bottom accent bar -->
    <tr>
      <td style="background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:1px;font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="padding:22px 48px;text-align:center;background:#120f0a;">
        <p style="margin:0 0 5px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.1em;color:#4a4130;text-transform:uppercase;">
          The Phelonpreneur Network
        </p>
        <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;color:#302a1e;">
          Internal admin notification &nbsp;&middot;&nbsp; Do not forward.
        </p>
      </td>
    </tr>

  </table>

</td></tr>
</table>

</body>
</html>
HTML;



if (empty($email)) {
    file_put_contents(
        __DIR__ . '/smtp_debug.log',
        '[' . date('Y-m-d H:i:s') . '] ABORT: $email is empty — check webhook payload / extractValue paths' . PHP_EOL,
        FILE_APPEND
    );
    http_response_code(400);
    exit('No recipient email found in payload.');
}

try {

    // Client email
    $mail = createMailer();
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Next Step Required: Complete Your Intake Form - ' . $calendar_name;
    $mail->Body    = $details;
    $mail->send();
 
    // Admin email
    $adminMail = createMailer();
    $adminMail->setFrom('contact@phelonpreneurnetwork.com', 'Phelon Booking System');
    $adminMail->addAddress('phelonpreneur@gmail.com');
    $adminMail->isHTML(true);
    $adminMail->Subject = 'New ' . $calendar_name . ' Booking';
    $adminMail->Body    = $adminDetails;
    $adminMail->send();


    file_put_contents($processedFile, date('Y-m-d H:i:s') . PHP_EOL);
 
    http_response_code(200);
    echo 'Booking email sent successfully.';

} catch (Exception $e) {
  

    if (isset($lockFile) && file_exists($lockFile)) {
        unlink($lockFile);
    }

    http_response_code(500);
    echo 'Mailer Error: ' . $e->getMessage();
}

