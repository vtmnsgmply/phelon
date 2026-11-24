<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$input = file_get_contents('php://input');

$data  = json_decode($input, true);

file_put_contents(
    __DIR__ . '/webhook_debug.json',
    json_encode($data, JSON_PRETTY_PRINT)
);

if (empty($data)) $data = $_POST;

function extractValue($data, $keys) {
    foreach ($keys as $key) {
        $parts = explode('.', $key);
        $temp  = $data;
        foreach ($parts as $part) {
            if (isset($temp[$part])) $temp = $temp[$part];
            else continue 2;
        }
        if (!empty($temp)) return trim($temp);
    }
    return '';
}

$firstName          = extractValue($data, ['contact.firstName','contact.first_name','first_name','firstName']);
$lastName           = extractValue($data, ['contact.lastName','contact.last_name','last_name','lastName']);
$email              = extractValue($data, ['contact.email','email']);
$time_zone = extractValue($data, [
    'customData.time_zone',
    'Time Zone'
]);

$preferred_contact = extractValue($data, [
    'customData.preferred_contact',
    'Preferred Contact'
]);

$basic_reason_for_the_call = extractValue($data, [
    'customData.basic_reason_for_the_call',
    'Basic reason for the call'
]);

$current_situation_stage = extractValue($data, [
    'customData.current_situation_stage',
    'Current Situation Stage'
]);

$main_concern_right_now = extractValue($data, [
    'customData.main_concern_right_now',
    'Main Concern Right Now'
]);

$important_date = extractValue($data, [
    'customData.is_there_an_upcoming_important_date',
    'Is There an Upcoming Important Date?'
]);

$appointmentId = extractValue($data, [
    'customData.appointmentId',
    'appointment.id',
    'appointment.appointmentId'
]);

$date_chosen = extractValue($data, [
    'customData.date',
    'appointment.only_start_date',
    'appointment.date'
]);

$time_chosen = extractValue($data, [
    'customData.time',
    'appointment.only_start_time',
    'appointment.time'
]);

$add_to_google_calendar = extractValue($data, [
    'customData.add_to_google_calendar',
    'appointment.add_to_google_calendar',
    'appointment.add_to_google_calendar'
]);

$add_to_ical_outlook = extractValue($data, [
    'customData.add_to_ical_outlook',
    'appointment.add_to_ical_outlook',
    'appointment.add_to_ical_outlook'
]);

$reschedule_link = extractValue($data, [
    'customData.reschedule_link',
    'appointment.reschedule_link',
    'appointment.reschedule_link'
]);



$rawPhone = extractValue($data, [
    'customData.phone',
    'contact.phone',
    'phone'
]);

$phone = trim($rawPhone);
$phone = preg_replace('/[^\d\+\(\)\-\s]/', '', $phone);
$phone = preg_replace('/\s+/', ' ', $phone);



// === Format US Phone ===
if (preg_match('/^1?(\d{10})$/', preg_replace('/\D/', '', $phone), $m)) {
    $digits = $m[1];
    $phone = "+1 (" . substr($digits, 0, 3) . ") " .
             substr($digits, 3, 3) . "-" .
             substr($digits, 6);
}


// $logFile = __DIR__ . '/aa_webhook.log';
// file_put_contents($logFile, $input, FILE_APPEND);

$calendarButtons = "";

if (!empty($add_to_google_calendar)) {
    $calendarButtons .= "
    <a href='$add_to_google_calendar'
    style='display:inline-block;margin:6px;padding:12px 22px;background:#c9a24a;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
    Add to Google Calendar
    </a>";
}

if (!empty($add_to_ical_outlook)) {
    $calendarButtons .= "
    <a href='$add_to_ical_outlook'
    style='display:inline-block;margin:6px;padding:12px 22px;background:#1a1208;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
    Add to Outlook / iCal
    </a>";
}

if (!empty($reschedule_link)) {
    $calendarButtons .= "
    <a href='$reschedule_link'
    style='display:inline-block;margin:6px;padding:12px 22px;background:#555;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>
    Reschedule Appointment
    </a>";
}

$details = "<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Appointment Confirmation – The Phelonpreneur Network</title>
</head>

<body style='margin:0;padding:0;background-color:#0e0c08;font-family:Georgia,serif;'>

<!-- Outer wrapper -->
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#0e0c08;padding:40px 16px;'>
<tr>
<td align='center'>

  <!-- Card -->
  <table width='600' cellpadding='0' cellspacing='0' style='background:#1a1510;border-radius:4px;overflow:hidden;border:1px solid #2e2618;max-width:600px;width:100%;'>

    <!-- Top accent bar -->
    <tr>
      <td style='background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:3px;font-size:0;line-height:0;'>&nbsp;</td>
    </tr>

    <!-- Header -->
    <tr>
      <td style='padding:44px 48px 36px;text-align:center;background:#1a1510;'>
        <img src='https://phelonpreneurnetwork.com/images/logo.png'
          alt='The Phelonpreneur Network'
          style='max-width:200px;width:100%;height:auto;display:block;margin:0 auto 28px;'>
        
        <!-- Thin gold rule -->
        <table width='100%' cellpadding='0' cellspacing='0'><tr>
          <td style='border-top:1px solid #2e2618;'></td>
          <td width='48' style='padding:0 14px;text-align:center;color:#c9a24a;font-size:16px;white-space:nowrap;'>✦</td>
          <td style='border-top:1px solid #2e2618;'></td>
        </tr></table>

        <p style='margin:22px 0 6px;font-family:Georgia,serif;font-size:11px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a24a;'>
          Appointment Confirmed
        </p>
        <h1 style='margin:0 0 18px;font-family:Georgia,serif;font-size:30px;font-weight:400;color:#f0e6cc;line-height:1.35;letter-spacing:-0.01em;'>
          Your Discovery Call<br>is Scheduled
        </h1>
        <p style='margin:0;font-family:Arial,sans-serif;font-size:15px;color:#9e8f72;line-height:1.7;'>
          Thank you for connecting with <span style='color:#c9a24a;'>The Phelonpreneur Network</span>.<br>
          We look forward to speaking with you.
        </p>
      </td>
    </tr>

    <!-- Details block -->
    <tr>
      <td style='padding:0 48px 36px;'>
        <table width='100%' cellpadding='0' cellspacing='0' style='border:1px solid #2e2618;border-radius:3px;overflow:hidden;'>
          
          <!-- Section label -->
          <tr>
            <td colspan='2' style='padding:14px 20px;background:#231e14;border-bottom:1px solid #2e2618;'>
              <span style='font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;'>Appointment Details</span>
            </td>
          </tr>

          <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;width:42%;'>First Name</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$firstName</td>
          </tr>

          <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Last Name</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$lastName</td>
          </tr>

          <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Email</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$email</td>
          </tr>

          <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Phone</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$phone</td>
          </tr>

          <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Appointment ID</td>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:13px;color:#8a7d63;letter-spacing:0.05em;border-bottom:1px solid #231e14;'>$appointmentId</td>
          </tr>

          <!-- Date & time highlighted row -->
          <tr style='background:#231e14;'>
            <td style='padding:18px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#c9a24a;font-weight:600;border-bottom:1px solid #2e2618;'>Date</td>
            <td style='padding:18px 20px;font-family:Georgia,serif;font-size:16px;color:#e6c98b;font-weight:400;border-bottom:1px solid #2e2618;'>$date_chosen</td>
          </tr>

          <tr style='background:#231e14;'>
            <td style='padding:18px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#c9a24a;font-weight:600;'>Time</td>
            <td style='padding:18px 20px;font-family:Georgia,serif;font-size:16px;color:#e6c98b;font-weight:400;'>$time_chosen</td>
          </tr>
          
        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Time Zone</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$time_zone</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Preferred Contact</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$preferred_contact</td>
        </tr>

        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Reason For Call</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$basic_reason_for_the_call</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Current Stage</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$current_situation_stage</td>
        </tr>

        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Main Concern</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$main_concern_right_now</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Important Date</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$important_date</td>
        </tr>
        </table>
      </td>
    </tr>
    <!-- Message -->
    <tr>
      <td style='padding:0 48px 36px;text-align:center;'>
        <p style='margin:0;font-family:Arial,sans-serif;font-size:14px;color:#7a6d57;line-height:1.8;'>
          Need to reschedule? Simply reply to this email<br>and our team will be happy to assist you.
        </p>
      </td>
    </tr>

    <!-- CTA button -->
    <tr>
      <td style='padding:0 48px 40px;text-align:center;'>
        <a href='https://phelonpreneurnetwork.com'
          style='display:inline-block;padding:15px 38px;background:linear-gradient(135deg,#9a7028 0%,#c9a24a 50%,#e6c98b 100%);color:#0e0c08;text-decoration:none;border-radius:2px;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;'>
          Visit Our Website
        </a>
      </td>
    </tr>

    <!-- Calendar section -->
    <tr>
      <td style='padding:0 48px 40px;text-align:center;'>
        <!-- Divider with label -->
        <table width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:22px;'><tr>
          <td style='border-top:1px solid #2e2618;'></td>
          <td style='padding:0 16px;white-space:nowrap;font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.16em;text-transform:uppercase;color:#4a4130;'>Manage Appointment</td>
          <td style='border-top:1px solid #2e2618;'></td>
        </tr></table>

        $calendarButtons
      </td>
    </tr>

    <!-- Bottom accent bar -->
    <tr>
      <td style='background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:1px;font-size:0;line-height:0;'>&nbsp;</td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style='padding:24px 48px;text-align:center;background:#120f0a;'>
        <p style='margin:0 0 6px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.1em;color:#4a4130;text-transform:uppercase;'>
          The Phelonpreneur Network
        </p>
        <p style='margin:0;font-family:Arial,sans-serif;font-size:11px;color:#302a1e;'>
          Readiness support only &nbsp;·&nbsp; No legal advice provided.
        </p>
      </td>
    </tr>

  </table>

</td>
</tr>
</table>

</body>
</html>";


$adminDetails = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>New Discovery Call Booked</title>
</head>

<body style='margin:0;padding:0;background-color:#0e0c08;font-family:Georgia,serif;'>

<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#0e0c08;padding:40px 16px;'>
<tr>
<td align='center'>

  <table width='600' cellpadding='0' cellspacing='0' style='background:#1a1510;border-radius:4px;overflow:hidden;border:1px solid #2e2618;max-width:600px;width:100%;'>

    <!-- Top accent bar -->
    <tr>
      <td style='background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:3px;font-size:0;line-height:0;'>&nbsp;</td>
    </tr>

    <!-- Header -->
    <tr>
      <td style='padding:36px 48px 28px;text-align:center;'>
        <img src='https://phelonpreneurnetwork.com/images/logo.png'
          alt='The Phelonpreneur Network'
          style='max-width:180px;width:100%;height:auto;display:block;margin:0 auto 26px;'>

        <table width='100%' cellpadding='0' cellspacing='0'><tr>
          <td style='border-top:1px solid #2e2618;'></td>
          <td width='48' style='padding:0 14px;text-align:center;color:#c9a24a;font-size:14px;'>✦</td>
          <td style='border-top:1px solid #2e2618;'></td>
        </tr></table>

        <p style='margin:20px 0 4px;font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.22em;text-transform:uppercase;color:#c9a24a;'>
          Admin Notification
        </p>
        <h1 style='margin:0;font-family:Georgia,serif;font-size:26px;font-weight:400;color:#f0e6cc;letter-spacing:-0.01em;line-height:1.3;'>
          New Discovery Call Booked
        </h1>
      </td>
    </tr>

    <!-- Alert badge -->
    <tr>
      <td style='padding:0 48px 28px;text-align:center;'>
        <span style='display:inline-block;background:#231e14;border:1px solid #3a3020;border-radius:2px;padding:8px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:#c9a24a;'>
          ● &nbsp;Action Required — Review New Booking
        </span>
      </td>
    </tr>

    <!-- Details table -->
    <tr>
      <td style='padding:0 48px 36px;'>
        <table width='100%' cellpadding='0' cellspacing='0' style='border:1px solid #2e2618;border-radius:3px;overflow:hidden;'>

          <tr>
            <td colspan='2' style='padding:13px 20px;background:#231e14;border-bottom:1px solid #2e2618;'>
              <span style='font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;'>Client Information</span>
            </td>
          </tr>

          <tr>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;width:40%;'>First Name</td>
            <td style='padding:13px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$firstName</td>
          </tr>

          <tr style='background:#1e1912;'>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Last Name</td>
            <td style='padding:13px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$lastName</td>
          </tr>

          <tr>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Email</td>
            <td style='padding:13px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$email</td>
          </tr>

          <tr style='background:#1e1912;'>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #2e2618;'>Phone</td>
            <td style='padding:13px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #2e2618;'>$phone</td>
          </tr>

          <!-- Spacer label row -->
          <tr>
            <td colspan='2' style='padding:13px 20px;background:#231e14;border-bottom:1px solid #2e2618;border-top:1px solid #2e2618;'>
              <span style='font-family:Arial,sans-serif;font-size:10px;letter-spacing:0.18em;text-transform:uppercase;color:#c9a24a;'>Appointment Details</span>
            </td>
          </tr>

          <tr style='background:#231e14;'>
            <td style='padding:16px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#c9a24a;font-weight:600;border-bottom:1px solid #2e2618;'>Date</td>
            <td style='padding:16px 20px;font-family:Georgia,serif;font-size:16px;color:#e6c98b;border-bottom:1px solid #2e2618;'>$date_chosen</td>
          </tr>

          <tr style='background:#231e14;'>
            <td style='padding:16px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#c9a24a;font-weight:600;border-bottom:1px solid #2e2618;'>Time</td>
            <td style='padding:16px 20px;font-family:Georgia,serif;font-size:16px;color:#e6c98b;border-bottom:1px solid #2e2618;'>$time_chosen</td>
          </tr>

          <tr>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;'>Appointment ID</td>
            <td style='padding:13px 20px;font-family:Arial,sans-serif;font-size:13px;color:#6a5f48;letter-spacing:0.05em;'>$appointmentId</td>
          </tr>
          
        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Time Zone</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$time_zone</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Preferred Contact</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$preferred_contact</td>
        </tr>

        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Reason For Call</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$basic_reason_for_the_call</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Current Stage</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$current_situation_stage</td>
        </tr>

        <tr>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Main Concern</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$main_concern_right_now</td>
        </tr>

        <tr style='background:#1e1912;'>
            <td style='padding:14px 20px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#7a6d57;font-weight:600;border-bottom:1px solid #231e14;'>Important Date</td>
            <td style='padding:14px 20px;font-family:Georgia,serif;font-size:15px;color:#d4c4a0;border-bottom:1px solid #231e14;'>$important_date</td>
        </tr>
        </table>
      </td>
    </tr>

    <!-- CTA -->
    <tr>
      <td style='padding:0 48px 44px;text-align:center;'>
        <a href='$reschedule_link'
          style='display:inline-block;padding:15px 38px;background:linear-gradient(135deg,#9a7028 0%,#c9a24a 50%,#e6c98b 100%);color:#0e0c08;text-decoration:none;border-radius:2px;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;'>
          Manage Appointment
        </a>
      </td>
    </tr>

    <!-- Bottom accent bar -->
    <tr>
      <td style='background:linear-gradient(90deg,#7a5c1e 0%,#c9a24a 40%,#e6c98b 60%,#c9a24a 80%,#7a5c1e 100%);height:1px;font-size:0;line-height:0;'>&nbsp;</td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style='padding:22px 48px;text-align:center;background:#120f0a;'>
        <p style='margin:0 0 5px;font-family:Arial,sans-serif;font-size:12px;letter-spacing:0.1em;color:#4a4130;text-transform:uppercase;'>
          The Phelonpreneur Network
        </p>
        <p style='margin:0;font-family:Arial,sans-serif;font-size:11px;color:#302a1e;'>
          Internal admin notification &nbsp;·&nbsp; Do not forward.
        </p>
      </td>
    </tr>

  </table>

</td>
</tr>
</table>

</body>
</html>
";


$mail = new PHPMailer(true);

function createMailer() {

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@phelonpreneurnetwork.com';
    $mail->Password   = '+5olw$*a^pT';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('contact@phelonpreneurnetwork.com', 'The Phelonpreneur Network');

    return $mail;
}

try {

    $mail = createMailer();
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'From The Phelonpreneur Network - Free Discovery Call';
    $mail->Body    = $details;
    $mail->send();

    $adminMail = createMailer();
    $adminMail->setFrom('contact@phelonpreneurnetwork.com', 'Phelon Booking System');
    $adminMail->addAddress('phelonpreneur@gmail.com');
    $adminMail->isHTML(true);
    $adminMail->Subject = 'New Discovery Call Booking';
    $adminMail->Body = $adminDetails;
    $adminMail->send();


} catch (Exception $e) {

    echo "Mailer Error: {$mail->ErrorInfo}";
}

