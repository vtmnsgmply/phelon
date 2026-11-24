<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 0);
error_reporting(E_ALL);

// === Load environment variables ===
include 'env.php';

// === SMTP Configuration ===
$smtpHost   = getenv("SMTP_HOST");
$smtpUser   = getenv("SMTP_USER");
$smtpPass   = getenv("SMTP_PASS");
$smtpPort   = 465;
$smtpSecure = PHPMailer::ENCRYPTION_SMTPS;

// === Parse Input (from GHL webhook or manual POST) ===
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (empty($data)) $data = $_POST;

// === Prevent Duplicate Processing ===
$requestHash = md5($input . date('Y-m-d-H-i'));
$lockFile = __DIR__ . '/locks/' . $requestHash . '.lock';

// Create locks directory if it doesn't exist
if (!is_dir(__DIR__ . '/locks')) {
    mkdir(__DIR__ . '/locks', 0755, true);
}

// Check if this exact request was already processed in the last minute
if (file_exists($lockFile)) {
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Duplicate request ignored"]);
    exit;
}

// Create lock file
file_put_contents($lockFile, time());

// Clean up old lock files (older than 5 minutes)
foreach (glob(__DIR__ . '/locks/*.lock') as $file) {
    if (time() - filemtime($file) > 300) {
        unlink($file);
    }
}

// === Helper for nested extraction ===
function extractValue($data, $keys) {
    foreach ($keys as $key) {
        $parts = explode('.', $key);
        $temp = $data;
        foreach ($parts as $part) {
            if (isset($temp[$part])) $temp = $temp[$part];
            else continue 2;
        }
        if (!empty($temp)) return trim($temp);
    }
    return '';
}

// === Extract Contact & Appointment Info ===
$firstName = extractValue($data, ['contact.firstName','contact.firstname','first_name','firstName']);
$lastName  = extractValue($data, ['contact.lastName','contact.lastname','last_name','lastName']);
$email     = extractValue($data, ['contact.email','email']);
$service   = extractValue($data, ['appointment.title','service']);
$date      = extractValue($data, ['appointment.start_date','appointment.startDate']);
$time      = extractValue($data, ['appointment.start_time','appointment.startTime']);
$timezone  = extractValue($data, ['appointment.timezone','timezone']);
$bookID  = extractValue($data, ['appointment.id','bookID']);
$type_of_service  = extractValue($data, ['contact.type_of_service','type_of_service']);
$best_way_to_reach_you  = extractValue($data, ['contact.best_way_to_reach_you','best_way_to_reach_you']);


// === Extract and Normalize Phone ===
$rawPhone = extractValue($data, [
    'customData.phone',
    'contact.phone',
    'phone'
]);

// Normalize spacing and remove excessive symbols, but keep + and parentheses
$phone = trim($rawPhone);
$phone = preg_replace('/[^\d\+\(\)\-\s]/', '', $phone); // keep digits, +, (), -, and spaces
$phone = preg_replace('/\s+/', ' ', $phone); // clean double spaces

// Optional: If starts with country code digits only (e.g. "18005550100"), reformat to +1 (800) 555-0100
if (preg_match('/^1?(\d{10})$/', preg_replace('/\D/', '', $phone), $m)) {
    $digits = $m[1];
    $phone = "+1 (" . substr($digits, 0, 3) . ") " . substr($digits, 3, 3) . "-" . substr($digits, 6);
}


// === Extract Appointment Details from Webhook ===
$calendarData = $data['calendar'] ?? [];
$customData   = $data['customData'] ?? [];

$appointment = [
    'id'        => $calendarData['appointmentId'] ?? $customData['bookID'] ?? '',
    'title'     => $calendarData['calendarName'] ?? $customData['service'] ?? '',
    'client'    => trim((ucfirst($data['first_name']) ?? ucfirst($customData['firstName']) ?? '') . ' ' . (ucfirst($data['last_name']) ?? ucfirst($customData['lastName']) ?? '')),
    'email'     => $data['email'] ?? $customData['email'] ?? '',
    'phone'     => $phone,
    'startTime' => $calendarData['startTime'] ?? $customData['time'] ?? '',
    'endTime'   => $calendarData['endTime'] ?? '',
    'date'      => $customData['date'] ?? '',
    'timezone'  => $calendarData['selectedTimezone'] ?? $customData['timezone'] ?? '',
    'status'    => ucfirst($calendarData['status']) ?? '',
    'calendar'  => $calendarData['calendarName'] ?? '',
    'location'  => $data['location']['fullAddress'] ?? '',
    'created'   => $calendarData['date_created'] ?? '',
    'type_of_service'   => $data['type_of_service'] ?? $customData['type_of_service'] ?? '',
    'best_way_to_reach_you'   => $data['best_way_to_reach_you'] ?? $customData['best_way_to_reach_you'] ?? '',

];

// === Build Appointment Details HTML Safely ===
$appointmentDetailsHTML = <<<HTML
  <div style="font-size:16px; line-height:1.6; color:#222;">
    <p><strong style="color:#153A2E;">Client:</strong> {$appointment['client']}</p>
    <p><strong style="color:#153A2E;">Email:</strong> 
      <a href="mailto:{$appointment['email']}" style="color:#153A2E; text-decoration:none;">{$appointment['email']}</a>
    </p>
    <p><strong style="color:#153A2E;">Phone:</strong> 
      <a href="tel:{$appointment['phone']}" style="color:#153A2E; text-decoration:none;">{$appointment['phone']}</a>
    </p>

    <hr style="border:0; border-top:1px solid #ddd; margin:18px 0;">

    <p><strong style="color:#153A2E;">Service:</strong> {$appointment['type_of_service']}</p>
    <p><strong style="color:#153A2E;">Best way to reach you:</strong> {$appointment['best_way_to_reach_you']}</p>
    <p><strong style="color:#153A2E;">Date:</strong> {$appointment['date']}</p>
    <p><strong style="color:#153A2E;">Time:</strong> {$appointment['startTime']} 
      <span style="color:#666;">({$appointment['timezone']})</span>
    </p>
    <p><strong style="color:#153A2E;">Status:</strong> 
      <span style="color:#333;">{$appointment['status']}</span>
    </p>

    <hr style="border:0; border-top:1px solid #ddd; margin:18px 0;">

    <p><strong style="color:#153A2E;">Calendar:</strong> {$appointment['calendar']}</p>
    <p><strong style="color:#153A2E;">Created On:</strong> {$appointment['created']}</p>

  </div>
HTML;


// === Validate Required Appointment Data ===
// Only proceed if we have complete appointment information
$requiredFields = [
    'id' => $appointment['id'],
    'title' => $appointment['title'],
    'date' => $appointment['date'],
    'startTime' => $appointment['startTime']
];

$missingFields = [];
foreach ($requiredFields as $fieldName => $fieldValue) {
    if (empty($fieldValue)) {
        $missingFields[] = $fieldName;
    }
}

// Log incomplete webhook but don't send email
if (!empty($missingFields)) {
    $logMsg = date('Y-m-d H:i:s') . " | INCOMPLETE WEBHOOK - Missing: " . implode(', ', $missingFields) . "\n";
    $logMsg .= "Raw data: " . json_encode($appointment, JSON_PRETTY_PRINT) . "\n\n";
    file_put_contents(__DIR__ . '/webhook_debug.log', $logMsg, FILE_APPEND);
    
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Webhook received but incomplete - waiting for full data"]);
    exit;
}

if (empty($email)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing email address"]);
    exit;
}



// === Build Plain Text Summary for Logging ===


// $appointmentSummary = "Client Name     : {$appointment['client']}<br>";
// $appointmentSummary .= "Email Address   : {$appointment['email']}<br>";
// $appointmentSummary .= "Phone Number    : {$appointment['phone']}<br>";
// $appointmentSummary .= "Service Title   : {$appointment['best_way_to_reach_you']}<br>";
// $appointmentSummary .= "Date (Local)    : {$appointment['date']}<br>";
// $appointmentSummary .= "Time (Start)    : {$appointment['startTime']} ({$appointment['timezone']})<br>";
// $appointmentSummary .= "Status          : {$appointment['status']}<br>";
// $appointmentSummary .= "Appointment ID  : {$appointment['id']}<br>";
// $appointmentSummary .= "Calendar Name   : {$appointment['calendar']}<br>";
// $appointmentSummary .= "Location        : {$appointment['location']}<br>";
// $appointmentSummary .= "Created On      : {$appointment['created']}<br>";
// $appointmentSummary .= "Join Link       : https://www.mwbaenterprises.com/c?bookID={$appointment['id']}<br>";


if (empty($email)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing email address"]);
    exit;
}

// === Prepare file paths ===
$filesDir = __DIR__ . '/FILESS/';
$attachments = [
    'MWBA_Clarity_Consultation_Intake_Questionnaire.docx',
    'MWBA_Clarity_Consultation_Intake_Questionnaire.docx.pdf',
    'MWBA_Education_Clarity_Agreement_v6_Nature_of_Services.docx',
    'MWBA_Education_Clarity_Agreement_v6_Nature_of_Services.pdf'
];


// === Generate properly formatted Google + Outlook Calendar URLs ===
// === STEP 1: Extract and normalize GHL calendar times ===
$startIso = $calendarData['startTime'] ?? '';
$endIso   = $calendarData['endTime'] ?? '';

// Fallback: calculate 45 minutes if end time missing
if (empty($endIso) && !empty($startIso)) {
    try {
        $dt = new DateTime($startIso);
        $dt->modify('+45 minutes');
        $endIso = $dt->format(DateTime::ATOM); // ISO 8601
    } catch (Exception $e) {
        error_log("Invalid start time: " . $e->getMessage());
        $endIso = '';
    }
}

// === STEP 2: Convert ISO to UTC timestamps for Google/Outlook ===
try {
    $startObj = new DateTime($startIso);
    $endObj   = new DateTime($endIso);
    $startTimestamp = $startObj->getTimestamp();
    $endTimestamp   = $endObj->getTimestamp();
} catch (Exception $e) {
    $startTimestamp = time();
    $endTimestamp   = $startTimestamp + (45 * 60);
}


// === Database Configuration ===
$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbCharset = getenv('DB_CHARSET') ?: 'utf8mb4';


include 'connections.php';
// === Database Insert Test ===

try {
	$pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    $insertQuery = "
        INSERT INTO clarity_consultation_intake (book_id, firstName, lastName, phone, email)
        VALUES (:book_id, :firstName, :lastName, :phone, :email)
        ON DUPLICATE KEY UPDATE
            firstName = VALUES(firstName),
            lastName  = VALUES(lastName),
            phone     = VALUES(phone),
            email     = VALUES(email)
    ";

    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        ':book_id'   => $appointment['id'],
        ':firstName' => $firstName,
        ':lastName'  => $lastName,
        ':phone'     => $phone,
        ':email'     => $email
    ]);

    echo "<p style='color:green;'>✅ Database record inserted successfully for {$email}</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Database error: {$e->getMessage()}</p>";
}


// === STEP 3: Format for Google & Outlook ===
$startDateTime = gmdate('Ymd\THis\Z', $startTimestamp); // Google format (Z = UTC)
$endDateTime   = gmdate('Ymd\THis\Z', $endTimestamp);

$googleCalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" .
    urlencode("Clarity Consultation - MWBA Education") .
    "&dates={$startDateTime}/{$endDateTime}" .
    "&details=" . urlencode("Your Clarity Consultation Session with MWBA Education") .
    "&location=" . urlencode("Online Meeting") .
    "&sf=true&output=xml";

$outlookStartDT = urlencode(gmdate('Y-m-d\TH:i:s\Z', $startTimestamp));
$outlookEndDT   = urlencode(gmdate('Y-m-d\TH:i:s\Z', $endTimestamp));

$outlookUrl = "https://outlook.live.com/calendar/0/deeplink/compose?subject=" .
    urlencode("Clarity Consultation - MWBA Education") .
    "&body=" . urlencode("Your Clarity Consultation Session with MWBA Education") .
    "&startdt={$outlookStartDT}" .
    "&enddt={$outlookEndDT}" .
    "&allday=false";

// === (Optional) For debugging ===
// file_put_contents('debug_times.log', "Start: {$startIso}\nEnd: {$endIso}\nGoogle: {$googleCalUrl}\nOutlook: {$outlookUrl}\n\n", FILE_APPEND);

	
	
// === Build Email HTML Body ===
$emailBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#000;font-family:Arial,sans-serif;">
<table width="100%" cellspacing="0" cellpadding="0" style="background:#000;padding:0;">
<tr><td align="center">
<table width="780" cellspacing="0" cellpadding="0" style="background:#fff;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.25);overflow:hidden;margin:0 auto;">
  <tr>
    <td style="background:#000;text-align:center;padding:40px 20px;">
      <img src="https://mwbaenterprises.com/images/logo2.png" alt="MWBA Education" style="max-width:220px;margin-bottom:10px;">
      <h2 style="margin:0;font-size:26px;color:#fff;">MWBA Education</h2>
      <p style="margin:8px 0 0;font-size:15px;color:#E8E8E8;">Powered by One Love Wealth Enterprise LLC</p>
    </td>
  </tr>
  <tr><td style="padding:45px 50px;color:#333;font-size:17px;line-height:1.6;">
    <p>Hi <strong>{$firstName}</strong>,</p>
    <p>Attached is your <strong>Clarity Consultation Agreement & Consent</strong> from 
    <strong>MWBA Education (powered by One Love Wealth Enterprise LLC)</strong>.</p>
    <p>It explains the <strong>nature of our services</strong>, defines the <strong>fixed 45-minute</strong> session, and includes your <strong>1-page Action Plan</strong> that will be delivered within 24 hours.</p>
  </td></tr>

  <tr><td style="padding:0 50px 30px 50px;">
  <table width="100%" style="background:#fafafa;border-left:5px solid #153A2E;border-radius:8px;">
    <tr><td style="padding:25px 30px;">
      <h3 style="margin:0 0 15px;color:#153A2E;font-size:19px;">📅 Appointment Details</h3>
      {$appointmentDetailsHTML}

      <div style="margin-top:25px;text-align:center;">
        <p style="margin-bottom:10px;color:#333;">Add this session to your calendar:</p>
        <table align="center" cellpadding="6" cellspacing="0" style="margin:auto;">
          <tr>
            <td>
              <a href="{$googleCalUrl}"
                 target="_blank"
                 style="background:#D93025;color:#fff;padding:10px 18px;text-decoration:none;border-radius:6px;font-weight:bold;display:inline-block;">Google Calendar</a>
            </td>
            <td>
              <a href="{$outlookUrl}"
                 target="_blank"
                 style="background:#0078D4;color:#fff;padding:10px 18px;text-decoration:none;border-radius:6px;font-weight:bold;display:inline-block;">Outlook</a>
            </td>
            <td>
              <a href="https://calendly.com/app/scheduled_events/user/me?name=Clarity+Consultation&details=MWBA+Education"
                 target="_blank"
                 style="background:#000;color:#fff;padding:10px 18px;text-decoration:none;border-radius:6px;font-weight:bold;display:inline-block;">Calendly</a>
            </td>
          </tr>
        </table>
      </div>
    </td></tr>
  </table>
</td></tr>

  <tr><td style="padding:0 50px 30px 50px;">
    <table width="100%" style="background:#fff;border-radius:8px;border:1px solid #e0e0e0;">
      <tr><td style="padding:25px 30px;">
        <h3 style="margin:0 0 15px;color:#153A2E;font-size:19px;">📝 Next Steps</h3>
        <ol style="margin:0 0 20px 20px;">
          <li>Open the e-signature link (or the attached PDF/Doc)</li>
          <li>Initial the "Client Understanding" statements</li>
          <li>Sign and submit</li>
        </ol>
        <div style="text-align:center;margin-top:30px;">
          <a href="https://www.mwbaenterprises.com/c?bookID={$appointment['id']}" target="_blank" style="background:#D4AF37;color:#000;text-decoration:none;
          padding:15px 40px;border-radius:8px;font-weight:bold;display:inline-block;">
          📄 Review & Sign Agreement</a>
        </div>
      </td></tr>
    </table>
  </td></tr>

  <tr><td style="padding:0 50px 30px 50px;">
    <table width="100%" style="background:#fafafa;border-left:5px solid #D4AF37;border-radius:8px;">
      <tr><td style="padding:25px 30px;">
        <h3 style="margin:0 0 10px;color:#D4AF37;font-size:19px;">📎 Attachments Included</h3>
        <p>You'll find <strong>four attachments</strong> — two documents, each in both 
        <strong>PDF</strong> and <strong>DOCX</strong> formats, so you can choose whichever version works best for signing and saving.</p>
      </td></tr>
    </table>
  </td></tr>

  <tr><td style="padding:0 50px 45px 50px;">
    <table width="100%" style="background:#fff;border-radius:8px;border:1px solid #e0e0e0;">
      <tr><td style="padding:25px 30px;">
        <h3 style="margin:0 0 10px;color:#153A2E;font-size:19px;">📞 Contact Information</h3>
        <p>📧 <a href="mailto:sl@mwbaenterprises.com" style="color:#153A2E;">sl@mwbaenterprises.com</a><br>
           ☎️ <a href="tel:18887053922" style="color:#153A2E;">1-888-705-3922</a><br>
           📱 <a href="tel:15167013274" style="color:#153A2E;">+1 (516) 701-3274</a></p>
        <p style="margin-top:25px;">— <strong>MWBA Education</strong></p>
      </td></tr>
    </table>
  </td></tr>

  <tr><td style="background:#000;text-align:center;padding:25px;font-size:12px;color:#ccc;">
    © 2025 MWBA Education • All Rights Reserved
  </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;





// === Send Email ===
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port       = $smtpPort;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($smtpUser, 'MWBA Education');
    $mail->addAddress($email, " - $firstName $lastName");
    $mail->isHTML(true);
    $mail->Subject = "Please review & sign: Clarity Consultation Agreement & Consent";
    $mail->Body    = $emailBody;

    // Attach all files
    foreach ($attachments as $file) {
        $path = $filesDir . $file;
        if (file_exists($path)) {
            $mail->addAttachment($path);
        }
    }

    $mail->send();
	// === Log success and respond ===
	
	
	
	 // === ✅ SECOND EMAIL: ADMIN NOTIFICATION ===
    $adminMail = new PHPMailer(true);
    $adminMail->isSMTP();
    $adminMail->Host       = $smtpHost;
    $adminMail->SMTPAuth   = true;
    $adminMail->Username   = $smtpUser;
    $adminMail->Password   = $smtpPass;
    $adminMail->SMTPSecure = $smtpSecure;
    $adminMail->Port       = $smtpPort;
    $adminMail->CharSet    = 'UTF-8';

    $adminMail->setFrom($smtpUser, 'MWBA System Notification');
    $adminMail->addAddress('mindfulnessstudentloan@gmail.com', 'MWBA Admin');
    $adminMail->isHTML(true);
    $adminMail->Subject = "📩 New Clarity Consultation Booking: {$firstName} {$lastName}";

    $adminBody = <<<HTML
<div style="background:#f4f4f4;padding:40px 0;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="600" align="center" cellpadding="0" cellspacing="0" 
         style="background:#ffffff;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);overflow:hidden;">
    
    <!-- Header -->
    <tr>
      <td style="background:#000000;text-align:center;padding:30px 20px;">
        <img src="https://mwbaenterprises.com/images/logo2.png" alt="MWBA Enterprises" 
             style="max-width:200px;margin-bottom:8px;">
        <h2 style="color:#D4AF37;margin:0;font-size:22px;font-weight:700;">New Consultation Booking</h2>
        <p style="color:#CCCCCC;font-size:14px;margin:4px 0 0;">Automated Notification • MWBA Education</p>
      </td>
    </tr>

    <!-- Main Content -->
    <tr>
      <td style="padding:30px 40px;color:#222;font-size:16px;line-height:1.6;">
        <p style="margin:0 0 15px;">A new client has scheduled a <strong>Clarity Consultation</strong> session.</p>

        <table width="100%" cellpadding="0" cellspacing="0" 
               style="border-collapse:collapse;border:1px solid #eee;border-radius:6px;overflow:hidden;">
          <tbody>
            <tr style="background:#fafafa;">
              <td style="padding:10px 15px;width:35%;font-weight:600;color:#153A2E;">Client</td>
              <td style="padding:10px 15px;">{$firstName} {$lastName}</td>
            </tr>
            <tr>
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Email</td>
              <td style="padding:10px 15px;"><a href="mailto:{$email}" style="color:#153A2E;">{$email}</a></td>
            </tr>
            <tr style="background:#fafafa;">
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Phone</td>
              <td style="padding:10px 15px;">{$phone}</td>
            </tr>
            <tr>
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Service</td>
              <td style="padding:10px 15px;">{$appointment['type_of_service']}</td>
            </tr>
            <tr style="background:#fafafa;">
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Date</td>
              <td style="padding:10px 15px;">{$appointment['date']}</td>
            </tr>
            <tr>
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Time</td>
              <td style="padding:10px 15px;">{$appointment['startTime']} <span style="color:#777;">({$appointment['timezone']})</span></td>
            </tr>
            <tr style="background:#fafafa;">
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Best Way to Reach</td>
              <td style="padding:10px 15px;">{$appointment['best_way_to_reach_you']}</td>
            </tr>
            <tr>
              <td style="padding:10px 15px;font-weight:600;color:#153A2E;">Calendar</td>
              <td style="padding:10px 15px;">{$appointment['calendar']}</td>
            </tr>
          </tbody>
        </table>

        <!-- CTA -->
        <div style="text-align:center;margin-top:30px;">
          <a href="https://www.mwbaenterprises.com/c?bookID={$appointment['id']}"
             target="_blank"
             style="background:#D4AF37;color:#000;font-weight:700;
             padding:14px 28px;border-radius:6px;text-decoration:none;
             display:inline-block;box-shadow:0 2px 5px rgba(0,0,0,0.2);">
             View Appointment Dashboard
          </a>
        </div>

        <p style="font-size:13px;color:#777;margin-top:35px;">
          This notification was automatically generated by MWBA Education's booking system.<br>
          If you believe you received this in error, please contact <a href="mailto:sl@mwbaenterprises.com" style="color:#153A2E;">sl@mwbaenterprises.com</a>.
        </p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background:#000;text-align:center;padding:15px;color:#ccc;font-size:12px;">
         2020 © 2025 MWBA Enterprises • All Rights Reserved
      </td>
    </tr>
  </table>
</div>
HTML;



    $adminMail->Body = $adminBody;
    $adminMail->send();
	
	
	
	
	// file_put_contents(__DIR__ . '/webhook_debug.log', $appointmentSummary, FILE_APPEND);
	
	file_put_contents(__DIR__ . '/webhook_debug.log',
		date('Y-m-d H:i:s') . " | RAW WEBHOOK INPUT:\n{$input}\n\n", FILE_APPEND);

    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Email sent successfully to {$email}"]);
} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $mail->ErrorInfo]);
}



?>
