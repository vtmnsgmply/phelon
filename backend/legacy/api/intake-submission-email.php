<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function intakeEmailValue($data, $key, $default = '') {
    return isset($data[$key]) && $data[$key] !== '' ? $data[$key] : $default;
}

function intakeEmailDisplay($value) {
    if (is_array($value)) {
        return implode(', ', array_filter($value));
    }

    return trim((string) $value);
}

function intakeEmailSafe($value) {
    return htmlspecialchars(intakeEmailDisplay($value), ENT_QUOTES, 'UTF-8');
}

function intakeEmailRow($label, $value) {
    $value = intakeEmailDisplay($value);

    if ($value === '') {
        return '';
    }

    $labelSafe = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $valueSafe = nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));

    return "
        <tr>
            <td style='padding:12px 16px;border-bottom:1px solid #2e2618;width:38%;font-family:Arial,sans-serif;font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#c9a24a;font-weight:700;vertical-align:top;'>
                {$labelSafe}
            </td>
            <td style='padding:12px 16px;border-bottom:1px solid #2e2618;font-family:Arial,sans-serif;font-size:14px;color:#f0e6cc;line-height:1.6;vertical-align:top;'>
                {$valueSafe}
            </td>
        </tr>
    ";
}

function createIntakeMailer() {
    if (!class_exists(PHPMailer::class)) {
        $autoload1 = __DIR__ . '/../vendor/autoload.php';
        $autoload2 = __DIR__ . '/vendor/autoload.php';

        if (file_exists($autoload1)) {
            require_once $autoload1;
        } elseif (file_exists($autoload2)) {
            require_once $autoload2;
        } else {
            throw new Exception('PHPMailer autoload file not found.');
        }
    }

    $mail = new PHPMailer(true);

    $smtpHost = $_ENV['SMTP_HOST'] ?? 'smtp.hostinger.com';
    $smtpUser = $_ENV['SMTP_USERNAME'] ?? 'contact@phelonpreneurnetwork.com';
    $smtpPass = $_ENV['SMTP_PASSWORD'] ?? '';
    $smtpPort = (int) ($_ENV['SMTP_PORT'] ?? 465);

    if (empty($smtpPass)) {
        throw new Exception('SMTP password is missing in .env file.');
    }

    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $smtpPort;

    $mail->setFrom($smtpUser, 'The Phelonpreneur Network');

    return $mail;
}

function buildIntakeDetailsRows($data, $submissionId, $appointmentId) {
    return
        intakeEmailRow('Submission ID', $submissionId) .
        intakeEmailRow('Appointment ID', $appointmentId) .

        intakeEmailRow('First Name', intakeEmailValue($data, 'first_name')) .
        intakeEmailRow('Last Name', intakeEmailValue($data, 'last_name')) .
        intakeEmailRow('Email', intakeEmailValue($data, 'email')) .
        intakeEmailRow('Phone', intakeEmailValue($data, 'phone')) .
        intakeEmailRow('Best Phone', intakeEmailValue($data, 'best_phone')) .
        intakeEmailRow('State', intakeEmailValue($data, 'state')) .
        intakeEmailRow('Timezone', intakeEmailValue($data, 'timezone')) .
        intakeEmailRow('Preferred Name', intakeEmailValue($data, 'preferred_name')) .
        intakeEmailRow('City', intakeEmailValue($data, 'city')) .
        intakeEmailRow('Alternate Phone', intakeEmailValue($data, 'alternate_phone')) .
        intakeEmailRow('Best Time', intakeEmailValue($data, 'best_time')) .
        intakeEmailRow('Text Consent', intakeEmailValue($data, 'text_consent')) .

        intakeEmailRow('Consultation For', intakeEmailValue($data, 'consultation_for')) .
        intakeEmailRow('Directly Impacted', intakeEmailValue($data, 'directly_impacted')) .
        intakeEmailRow('Decision Maker', intakeEmailValue($data, 'decision_maker')) .
        intakeEmailRow('Other Decision Person', intakeEmailValue($data, 'other_decision_person')) .

        intakeEmailRow('Current Stage', intakeEmailValue($data, 'current_stage')) .
        intakeEmailRow('Urgency', intakeEmailValue($data, 'urgency')) .

        intakeEmailRow('Has Important Date', intakeEmailValue($data, 'has_important_date')) .
        intakeEmailRow('Important Date', intakeEmailValue($data, 'important_date')) .
        intakeEmailRow('Deadline Type', intakeEmailValue($data, 'deadline_type')) .
        intakeEmailRow('Date Readiness Need', intakeEmailValue($data, 'date_readiness_need')) .

        intakeEmailRow('Concerns', intakeEmailValue($data, 'concerns')) .
        intakeEmailRow('Biggest Pressure', intakeEmailValue($data, 'biggest_pressure')) .
        intakeEmailRow('Helpful Consultation', intakeEmailValue($data, 'helpful_consultation')) .

        intakeEmailRow('Has Attorney', intakeEmailValue($data, 'has_attorney')) .
        intakeEmailRow('Attorney Name', intakeEmailValue($data, 'attorney_name')) .
        intakeEmailRow('Attorney Firm', intakeEmailValue($data, 'attorney_firm')) .
        intakeEmailRow('Attorney Phone', intakeEmailValue($data, 'attorney_phone')) .
        intakeEmailRow('Attorney Email', intakeEmailValue($data, 'attorney_email')) .
        intakeEmailRow('Avoid Professional Contact', intakeEmailValue($data, 'avoid_professional_contact')) .

        intakeEmailRow('Family Affected', intakeEmailValue($data, 'family_affected')) .
        intakeEmailRow('Family Details', intakeEmailValue($data, 'family_affected_details')) .
        intakeEmailRow('Financial Affected', intakeEmailValue($data, 'financial_affected')) .
        intakeEmailRow('Financial Details', intakeEmailValue($data, 'financial_affected_details')) .
        intakeEmailRow('Credit Affected', intakeEmailValue($data, 'credit_affected')) .
        intakeEmailRow('Credit Details', intakeEmailValue($data, 'credit_affected_details')) .
        intakeEmailRow('Business Affected', intakeEmailValue($data, 'business_affected')) .
        intakeEmailRow('Business Details', intakeEmailValue($data, 'business_affected_details')) .
        intakeEmailRow('Reputation Affected', intakeEmailValue($data, 'reputation_affected')) .
        intakeEmailRow('Reputation Details', intakeEmailValue($data, 'reputation_affected_details')) .
        intakeEmailRow('Prison Affected', intakeEmailValue($data, 'prison_affected')) .
        intakeEmailRow('Prison Details', intakeEmailValue($data, 'prison_affected_details')) .
        intakeEmailRow('Productive Time Affected', intakeEmailValue($data, 'productive_time_affected')) .
        intakeEmailRow('Productive Time Details', intakeEmailValue($data, 'productive_time_affected_details')) .
        intakeEmailRow('Reentry Affected', intakeEmailValue($data, 'reentry_affected')) .
        intakeEmailRow('Reentry Details', intakeEmailValue($data, 'reentry_affected_details')) .

        intakeEmailRow('Consultation Goals', intakeEmailValue($data, 'consultation_goals')) .
        intakeEmailRow('Successful Consultation', intakeEmailValue($data, 'successful_consultation')) .

        intakeEmailRow('Score: Family', intakeEmailValue($data, 'score_family')) .
        intakeEmailRow('Score: Financial', intakeEmailValue($data, 'score_financial')) .
        intakeEmailRow('Score: Business', intakeEmailValue($data, 'score_business')) .
        intakeEmailRow('Score: Reputation', intakeEmailValue($data, 'score_reputation')) .
        intakeEmailRow('Score: Prison', intakeEmailValue($data, 'score_prison')) .
        intakeEmailRow('Score: Reentry', intakeEmailValue($data, 'score_reentry')) .
        intakeEmailRow('Score: Overall', intakeEmailValue($data, 'score_overall'));
}

function buildIntakeEmailTemplate($title, $intro, $rows, $printUrl = '') {
    $titleSafe = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $introSafe = htmlspecialchars($intro, ENT_QUOTES, 'UTF-8');

    $printButton = '';

    if (!empty($printUrl)) {
        $printUrlSafe = htmlspecialchars($printUrl, ENT_QUOTES, 'UTF-8');

        $printButton = "
          <tr>
            <td style='padding:0 36px 32px;text-align:center;'>
              <p style='margin:0 0 16px;font-family:Arial,sans-serif;font-size:13px;color:#9e8f72;line-height:1.7;'>
                You may print or save a copy of these submitted intake details for your records.
              </p>

              <a href='{$printUrlSafe}'
                 target='_blank'
                 style='display:inline-block;padding:15px 34px;background:linear-gradient(135deg,#9a7028 0%,#c9a24a 50%,#e6c98b 100%);color:#0e0c08;text-decoration:none;border-radius:3px;font-family:Arial,sans-serif;font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;'>
                Print / Save Details
              </a>

              <p style='margin:16px 0 0;font-family:Arial,sans-serif;font-size:11px;color:#6f624d;line-height:1.6;'>
                If the button does not open, you can print this email directly from your email app.
              </p>
            </td>
          </tr>
        ";
    }

    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <title>{$titleSafe}</title>
    </head>
    <body style='margin:0;padding:0;background:#0e0c08;font-family:Arial,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='background:#0e0c08;padding:40px 16px;'>
        <tr>
          <td align='center'>
            <table width='680' cellpadding='0' cellspacing='0' style='max-width:680px;width:100%;background:#1a1510;border:1px solid #2e2618;border-radius:6px;overflow:hidden;'>

              <tr>
                <td style='height:4px;background:linear-gradient(90deg,#7a5c1e,#c9a24a,#e6c98b,#c9a24a,#7a5c1e);font-size:0;line-height:0;'>&nbsp;</td>
              </tr>

              <tr>
                <td style='padding:36px 40px;text-align:center;'>
                  <img src='https://phelonpreneurnetwork.com/images/logo.png'
                       alt='The Phelonpreneur Network'
                       style='max-width:190px;width:100%;height:auto;margin:0 auto 24px;display:block;'>

                  <p style='margin:0 0 8px;font-size:11px;letter-spacing:.22em;text-transform:uppercase;color:#c9a24a;font-weight:700;'>
                    Intake Form Submitted
                  </p>

                  <h1 style='margin:0 0 14px;font-family:Georgia,serif;font-weight:400;font-size:30px;line-height:1.3;color:#f0e6cc;'>
                    {$titleSafe}
                  </h1>

                  <p style='margin:0;font-size:14px;line-height:1.8;color:#9e8f72;'>
                    {$introSafe}
                  </p>
                </td>
              </tr>

              {$printButton}

              <tr>
                <td style='padding:0 36px 36px;'>
                  <table width='100%' cellpadding='0' cellspacing='0' style='border:1px solid #2e2618;border-radius:4px;overflow:hidden;'>
                    <tr>
                      <td colspan='2' style='padding:14px 16px;background:#231e14;border-bottom:1px solid #2e2618;'>
                        <span style='font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:#c9a24a;font-weight:700;'>
                          Submitted Intake Details
                        </span>
                      </td>
                    </tr>
                    {$rows}
                  </table>
                </td>
              </tr>

              {$printButton}

              <tr>
                <td style='padding:22px 36px;text-align:center;background:#120f0a;'>
                  <p style='margin:0 0 6px;font-size:12px;letter-spacing:.1em;color:#4a4130;text-transform:uppercase;'>
                    The Phelonpreneur Network
                  </p>
                  <p style='margin:0;font-size:11px;color:#302a1e;'>
                    Readiness support only &middot; No legal advice provided.
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
}



function sendIntakeSubmittedEmails($data, $submissionId, $appointmentId, PDO $pdo) {
    $appointmentId = trim((string) $appointmentId);

    if (empty($appointmentId)) {
        throw new Exception('Appointment ID is missing. Intake email was not sent.');
    }

    /*
    |--------------------------------------------------------------------------
    | GET CLIENT EMAIL FROM INTAKE TABLE
    |--------------------------------------------------------------------------
    */
    $clientStmt = $pdo->prepare("
        SELECT 
            email,
            firstName,
            lastName
        FROM intake
        WHERE TRIM(appointment_id) = :appointment_id
        LIMIT 1
    ");

    $clientStmt->execute([
        ':appointment_id' => $appointmentId
    ]);

    $intakeClient = $clientStmt->fetch(PDO::FETCH_ASSOC);

    if (!$intakeClient) {
        throw new Exception('No intake client found for appointment ID: ' . $appointmentId);
    }

    $clientEmail = trim((string) ($intakeClient['email'] ?? ''));

    if (empty($clientEmail)) {
        $clientEmail = trim((string) intakeEmailValue($data, 'email'));
    }

    if (empty($clientEmail)) {
        throw new Exception('Client email is missing. Intake notification was not sent.');
    }

    $clientFirstName = trim((string) ($intakeClient['firstName'] ?? intakeEmailValue($data, 'first_name')));
    $clientLastName  = trim((string) ($intakeClient['lastName'] ?? intakeEmailValue($data, 'last_name')));
    $clientName      = trim($clientFirstName . ' ' . $clientLastName);

    $adminEmail = trim((string) ($_ENV['INTAKE_ADMIN_EMAIL'] ?? ''));

        if (empty($adminEmail)) {
            $adminEmail = 'phelonpreneur@gmail.com';
        }

    $rows = buildIntakeDetailsRows($data, $submissionId, $appointmentId);

    $printUrl = 'https://phelonpreneurnetwork.com/intake-print.php?appointmentId=' . urlencode($appointmentId);

    $clientIntro = "Thank you for completing your intake form. Below is a copy of the details you submitted. You may print or save this information for your records.";
    $adminIntro = "A client has completed the intake form. Review the submitted details below and continue the onboarding/case review process.";

    $clientBody = buildIntakeEmailTemplate(
        'Your Intake Form Has Been Received',
        $clientIntro,
        $rows,
        $printUrl
    );

    $adminBody = buildIntakeEmailTemplate(
        'New Intake Form Submission',
        $adminIntro,
        $rows,
        $printUrl
    );

    $clientMail = createIntakeMailer();
    $clientMail->addAddress($clientEmail, $clientName ?: $clientEmail);
    $clientMail->isHTML(true);
    $clientMail->Subject = 'Your Intake Form Details - The Phelonpreneur Network';
    $clientMail->Body = $clientBody;
    $clientMail->send();

    $adminMail = createIntakeMailer();
    $adminMail->addAddress($adminEmail);
    $adminMail->isHTML(true);
    $adminMail->Subject = 'New Intake Form Submitted - ' . ($clientName ?: $clientEmail);
    $adminMail->Body = $adminBody;
    $adminMail->send();

    return true;
}