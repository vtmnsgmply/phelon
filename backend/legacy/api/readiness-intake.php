<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
   /*
    |--------------------------------------------------------------------------
    | LOAD .ENV FILE
    |--------------------------------------------------------------------------
    */
    $envPath = __DIR__ . '/../.env';

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

    /*
    |--------------------------------------------------------------------------
    | DATABASE CONNECTION
    |--------------------------------------------------------------------------
    */
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $db   = $_ENV['DB_NAME'] ?? '';
    $user = $_ENV['DB_USERNAME'] ?? '';
    $pass = $_ENV['DB_PASSWORD'] ?? '';

    if (!$db || !$user) {
        throw new Exception('Database credentials are missing in .env file.');
    }

    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    /*
    |--------------------------------------------------------------------------
    | GET JSON INPUT
    |--------------------------------------------------------------------------
    */
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'multipart/form-data') !== false) {
        $payloadJson = $_POST['payload'] ?? '';

        $data = json_decode($payloadJson, true);

        if (!$data || !is_array($data)) {
            throw new Exception('Invalid or empty multipart payload received.');
        }

    } else {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        if (!$data || !is_array($data)) {
            throw new Exception('Invalid or empty JSON data received.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER FUNCTIONS
    |--------------------------------------------------------------------------
    */
    function getValue($data, $key, $default = null) {
        return isset($data[$key]) && $data[$key] !== '' ? $data[$key] : $default;
    }

    function getJsonValue($data, $key) {
        if (!isset($data[$key]) || $data[$key] === '') {
            return null;
        }

        if (is_array($data[$key])) {
            return json_encode($data[$key], JSON_UNESCAPED_UNICODE);
        }

        return json_encode([$data[$key]], JSON_UNESCAPED_UNICODE);
    }

    function getDateValue($data, $key) {
        if (empty($data[$key])) {
            return null;
        }

        return $data[$key];
    }

    function safeUploadName($filename) {
    $filename = basename($filename);
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
    return $filename;
}

    function saveUploadedFiles($appointmentId) {
        if (empty($_FILES['uploads'])) {
            return [];
        }

        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB per file

        $baseUploadDir = __DIR__ . '/../uploads/intake/';
        $publicBasePath = 'uploads/intake/';

        $safeAppointmentId = preg_replace('/[^a-zA-Z0-9_-]/', '_', $appointmentId);

        $targetDir = $baseUploadDir . $safeAppointmentId . '/';
        $publicDir = $publicBasePath . $safeAppointmentId . '/';

        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                throw new Exception('Failed to create upload folder.');
            }
        }

        $savedFiles = [];

        $files = $_FILES['uploads'];

        foreach ($files['name'] as $index => $originalName) {
            if ($files['error'][$index] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($files['error'][$index] !== UPLOAD_ERR_OK) {
                throw new Exception('Upload failed for file: ' . $originalName);
            }

            if ($files['size'][$index] > $maxFileSize) {
                throw new Exception('File is too large: ' . $originalName);
            }

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                throw new Exception('File type not allowed: ' . $originalName);
            }

            $safeName = safeUploadName(pathinfo($originalName, PATHINFO_FILENAME));
            $newName = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '_' . $safeName . '.' . $extension;

            $destination = $targetDir . $newName;
            $publicPath = $publicDir . $newName;

            if (!move_uploaded_file($files['tmp_name'][$index], $destination)) {
                throw new Exception('Failed to save uploaded file: ' . $originalName);
            }

            $savedFiles[] = [
                'original_name' => $originalName,
                'saved_name' => $newName,
                'path' => $publicPath,
                'size' => $files['size'][$index],
                'type' => $files['type'][$index],
            ];
        }

        return $savedFiles;
    }

    $appointmentId = trim((string) getValue($data, 'appointmentId'));

    /*
    |--------------------------------------------------------------------------
    | HANDLE FILE UPLOADS (must happen before INSERT so paths go into payload)
    |--------------------------------------------------------------------------
    */
    $savedFiles = saveUploadedFiles($appointmentId);
    if (!empty($savedFiles)) {
        $data['uploaded_files'] = $savedFiles;
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT DATA
    |--------------------------------------------------------------------------
    */
    $sql = "
        INSERT INTO readiness_intake_submissions (
            first_name,
            last_name,
            email,
            phone,
            best_phone,
            state,
            timezone,
            preferred_name,
            city,
            alternate_phone,
            best_time,
            text_consent,

            consultation_for,
            directly_impacted,
            decision_maker,
            other_decision_person,

            current_stage,
            urgency,

            has_important_date,
            important_date,
            deadline_type,
            date_readiness_need,

            concerns,
            biggest_pressure,
            helpful_consultation,

            has_attorney,
            attorney_name,
            attorney_firm,
            attorney_phone,
            attorney_email,
            avoid_professional_contact,

            family_affected,
            family_affected_details,
            financial_affected,
            financial_affected_details,
            credit_affected,
            credit_affected_details,
            business_affected,
            business_affected_details,
            reputation_affected,
            reputation_affected_details,
            prison_affected,
            prison_affected_details,
            productive_time_affected,
            productive_time_affected_details,
            reentry_affected,
            reentry_affected_details,

            consultation_goals,
            successful_consultation,

            score_family,
            score_financial,
            score_business,
            score_reputation,
            score_prison,
            score_reentry,
            score_overall,

            uploaded_files,
            raw_payload,
            ip_address,
            user_agent,
            status
        ) VALUES (
            :first_name,
            :last_name,
            :email,
            :phone,
            :best_phone,
            :state,
            :timezone,
            :preferred_name,
            :city,
            :alternate_phone,
            :best_time,
            :text_consent,

            :consultation_for,
            :directly_impacted,
            :decision_maker,
            :other_decision_person,

            :current_stage,
            :urgency,

            :has_important_date,
            :important_date,
            :deadline_type,
            :date_readiness_need,

            :concerns,
            :biggest_pressure,
            :helpful_consultation,

            :has_attorney,
            :attorney_name,
            :attorney_firm,
            :attorney_phone,
            :attorney_email,
            :avoid_professional_contact,

            :family_affected,
            :family_affected_details,
            :financial_affected,
            :financial_affected_details,
            :credit_affected,
            :credit_affected_details,
            :business_affected,
            :business_affected_details,
            :reputation_affected,
            :reputation_affected_details,
            :prison_affected,
            :prison_affected_details,
            :productive_time_affected,
            :productive_time_affected_details,
            :reentry_affected,
            :reentry_affected_details,

            :consultation_goals,
            :successful_consultation,

            :score_family,
            :score_financial,
            :score_business,
            :score_reputation,
            :score_prison,
            :score_reentry,
            :score_overall,

            :uploaded_files,
            :raw_payload,
            :ip_address,
            :user_agent,
            :status
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':first_name' => getValue($data, 'first_name'),
        ':last_name' => getValue($data, 'last_name'),
        ':email' => getValue($data, 'email'),
        ':phone' => getValue($data, 'phone'),
        ':best_phone' => getValue($data, 'best_phone'),
        ':state' => getValue($data, 'state'),
        ':timezone' => getValue($data, 'timezone'),
        ':preferred_name' => getValue($data, 'preferred_name'),
        ':city' => getValue($data, 'city'),
        ':alternate_phone' => getValue($data, 'alternate_phone'),
        ':best_time' => getValue($data, 'best_time'),
        ':text_consent' => getValue($data, 'text_consent'),

        ':consultation_for' => getValue($data, 'consultation_for'),
        ':directly_impacted' => getValue($data, 'directly_impacted'),
        ':decision_maker' => getValue($data, 'decision_maker'),
        ':other_decision_person' => getValue($data, 'other_decision_person'),

        ':current_stage' => getValue($data, 'current_stage'),
        ':urgency' => getValue($data, 'urgency'),

        ':has_important_date' => getValue($data, 'has_important_date'),
        ':important_date' => getDateValue($data, 'important_date'),
        ':deadline_type' => getValue($data, 'deadline_type'),
        ':date_readiness_need' => getValue($data, 'date_readiness_need'),

        ':concerns' => getJsonValue($data, 'concerns'),
        ':biggest_pressure' => getValue($data, 'biggest_pressure'),
        ':helpful_consultation' => getValue($data, 'helpful_consultation'),

        ':has_attorney' => getValue($data, 'has_attorney'),
        ':attorney_name' => getValue($data, 'attorney_name'),
        ':attorney_firm' => getValue($data, 'attorney_firm'),
        ':attorney_phone' => getValue($data, 'attorney_phone'),
        ':attorney_email' => getValue($data, 'attorney_email'),
        ':avoid_professional_contact' => getValue($data, 'avoid_professional_contact'),

        ':family_affected' => getValue($data, 'family_affected'),
        ':family_affected_details' => getValue($data, 'family_affected_details'),

        ':financial_affected' => getValue($data, 'financial_affected'),
        ':financial_affected_details' => getValue($data, 'financial_affected_details'),

        ':credit_affected' => getValue($data, 'credit_affected'),
        ':credit_affected_details' => getValue($data, 'credit_affected_details'),

        ':business_affected' => getValue($data, 'business_affected'),
        ':business_affected_details' => getValue($data, 'business_affected_details'),

        ':reputation_affected' => getValue($data, 'reputation_affected'),
        ':reputation_affected_details' => getValue($data, 'reputation_affected_details'),

        ':prison_affected' => getValue($data, 'prison_affected'),
        ':prison_affected_details' => getValue($data, 'prison_affected_details'),

        ':productive_time_affected' => getValue($data, 'productive_time_affected'),
        ':productive_time_affected_details' => getValue($data, 'productive_time_affected_details'),

        ':reentry_affected' => getValue($data, 'reentry_affected'),
        ':reentry_affected_details' => getValue($data, 'reentry_affected_details'),

        ':consultation_goals' => getJsonValue($data, 'consultation_goals'),
        ':successful_consultation' => getValue($data, 'successful_consultation'),

        ':score_family' => getValue($data, 'score_family'),
        ':score_financial' => getValue($data, 'score_financial'),
        ':score_business' => getValue($data, 'score_business'),
        ':score_reputation' => getValue($data, 'score_reputation'),
        ':score_prison' => getValue($data, 'score_prison'),
        ':score_reentry' => getValue($data, 'score_reentry'),
        ':score_overall' => getValue($data, 'score_overall'),

        ':uploaded_files' => !empty($savedFiles) ? json_encode($savedFiles, JSON_UNESCAPED_UNICODE) : null,
        ':raw_payload' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ':status' => 'submitted',
    ]);

    $submissionId = $pdo->lastInsertId();



    /*
    |--------------------------------------------------------------------------
    | VERIFY MATCHING INTAKE RECORD FIRST
    |--------------------------------------------------------------------------
    */
    $checkIntakeSql = "
        SELECT id_intake, appointment_id, intake
        FROM intake
        WHERE TRIM(appointment_id) = :appointment_id
        LIMIT 1
    ";

    $checkIntakeStmt = $pdo->prepare($checkIntakeSql);

    $checkIntakeStmt->execute([
        ':appointment_id' => $appointmentId
    ]);

    $existingIntake = $checkIntakeStmt->fetch();

    if (!$existingIntake) {
        throw new Exception('No matching intake record found for appointment ID: ' . $appointmentId);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE INTAKE STATUS
    |--------------------------------------------------------------------------
    */
    $updateIntakeSql = "
        UPDATE intake
        SET intake = 1
        WHERE id_intake = :id_intake
    ";

    $updateIntakeStmt = $pdo->prepare($updateIntakeSql);

    $updateIntakeStmt->execute([
        ':id_intake' => $existingIntake['id_intake']
    ]);

    /*
    |--------------------------------------------------------------------------
    | VERIFY UPDATE
    |--------------------------------------------------------------------------
    */
    $verifyStmt = $pdo->prepare("
        SELECT intake
        FROM intake
        WHERE id_intake = :id_intake
        LIMIT 1
    ");

    $verifyStmt->execute([
        ':id_intake' => $existingIntake['id_intake']
    ]);

    $verified = $verifyStmt->fetch();

    if (!$verified || (int) $verified['intake'] !== 1) {
        throw new Exception('Intake record found, but intake field did not update.');
    }

    /*
    |--------------------------------------------------------------------------
    | SEND INTAKE SUBMISSION EMAILS
    |--------------------------------------------------------------------------
    */

    require_once __DIR__ . '/intake-submission-email.php';

    sendIntakeSubmittedEmails($data, $submissionId, $appointmentId, $pdo);

    echo json_encode([
        'success' => true,
        'message' => 'Readiness intake form submitted successfully.',
        'submission_id' => $submissionId,
        'appointment_id' => $appointmentId,
        'intake_id' => $existingIntake['id_intake'],
        'intake_updated' => true,
        'email_sent' => true
    ]);
  
} catch (Throwable $e) {
    http_response_code(500);

    error_log('Readiness Intake Error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong while submitting the intake form.',
        'error' => $e->getMessage()
    ]);
}