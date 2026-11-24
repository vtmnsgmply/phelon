<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

define('WEBHOOK_LOG_FILE', __DIR__ . '/logs/ghl-webhook.log');
define('WEBHOOK_LOCK_DIR', __DIR__ . '/locks');
define('DUPLICATE_TTL_SECONDS', 300);

foreach ([dirname(WEBHOOK_LOG_FILE), WEBHOOK_LOCK_DIR] as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

/*
|--------------------------------------------------------------------------
| Request ID
|--------------------------------------------------------------------------
*/

try {
    $requestId = date('Ymd-His') . '-' . bin2hex(random_bytes(4));
} catch (Throwable $e) {
    $requestId = date('Ymd-His') . '-' . uniqid();
}

/*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
*/

function webhookLog(string $message, $data = null): void
{
    global $requestId;

    $line = PHP_EOL
        . '============================================================' . PHP_EOL
        . '[' . date('Y-m-d H:i:s') . '] [' . $requestId . ']' . PHP_EOL
        . $message . PHP_EOL;

    if ($data !== null) {
        if (is_array($data) || is_object($data)) {
            $line .= json_encode(
                $data,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ) . PHP_EOL;
        } else {
            $line .= (string)$data . PHP_EOL;
        }
    }

    $line .= '============================================================' . PHP_EOL;

    @file_put_contents(
        WEBHOOK_LOG_FILE,
        $line,
        FILE_APPEND | LOCK_EX
    );

    error_log(
        '[' . $requestId . '] ' . $message
        . ($data !== null
            ? ' | ' . (is_array($data) || is_object($data)
                ? json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                : (string)$data)
            : '')
    );
}

function respondJson(int $statusCode, array $payload): void
{
    webhookLog('WEBHOOK RESPONSE', [
        'status_code' => $statusCode,
        'payload' => $payload,
    ]);

    http_response_code($statusCode);

    echo json_encode(
        $payload,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Error Handling
|--------------------------------------------------------------------------
*/

set_exception_handler(function (Throwable $e): void {
    webhookLog('WEBHOOK UNCAUGHT EXCEPTION', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);

    respondJson(500, [
        'success' => false,
        'message' => 'Internal webhook error.',
    ]);
});

register_shutdown_function(function (): void {
    $error = error_get_last();

    if ($error === null) {
        return;
    }

    $fatalTypes = [
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
    ];

    if (in_array($error['type'], $fatalTypes, true)) {
        webhookLog('WEBHOOK FATAL ERROR', $error);
    }
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function getRequestHeadersSafe(): array
{
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (is_array($headers)) {
            return $headers;
        }
    }

    $headers = [];

    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') !== 0) {
            continue;
        }

        $headerName = str_replace(
            ' ',
            '-',
            ucwords(
                strtolower(
                    str_replace('_', ' ', substr($key, 5))
                )
            )
        );

        $headers[$headerName] = $value;
    }

    return $headers;
}

function postedData(string $rawInput): array
{
    if ($rawInput !== '') {
        $decoded = json_decode($rawInput, true);

        webhookLog('JSON DECODE ATTEMPT', [
            'json_error_code' => json_last_error(),
            'json_error_message' => json_last_error_msg(),
            'decoded_data' => $decoded,
        ]);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            webhookLog('PAYLOAD TYPE DETECTED: JSON');
            return $decoded;
        }
    }

    if (!empty($_POST)) {
        webhookLog('PAYLOAD TYPE DETECTED: POST FORM');
        return $_POST;
    }

    webhookLog('PAYLOAD COULD NOT BE PARSED');
    return [];
}

function normalizeCustomData(array $data): array
{
    if (!isset($data['customData']) || !is_string($data['customData'])) {
        return $data;
    }

    $decoded = json_decode($data['customData'], true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $data['customData'] = $decoded;
    }

    return $data;
}

function getPostedValue(array $data, array $keys): string
{
    foreach ($keys as $key) {
        $parts = explode('.', $key);
        $value = $data;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                continue 2;
            }

            $value = $value[$part];
        }

        if ($value === null || $value === '') {
            continue;
        }

        if (is_array($value) || is_object($value)) {
            continue;
        }

        return trim((string)$value);
    }

    return '';
}

function normalizeMoneyNullable(string $value): ?float
{
    if ($value === '') {
        return null;
    }

    $normalized = preg_replace('/[^0-9.\-]/', '', $value);

    if ($normalized === '' || !is_numeric($normalized)) {
        return null;
    }

    return round((float)$normalized, 2);
}

function normalizePhoneKey(string $phone): string
{
    return preg_replace('/\D+/', '', $phone) ?? '';
}

function nullableString(string $value): ?string
{
    return $value === '' ? null : $value;
}

function limitString(string $value, int $length): string
{
    $value = trim($value);

    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $length, 'UTF-8');
    }

    return substr($value, 0, $length);
}

function htmlEscape(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/*
|--------------------------------------------------------------------------
| Environment Helper
|--------------------------------------------------------------------------
|
| env.php loads .env and provides envValue().
| This helper uses envValue() when available, then getenv() as fallback.
|
|--------------------------------------------------------------------------
*/

function smtpEnvValue(
    string $key,
    ?string $fallbackKey = null,
    string $default = ''
): string {
    if (function_exists('envValue')) {
        return envValue($key, $fallbackKey, $default);
    }

    $value = getenv($key);

    if ($value !== false && $value !== '') {
        return (string)$value;
    }

    if ($fallbackKey !== null) {
        $fallback = getenv($fallbackKey);

        if ($fallback !== false && $fallback !== '') {
            return (string)$fallback;
        }
    }

    return $default;
}

/*
|--------------------------------------------------------------------------
| SMTP Debug Sanitizer
|--------------------------------------------------------------------------
|
| Detailed SMTP logging without writing passwords/auth credentials.
|
|--------------------------------------------------------------------------
*/

function sanitizeSmtpDebugLine(
    string $line,
    string $smtpUser = '',
    string $smtpPass = ''
): string {
    $clean = trim($line);

    if ($smtpPass !== '') {
        $clean = str_replace(
            $smtpPass,
            '[REDACTED_PASSWORD]',
            $clean
        );
    }

    if (
        stripos($clean, 'password') !== false
        || stripos($clean, 'username') !== false
    ) {
        return preg_replace(
            '/(CLIENT\\s*->\\s*SERVER:\\s*).*/i',
            '$1[REDACTED AUTH DATA]',
            $clean
        ) ?: '[REDACTED AUTH DATA]';
    }

    if (preg_match('/CLIENT\\s*->\\s*SERVER:\\s*AUTH\\b/i', $clean)) {
        return 'CLIENT -> SERVER: AUTH [REDACTED]';
    }

    return $clean;
}

function createDuplicateLock(string $rawInput, array $data): bool
{
    $source = $rawInput !== ''
        ? $rawInput
        : json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $hash = hash('sha256', (string)$source);
    $lockFile = WEBHOOK_LOCK_DIR . '/' . $hash . '.lock';

    if (file_exists($lockFile)) {
        $age = time() - (int)filemtime($lockFile);

        if ($age <= DUPLICATE_TTL_SECONDS) {
            return false;
        }
    }

    @file_put_contents($lockFile, (string)time(), LOCK_EX);

    foreach (glob(WEBHOOK_LOCK_DIR . '/*.lock') ?: [] as $file) {
        if (time() - (int)filemtime($file) > DUPLICATE_TTL_SECONDS) {
            @unlink($file);
        }
    }

    return true;
}

/*
|--------------------------------------------------------------------------
| Database Helper Tables
|--------------------------------------------------------------------------
*/

function ensureMergeTable(PDO $pdo): void
{
    /*
    |--------------------------------------------------------------------------
    | Create Merge Table
    |--------------------------------------------------------------------------
    |
    | The four appointment action URLs are staging-only fields. They are NOT
    | written to the customers table. They are held here so either GHL trigger
    | can arrive first and the final email still has all appointment links.
    |
    |--------------------------------------------------------------------------
    */

    $sql = "
        CREATE TABLE IF NOT EXISTS ghl_webhook_merge (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            active_key CHAR(64) NULL,
            contact_key VARCHAR(255) NOT NULL,
            contact_id VARCHAR(255) NULL,
            firstName VARCHAR(100) NULL,
            lastName VARCHAR(100) NULL,
            phone VARCHAR(20) NULL,
            email VARCHAR(100) NULL,
            product VARCHAR(200) NULL,
            total DECIMAL(10,2) NULL,
            transactionId VARCHAR(100) NULL,
            appointmentStart VARCHAR(255) NULL,
            reschedule_link TEXT NULL,
            cancellation_link TEXT NULL,
            google_calendar_link TEXT NULL,
            outlook_calendar_link TEXT NULL,
            payment_received TINYINT(1) NOT NULL DEFAULT 0,
            appointment_received TINYINT(1) NOT NULL DEFAULT 0,
            processed TINYINT(1) NOT NULL DEFAULT 0,
            last_payload LONGTEXT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            processed_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_active_key (active_key),
            KEY idx_contact_key (contact_key),
            KEY idx_transaction_id (transactionId),
            KEY idx_processed (processed)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
    ";

    $pdo->exec($sql);

    /*
    |--------------------------------------------------------------------------
    | Upgrade Existing Merge Table
    |--------------------------------------------------------------------------
    |
    | CREATE TABLE IF NOT EXISTS does not add new columns to a table that
    | already exists, so add the four link columns when needed.
    |
    |--------------------------------------------------------------------------
    */

    $requiredColumns = [
        'reschedule_link' =>
            'TEXT NULL AFTER appointmentStart',

        'cancellation_link' =>
            'TEXT NULL AFTER reschedule_link',

        'google_calendar_link' =>
            'TEXT NULL AFTER cancellation_link',

        'outlook_calendar_link' =>
            'TEXT NULL AFTER google_calendar_link',
    ];

    foreach ($requiredColumns as $columnName => $definition) {
        $checkStmt = $pdo->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'ghl_webhook_merge'
               AND COLUMN_NAME = ?"
        );

        $checkStmt->execute([$columnName]);

        if ((int)$checkStmt->fetchColumn() === 0) {
            $pdo->exec(
                "ALTER TABLE ghl_webhook_merge
                 ADD COLUMN {$columnName} {$definition}"
            );

            webhookLog(
                'MERGE TABLE COLUMN ADDED',
                [
                    'column' =>
                        $columnName,
                ]
            );
        }
    }
}

function ensureEmailDeliveryTable(PDO $pdo): void
{
    $sql = "
        CREATE TABLE IF NOT EXISTS ghl_email_delivery (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            transactionId VARCHAR(100) NOT NULL,
            customer_id INT NULL,
            recipient VARCHAR(100) NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            message_id VARCHAR(255) NULL,
            last_error TEXT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            sent_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_email_transaction (transactionId),
            KEY idx_email_status (status)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
    ";

    $pdo->exec($sql);
}

/*
|--------------------------------------------------------------------------
| Email Delivery Status Logger
|--------------------------------------------------------------------------
*/

function logEmailDeliveryStatus(
    PDO $pdo,
    string $transactionId,
    string $label
): void {
    try {
        $stmt = $pdo->prepare(
            "SELECT
                id,
                transactionId,
                customer_id,
                recipient,
                status,
                message_id,
                last_error,
                created_at,
                updated_at,
                sent_at
             FROM ghl_email_delivery
             WHERE transactionId = ?
             LIMIT 1"
        );

        $stmt->execute([$transactionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        webhookLog($label, [
            'found' => $row !== false,
            'delivery' => $row ?: null,
        ]);
    } catch (Throwable $e) {
        webhookLog('EMAIL DELIVERY STATUS LOGGING FAILED', [
            'transactionId' => $transactionId,
            'message' => $e->getMessage(),
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| Dynamic Service Email Content
|--------------------------------------------------------------------------
|
| The GHL "services" value controls both the subject and the client-facing
| program content. Known programs receive fully customized copy. Unknown/new
| services automatically fall back to a reusable generic confirmation layout.
|
|--------------------------------------------------------------------------
*/

function getServiceDisplayName(string $product): string
{
    $product = trim($product);

    if ($product === '') {
        return 'PHELONPRENEUR Program';
    }

    /*
    |--------------------------------------------------------------------------
    | Remove A Leading Price From Display Copy
    |--------------------------------------------------------------------------
    |
    | Examples:
    |
    | $997 - 30-Day Readiness Implementation Program
    | $3,497 - 90-Day Premium Readiness Program
    |
    |--------------------------------------------------------------------------
    */

    $displayName = preg_replace(
        '/^\s*\$\s*[\d,]+(?:\.\d{1,2})?\s*(?:-|–|—)\s*/u',
        '',
        $product
    );

    $displayName = trim(
        (string)$displayName
    );

    return $displayName !== ''
        ? $displayName
        : $product;
}

function getServiceEmailConfig(array $customer): array
{
    $rawProduct = trim(
        (string)(
            $customer['product']
            ?? ''
        )
    );

    $serviceName =
        getServiceDisplayName(
            $rawProduct
        );

    $normalized =
        strtolower(
            $serviceName
        );

    /*
    |--------------------------------------------------------------------------
    | 90-Day Premium Readiness Program
    |--------------------------------------------------------------------------
    */

    if (
        strpos(
            $normalized,
            '90-day premium readiness program'
        ) !== false
        ||
        strpos(
            $normalized,
            '90 day premium readiness program'
        ) !== false
    ) {
        return [
            'serviceName' =>
                $serviceName,

            'headline' =>
                'Your 90-Day Premium Readiness Program Is Confirmed',

            'heroText' =>
                'Your next 90 days are designed to bring structure, accountability, and consistent implementation support across the readiness priorities that matter most.',

            'introText' =>
                'Comprehensive implementation support for more complex or multi-area readiness needs, with a personalized roadmap, regular implementation sessions, and clear progress tracking throughout the full 90 days.',

            'programLabel' =>
                'Premium 90-Day Implementation Support',

            'includedHeading' =>
                'Included in Your 90-Day Program',

            'includedItems' => [
                'Comprehensive readiness review',
                'Personalized 90-day roadmap',
                'Three private 1-hour implementation calls every month',
                'Nine total 1-hour implementation calls over 90 days',
                'Client portal, resources, and implementation tools',
                'Progress tracking, accountability, and next-step planning',
            ],

            'purposeHeading' =>
                'THE PURPOSE OF YOUR NEXT 90 DAYS',

            'purposeText' =>
                'Your readiness needs may involve multiple priorities or areas that need to move together.',

            'purposeHighlight' =>
                'The next 90 days give you consistent structure, support, and accountability to move those priorities forward deliberately.',

            'sessionTitle' =>
                '90-Day Premium Readiness Implementation Session',

            'whatNextHeading' =>
                'Your 90 Days Start With a Clear Strategy.',

            'steps' => [
                [
                    'title' =>
                        'Complete Your Comprehensive Readiness Review',

                    'description' =>
                        'We start by identifying the areas that need attention, the priorities that matter most, and the sequence that makes the work more manageable.',
                ],
                [
                    'title' =>
                        'Build Your Personalized 90-Day Roadmap',

                    'description' =>
                        'Your priorities are organized into a structured 90-day implementation plan with clearer milestones, responsibilities, and next actions.',
                ],
                [
                    'title' =>
                        'Meet Three Times Every Month',

                    'description' =>
                        'Each month includes three private 1-hour implementation calls for progress review, troubleshooting, accountability, and next-step planning.',
                ],
                [
                    'title' =>
                        'Track Progress and Adjust Priorities',

                    'description' =>
                        'Your portal, resources, and progress tracking keep the work visible so priorities can be reviewed and adjusted as the 90 days move forward.',
                ],
            ],

            'closingText' =>
                'We are looking forward to helping you turn complex readiness priorities into a more structured, measurable, and supported 90-day implementation plan.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 30-Day Readiness Implementation Program
    |--------------------------------------------------------------------------
    */

    if (
        strpos(
            $normalized,
            '30-day readiness implementation program'
        ) !== false
        ||
        strpos(
            $normalized,
            '30 day readiness implementation program'
        ) !== false
    ) {
        return [
            'serviceName' =>
                $serviceName,

            'headline' =>
                'Your 30-Day Implementation Program Is Confirmed',

            'heroText' =>
                'Your next 30 days are about turning identified priorities into structured, visible progress.',

            'introText' =>
                'This next phase is designed to help you take the priorities you have already identified and move them forward with a defined roadmap, regular check-ins, progress tracking, and a clear next-step plan.',

            'programLabel' =>
                'Focused Implementation Support',

            'includedHeading' =>
                'Included in Your 30-Day Program',

            'includedItems' => [
                'Personal 30-Day Roadmap',
                'Weekly Implementation Check-Ins',
                'Priority Tracking',
                'Final Next-Step Plan',
            ],

            'purposeHeading' =>
                'THE PURPOSE OF YOUR NEXT 30 DAYS',

            'purposeText' =>
                'You have already identified what needs attention.',

            'purposeHighlight' =>
                'Now we are creating structure around actually moving it forward.',

            'sessionTitle' =>
                '30-Day Readiness Implementation Session',

            'whatNextHeading' =>
                'Your 30 Days Start With a Clear Direction.',

            'steps' => [
                [
                    'title' =>
                        'Build Your Personal Roadmap',

                    'description' =>
                        'Your immediate readiness priorities are organized into a clearer 30-day implementation path.',
                ],
                [
                    'title' =>
                        'Work the Priorities',

                    'description' =>
                        'The focus shifts from identifying what needs attention to actually progressing through those priorities.',
                ],
                [
                    'title' =>
                        'Check In and Track Progress',

                    'description' =>
                        'Weekly implementation check-ins and priority tracking help keep the work visible and moving.',
                ],
                [
                    'title' =>
                        'Finish With a Next-Step Plan',

                    'description' =>
                        'At the end of the 30-day period, you will have a clearer picture of what was completed, what remains open, and what should happen next.',
                ],
            ],

            'closingText' =>
                'We are looking forward to helping you turn the priorities you have identified into a more organized and actionable 30-day plan.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Generic / Future Service Fallback
    |--------------------------------------------------------------------------
    |
    | This lets the same webhook work with additional services without breaking
    | the confirmation email.
    |
    |--------------------------------------------------------------------------
    */

    return [
        'serviceName' =>
            $serviceName,

        'headline' =>
            'Your '
            . $serviceName
            . ' Is Confirmed',

        'heroText' =>
            'Your program is confirmed and your next steps are ready.',

        'introText' =>
            'This program is designed to provide structured implementation support, clearer priorities, progress tracking, and practical next-step planning.',

        'programLabel' =>
            'Implementation Support',

        'includedHeading' =>
            'Included in Your Program',

        'includedItems' => [
            'Personalized implementation planning',
            'Scheduled implementation support',
            'Priority and progress tracking',
            'Resources and next-step guidance',
        ],

        'purposeHeading' =>
            'THE PURPOSE OF YOUR PROGRAM',

        'purposeText' =>
            'The goal is to create clearer structure around the priorities you have identified.',

        'purposeHighlight' =>
            'Your program gives you a defined path for moving those priorities forward with greater visibility and accountability.',

        'sessionTitle' =>
            $serviceName
            . ' Session',

        'whatNextHeading' =>
            'Your Program Starts With a Clear Direction.',

        'steps' => [
            [
                'title' =>
                    'Clarify Your Priorities',

                'description' =>
                    'Identify the immediate areas that need attention and establish the order in which they should be addressed.',
            ],
            [
                'title' =>
                    'Create Your Implementation Plan',

                'description' =>
                    'Translate those priorities into a more organized plan with clear next actions.',
            ],
            [
                'title' =>
                    'Review Progress',

                'description' =>
                    'Use your implementation sessions and tracking tools to keep progress visible and address obstacles.',
            ],
            [
                'title' =>
                    'Define What Comes Next',

                'description' =>
                    'Finish with a clearer picture of completed work, remaining priorities, and the next appropriate steps.',
            ],
        ],

        'closingText' =>
            'We are looking forward to helping you move your priorities forward with greater structure, clarity, and accountability.',
    ];
}

/*
|--------------------------------------------------------------------------
| Client Email HTML
|--------------------------------------------------------------------------
*/

function buildClientEmail(array $customer): string
{
    $config =
        getServiceEmailConfig(
            $customer
        );

    $firstName =
        htmlEscape(
            $customer['firstName']
            ?? ''
        );

    $lastName =
        htmlEscape(
            $customer['lastName']
            ?? ''
        );

    $phone =
        htmlEscape(
            $customer['phone']
            ?? ''
        );

    $email =
        htmlEscape(
            $customer['email']
            ?? ''
        );

    $transactionId =
        htmlEscape(
            $customer['transactionId']
            ?? ''
        );

    $appointmentStart =
        htmlEscape(
            $customer['appointmentStart']
            ?? ''
        );

    $serviceName =
        htmlEscape(
            $config['serviceName']
            ?? 'PHELONPRENEUR Program'
        );

    $headline =
        htmlEscape(
            $config['headline']
            ?? (
                'Your '
                . (
                    $config['serviceName']
                    ?? 'PHELONPRENEUR Program'
                )
                . ' Is Confirmed'
            )
        );

    $heroText =
        htmlEscape(
            $config['heroText']
            ?? ''
        );

    $introText =
        htmlEscape(
            $config['introText']
            ?? ''
        );

    $programLabel =
        htmlEscape(
            $config['programLabel']
            ?? 'Implementation Support'
        );

    $includedHeading =
        htmlEscape(
            $config['includedHeading']
            ?? 'Included in Your Program'
        );

    $purposeHeading =
        htmlEscape(
            $config['purposeHeading']
            ?? 'THE PURPOSE OF YOUR PROGRAM'
        );

    $purposeText =
        htmlEscape(
            $config['purposeText']
            ?? ''
        );

    $purposeHighlight =
        htmlEscape(
            $config['purposeHighlight']
            ?? ''
        );

    $sessionTitle =
        htmlEscape(
            $config['sessionTitle']
            ?? (
                (
                    $config['serviceName']
                    ?? 'PHELONPRENEUR Program'
                )
                . ' Session'
            )
        );

    $whatNextHeading =
        htmlEscape(
            $config['whatNextHeading']
            ?? 'Your Program Starts With a Clear Direction.'
        );

    $closingText =
        htmlEscape(
            $config['closingText']
            ?? 'We are looking forward to helping you move your priorities forward.'
        );

    $rawRescheduleLink =
        trim(
            (string)(
                $customer['rescheduleLink']
                ?? ''
            )
        );

    $rawCancellationLink =
        trim(
            (string)(
                $customer['cancellationLink']
                ?? ''
            )
        );

    $rawGoogleCalendarLink =
        trim(
            (string)(
                $customer['googleCalendarLink']
                ?? ''
            )
        );

    $rawOutlookCalendarLink =
        trim(
            (string)(
                $customer['outlookCalendarLink']
                ?? ''
            )
        );

    $rescheduleLink =
        htmlEscape(
            $rawRescheduleLink
        );

    $cancellationLink =
        htmlEscape(
            $rawCancellationLink
        );

    $googleCalendarLink =
        htmlEscape(
            $rawGoogleCalendarLink
        );

    $outlookCalendarLink =
        htmlEscape(
            $rawOutlookCalendarLink
        );

    $total =
        '$'
        . number_format(
            (float)(
                $customer['total']
                ?? 0
            ),
            2
        );

    /*
    |--------------------------------------------------------------------------
    | Dynamic Included Items
    |--------------------------------------------------------------------------
    */

    $includedItemsHtml = '';

    foreach (
        (
            $config['includedItems']
            ?? []
        )
        as $item
    ) {
        $safeItem =
            htmlEscape(
                (string)$item
            );

        $includedItemsHtml .=
            '<p style="margin:0 0 12px;font-size:15px;line-height:23px;color:#444444;">'
            . '<span style="color:#b18a45;font-weight:700;">✓</span>'
            . '&nbsp;&nbsp;<strong>'
            . $safeItem
            . '</strong></p>';
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamic Next Steps
    |--------------------------------------------------------------------------
    */

    $stepsHtml = '';
    $stepNumber = 1;
    $steps = $config['steps'] ?? [];
    $stepCount = count($steps);

    foreach (
        $steps as $step
    ) {
        $safeTitle =
            htmlEscape(
                (string)(
                    $step['title']
                    ?? ''
                )
            );

        $safeDescription =
            htmlEscape(
                (string)(
                    $step['description']
                    ?? ''
                )
            );

        $number =
            str_pad(
                (string)$stepNumber,
                2,
                '0',
                STR_PAD_LEFT
            );

        $isLast =
            $stepNumber === $stepCount;

        $wrapperStyle =
            $isLast
                ? 'margin-bottom:30px;'
                : 'border-bottom:1px solid #eeeeee;padding-bottom:17px;margin-bottom:17px;';

        $stepsHtml .=
            '<div style="'
            . $wrapperStyle
            . '">'
            . '<div style="font-size:14px;line-height:22px;color:#171717;font-weight:700;margin-bottom:5px;">'
            . $number
            . ' &nbsp; '
            . $safeTitle
            . '</div>'
            . '<div style="font-size:14px;line-height:23px;color:#666666;">'
            . $safeDescription
            . '</div>'
            . '</div>';

        $stepNumber++;
    }

    /*
    |--------------------------------------------------------------------------
    | Optional Calendar Buttons
    |--------------------------------------------------------------------------
    */

    $calendarButtonsHtml = '';

    if (
        $rawGoogleCalendarLink !== ''
    ) {
        $calendarButtonsHtml .= <<<HTML
        <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;margin-bottom:10px;">
            <tr>
                <td align="center" style="background:#d7b56d;border-radius:8px;">
                    <a
                        href="{$googleCalendarLink}"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="display:block;padding:14px 15px;color:#171717;text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:18px;font-weight:700;"
                    >ADD TO GOOGLE CALENDAR &rarr;</a>
                </td>
            </tr>
        </table>
HTML;
    }

    if (
        $rawOutlookCalendarLink !== ''
    ) {
        $calendarButtonsHtml .= <<<HTML
        <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;margin-bottom:10px;">
            <tr>
                <td align="center" style="background:#26221d;border:1px solid #4a4135;border-radius:8px;">
                    <a
                        href="{$outlookCalendarLink}"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="display:block;padding:14px 15px;color:#ffffff;text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:18px;font-weight:700;"
                    >ADD TO APPLE / OUTLOOK CALENDAR &rarr;</a>
                </td>
            </tr>
        </table>
HTML;
    }

    $calendarSectionHtml = '';

    if (
        $calendarButtonsHtml !== ''
    ) {
        $calendarSectionHtml = <<<HTML
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#d7b56d;font-weight:700;margin-top:22px;margin-bottom:11px;">
            Add Your Session to Your Calendar
        </div>
        {$calendarButtonsHtml}
HTML;
    }

    /*
    |--------------------------------------------------------------------------
    | Optional Reschedule / Cancellation Buttons
    |--------------------------------------------------------------------------
    */

    $appointmentChangeHtml = '';

    if (
        $rawRescheduleLink !== ''
        ||
        $rawCancellationLink !== ''
    ) {
        $changeButtons = '';

        if (
            $rawRescheduleLink !== ''
            &&
            $rawCancellationLink !== ''
        ) {
            $changeButtons = <<<HTML
            <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td width="50%" style="padding-right:5px;">
                        <a
                            href="{$rescheduleLink}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display:block;padding:12px 8px;text-align:center;border:1px solid #d7b56d;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;color:#d7b56d;text-decoration:none;"
                        >RESCHEDULE</a>
                    </td>

                    <td width="50%" style="padding-left:5px;">
                        <a
                            href="{$cancellationLink}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display:block;padding:12px 8px;text-align:center;border:1px solid #4a4135;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;color:#ffffff;text-decoration:none;"
                        >CANCEL</a>
                    </td>
                </tr>
            </table>
HTML;

        } elseif (
            $rawRescheduleLink !== ''
        ) {
            $changeButtons = <<<HTML
            <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <a
                            href="{$rescheduleLink}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display:block;padding:12px 8px;text-align:center;border:1px solid #d7b56d;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;color:#d7b56d;text-decoration:none;"
                        >RESCHEDULE</a>
                    </td>
                </tr>
            </table>
HTML;

        } else {
            $changeButtons = <<<HTML
            <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <a
                            href="{$cancellationLink}"
                            target="_blank"
                            rel="noopener noreferrer"
                            style="display:block;padding:12px 8px;text-align:center;border:1px solid #4a4135;border-radius:7px;font-family:Arial,Helvetica,sans-serif;font-size:12px;font-weight:700;color:#ffffff;text-decoration:none;"
                        >CANCEL</a>
                    </td>
                </tr>
            </table>
HTML;
        }

        $appointmentChangeHtml = <<<HTML
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#d7b56d;font-weight:700;margin-top:24px;margin-bottom:11px;">
            Need to Change Your Session?
        </div>
        {$changeButtons}
HTML;
    }

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$headline}</title>
</head>
<body style="margin:0;padding:0;background:#f2f0eb;font-family:Arial,Helvetica,sans-serif;color:#1d1d1d;">
<table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#f2f0eb;margin:0;padding:30px 10px;">
<tr>
<td align="center">

<table role="presentation" width="650" border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:650px;background:#ffffff;border-collapse:separate;border-spacing:0;border-radius:16px;overflow:hidden;">

<tr>
<td style="background:#111111;padding:34px 28px 32px;text-align:center;border-radius:16px 16px 0 0;">
    <img src="https://phelonpreneurnetwork.com/images/pp.png" alt="Phelonpreneur Network" style="display:block;max-width:180px;height:auto;margin:0 auto 20px;">

    <div style="font-size:12px;letter-spacing:3px;text-transform:uppercase;color:#d7b56d;font-weight:700;margin-bottom:10px;">
        PHELONPRENEUR NETWORK
    </div>

    <div style="font-family:Georgia,'Times New Roman',serif;font-size:30px;line-height:38px;color:#ffffff;font-weight:700;margin-bottom:10px;">
        {$headline}
    </div>

    <div style="font-size:14px;line-height:23px;color:#c9c9c9;max-width:520px;margin:0 auto;">
        {$heroText}
    </div>
</td>
</tr>

<tr>
<td style="background:#ffffff;padding:36px 30px;">

    <p style="margin:0 0 16px;font-size:16px;line-height:27px;color:#222222;">
        Hi {$firstName},
    </p>

    <p style="margin:0 0 16px;font-size:16px;line-height:26px;color:#555555;">
        Thank you for choosing the <strong>{$serviceName}</strong>.
    </p>

    <p style="margin:0 0 26px;font-size:16px;line-height:26px;color:#555555;">
        {$introText}
    </p>

    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#f8f6f1;border:1px solid #e8e1d4;border-radius:12px;margin-bottom:26px;">
    <tr>
    <td style="padding:24px;">
        <div style="font-size:12px;text-transform:uppercase;letter-spacing:2px;color:#9b7a3d;font-weight:700;margin-bottom:18px;">
            Purchase Confirmation
        </div>

        <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Name</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;">{$firstName} {$lastName}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Email</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;word-break:break-all;">{$email}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Phone</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;">{$phone}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Program</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;">{$serviceName}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Amount Paid</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;">{$total}</td>
            </tr>
            <tr>
                <td style="padding:7px 0;font-size:14px;color:#777777;">Transaction ID</td>
                <td align="right" style="padding:7px 0;font-size:14px;color:#1d1d1d;font-weight:700;word-break:break-all;">{$transactionId}</td>
            </tr>
        </table>
    </td>
    </tr>
    </table>

    <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#9b7a3d;font-weight:700;margin-bottom:8px;">
        Your Program
    </div>

    <div style="font-family:Georgia,'Times New Roman',serif;font-size:25px;line-height:33px;color:#171717;font-weight:700;margin-bottom:9px;">
        {$serviceName}
    </div>

    <div style="font-size:14px;line-height:22px;color:#9a7426;font-weight:700;margin-bottom:17px;">
        {$programLabel}
    </div>

    <p style="margin:0 0 24px;font-size:15px;line-height:25px;color:#555555;">
        {$introText}
    </p>

    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#faf8f3;border:1px solid #e7dfd2;border-radius:12px;margin-bottom:28px;">
    <tr>
    <td style="padding:22px;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#9b7a3d;font-weight:700;margin-bottom:16px;">
            {$includedHeading}
        </div>

        {$includedItemsHtml}
    </td>
    </tr>
    </table>

    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#211b17;border-left:4px solid #d7b56d;border-radius:12px;margin-bottom:28px;">
    <tr>
    <td style="padding:21px;">
        <div style="font-size:10px;line-height:15px;text-transform:uppercase;letter-spacing:2px;color:#d7b56d;font-weight:700;margin-bottom:8px;">
            {$purposeHeading}
        </div>
        <div style="font-size:16px;line-height:28px;color:#ffffff;font-weight:700;">
            {$purposeText}
            <span style="color:#d7b56d;">{$purposeHighlight}</span>
        </div>
    </td>
    </tr>
    </table>

    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#171411;border:1px solid #d7b56d;border-radius:14px;margin-bottom:30px;overflow:hidden;">
        <tr>
            <td style="background:#d7b56d;padding:10px 20px;text-align:center;font-size:11px;line-height:16px;text-transform:uppercase;letter-spacing:2px;color:#171717;font-weight:700;">
                Your Implementation Session Is Scheduled
            </td>
        </tr>
        <tr>
            <td style="padding:25px 22px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#d7b56d;font-weight:700;margin-bottom:7px;">
                    Upcoming Session
                </div>

                <div style="font-family:Georgia,'Times New Roman',serif;font-size:22px;line-height:30px;color:#ffffff;font-weight:700;margin-bottom:20px;">
                    {$sessionTitle}
                </div>

                <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:8px;">
                    <tr>
                        <td style="padding:14px 0;border-top:1px solid #39332c;border-bottom:1px solid #39332c;font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#a9a096;">
                            Scheduled For
                        </td>
                        <td align="right" style="padding:14px 0;border-top:1px solid #39332c;border-bottom:1px solid #39332c;font-size:15px;line-height:23px;color:#ffffff;font-weight:700;">
                            {$appointmentStart}
                        </td>
                    </tr>
                </table>

                {$calendarSectionHtml}
                {$appointmentChangeHtml}
            </td>
        </tr>
    </table>

    <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#9b7a3d;font-weight:700;margin-bottom:8px;">
        What Happens Next
    </div>

    <div style="font-family:Georgia,'Times New Roman',serif;font-size:24px;line-height:32px;color:#171717;font-weight:700;margin-bottom:18px;">
        {$whatNextHeading}
    </div>

    {$stepsHtml}

    <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#211b17;border-radius:12px;margin-bottom:27px;">
    <tr>
    <td style="padding:20px;">
        <div style="font-size:10px;text-transform:uppercase;letter-spacing:2px;color:#d7b56d;font-weight:700;margin-bottom:7px;">
            THE PHELONPRENEUR DIFFERENCE
        </div>
        <div style="font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:28px;color:#ffffff;font-weight:700;">
            The attorney handles the legal case.
            <span style="color:#d7b56d;">PHELONPRENEUR helps stabilize the life around it.</span>
        </div>
    </td>
    </tr>
    </table>

    <p style="margin:0 0 16px;font-size:15px;line-height:25px;color:#555555;">
        Keep this email for your records. It contains your payment confirmation, appointment details, calendar links when available, and program information.
    </p>

    <p style="margin:0;font-size:15px;line-height:25px;color:#555555;">
        {$closingText}
    </p>

    <div style="margin-top:30px;padding-top:22px;border-top:1px solid #eeeeee;font-size:15px;line-height:24px;color:#444444;">
        To your progress,<br>
        <strong style="color:#171717;">The Phelonpreneur Network Team</strong>
    </div>

</td>
</tr>

<tr>
<td style="background:#111111;padding:24px 20px;text-align:center;border-radius:0 0 16px 16px;">
    <div style="font-size:12px;line-height:20px;color:#888888;">
        <strong style="color:#d7b56d;">PHELONPRENEUR NETWORK</strong><br>
        Readiness • Preparation • Implementation
    </div>

    <div style="font-size:11px;line-height:18px;color:#666666;margin-top:10px;">
        Educational and implementation support. PHELONPRENEUR does not provide legal advice or legal representation.
    </div>
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>
HTML;
}

function buildClientPlainText(array $customer): string
{
    $config =
        getServiceEmailConfig(
            $customer
        );

    $name =
        trim(
            (string)(
                $customer['firstName']
                ?? ''
            )
            . ' '
            . (string)(
                $customer['lastName']
                ?? ''
            )
        );

    $serviceName =
        (string)(
            $config['serviceName']
            ?? 'PHELONPRENEUR Program'
        );

    $headline =
        (string)(
            $config['headline']
            ?? (
                'Your '
                . $serviceName
                . ' Is Confirmed'
            )
        );

    $total =
        '$'
        . number_format(
            (float)(
                $customer['total']
                ?? 0
            ),
            2
        );

    $text =
        "PHELONPRENEUR NETWORK\n\n"
        . $headline
        . "\n\n"
        . "Hi "
        . (
            $customer['firstName']
            ?? ''
        )
        . ",\n\n"
        . "Thank you for choosing the "
        . $serviceName
        . ".\n\n"
        . (
            $config['introText']
            ?? ''
        )
        . "\n\n"
        . "Name: "
        . $name
        . "\n"
        . "Email: "
        . (
            $customer['email']
            ?? ''
        )
        . "\n"
        . "Phone: "
        . (
            $customer['phone']
            ?? ''
        )
        . "\n"
        . "Program: "
        . $serviceName
        . "\n"
        . "Amount Paid: "
        . $total
        . "\n"
        . "Transaction ID: "
        . (
            $customer['transactionId']
            ?? ''
        )
        . "\n"
        . "Scheduled For: "
        . (
            $customer['appointmentStart']
            ?? ''
        )
        . "\n";

    $googleCalendarLink =
        trim(
            (string)(
                $customer['googleCalendarLink']
                ?? ''
            )
        );

    $outlookCalendarLink =
        trim(
            (string)(
                $customer['outlookCalendarLink']
                ?? ''
            )
        );

    $rescheduleLink =
        trim(
            (string)(
                $customer['rescheduleLink']
                ?? ''
            )
        );

    $cancellationLink =
        trim(
            (string)(
                $customer['cancellationLink']
                ?? ''
            )
        );

    if (
        $googleCalendarLink !== ''
    ) {
        $text .=
            "Google Calendar: "
            . $googleCalendarLink
            . "\n";
    }

    if (
        $outlookCalendarLink !== ''
    ) {
        $text .=
            "Apple / Outlook Calendar: "
            . $outlookCalendarLink
            . "\n";
    }

    if (
        $rescheduleLink !== ''
    ) {
        $text .=
            "Reschedule: "
            . $rescheduleLink
            . "\n";
    }

    if (
        $cancellationLink !== ''
    ) {
        $text .=
            "Cancel: "
            . $cancellationLink
            . "\n";
    }

    $text .=
        "\n"
        . (
            $config['includedHeading']
            ?? 'Included in Your Program'
        )
        . ":\n";

    foreach (
        (
            $config['includedItems']
            ?? []
        )
        as $item
    ) {
        $text .=
            "- "
            . $item
            . "\n";
    }

    $text .=
        "\nWhat Happens Next:\n";

    $stepNumber = 1;

    foreach (
        (
            $config['steps']
            ?? []
        )
        as $step
    ) {
        $text .=
            $stepNumber
            . ". "
            . (
                $step['title']
                ?? ''
            )
            . "\n"
            . (
                $step['description']
                ?? ''
            )
            . "\n\n";

        $stepNumber++;
    }

    $text .=
        (
            $config['closingText']
            ?? ''
        )
        . "\n\n"
        . "To your progress,\n"
        . "The Phelonpreneur Network Team\n";

    return $text;
}

/*
|--------------------------------------------------------------------------
| Send Client Email Through PHPMailer
|--------------------------------------------------------------------------
*/

function sendClientEmail(array $customer): array
{
    /*
    |--------------------------------------------------------------------------
    | Load SMTP Values From env.php / .env
    |--------------------------------------------------------------------------
    |
    | Expected .env keys:
    |
    | SMTP_HOST
    | SMTP_USERNAME
    | SMTP_PASSWORD
    | SMTP_PORT
    |
    |--------------------------------------------------------------------------
    */

    $smtpHost = trim(
        smtpEnvValue('SMTP_HOST')
    );

    $smtpUser = trim(
        smtpEnvValue(
            'SMTP_USERNAME',
            'SMTP_USER'
        )
    );

    $smtpPass = smtpEnvValue(
        'SMTP_PASSWORD',
        'SMTP_PASS'
    );

    $smtpPort = (int)smtpEnvValue(
        'SMTP_PORT',
        null,
        '465'
    );

    if ($smtpPort <= 0) {
        $smtpPort = 465;
    }

    $smtpSecureSetting = strtolower(
        trim(
            smtpEnvValue('SMTP_SECURE')
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Auto Detect Encryption
    |--------------------------------------------------------------------------
    */

    if ($smtpSecureSetting === '') {
        if ($smtpPort === 465) {
            $smtpSecureSetting = 'ssl';
        } elseif ($smtpPort === 587) {
            $smtpSecureSetting = 'tls';
        }
    }

    $fromEmail = trim(
        smtpEnvValue(
            'SMTP_FROM_EMAIL',
            null,
            $smtpUser
        )
    );

    $fromName = trim(
        smtpEnvValue(
            'SMTP_FROM_NAME',
            null,
            'The Phelonpreneur Network'
        )
    );

    $replyTo = trim(
        smtpEnvValue('SMTP_REPLY_TO')
    );

    /*
    |--------------------------------------------------------------------------
    | Log SMTP Environment
    |--------------------------------------------------------------------------
    |
    | Password value is never logged.
    |
    |--------------------------------------------------------------------------
    */

    webhookLog(
        'SMTP CONFIGURATION LOADED',
        [
            'env_php_loaded' =>
                function_exists('envValue'),

            'dot_env_path' =>
                __DIR__ . '/.env',

            'dot_env_exists' =>
                is_file(__DIR__ . '/.env'),

            'dot_env_readable' =>
                is_readable(__DIR__ . '/.env'),

            'smtp_host' =>
                $smtpHost,

            'smtp_username' =>
                $smtpUser,

            'smtp_password_set' =>
                $smtpPass !== '',

            'smtp_port' =>
                $smtpPort,

            'smtp_secure' =>
                $smtpSecureSetting === ''
                    ? 'none'
                    : $smtpSecureSetting,

            'smtp_from_email' =>
                $fromEmail,

            'smtp_from_name' =>
                $fromName,

            'smtp_reply_to' =>
                $replyTo,

            'phpmailer_available' =>
                class_exists(PHPMailer::class),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Validate SMTP Configuration
    |--------------------------------------------------------------------------
    */

    $missingSmtp = [];

    if ($smtpHost === '') {
        $missingSmtp[] = 'SMTP_HOST';
    }

    if ($smtpUser === '') {
        $missingSmtp[] = 'SMTP_USERNAME';
    }

    if ($smtpPass === '') {
        $missingSmtp[] = 'SMTP_PASSWORD';
    }

    if ($fromEmail === '') {
        $missingSmtp[] = 'SMTP_FROM_EMAIL / SMTP_USERNAME';
    }

    if (!empty($missingSmtp)) {
        throw new RuntimeException(
            'SMTP configuration is incomplete. Missing: '
            . implode(', ', $missingSmtp)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Client Email
    |--------------------------------------------------------------------------
    */

    $recipient = trim(
        (string)(
            $customer['email']
            ?? ''
        )
    );

    if (
        $recipient === ''
        ||
        !filter_var(
            $recipient,
            FILTER_VALIDATE_EMAIL
        )
    ) {
        throw new RuntimeException(
            'Customer email address is missing or invalid: '
            . $recipient
        );
    }

    $firstName = trim(
        (string)(
            $customer['firstName']
            ?? ''
        )
    );

    $lastName = trim(
        (string)(
            $customer['lastName']
            ?? ''
        )
    );

    $transactionId = trim(
        (string)(
            $customer['transactionId']
            ?? ''
        )
    );

    $domain =
        parse_url(
            'https://phelonpreneurnetwork.com',
            PHP_URL_HOST
        )
        ?: 'phelonpreneurnetwork.com';

    $safeMessageKey =
        preg_replace(
            '/[^A-Za-z0-9_.-]/',
            '',
            $transactionId
        );

    $messageId =
        '<phelon-'
        . $safeMessageKey
        . '@'
        . $domain
        . '>';

    /*
    |--------------------------------------------------------------------------
    | Build PHPMailer
    |--------------------------------------------------------------------------
    */

    $mail = new PHPMailer(true);

    /*
    |--------------------------------------------------------------------------
    | SMTP Debug Logging
    |--------------------------------------------------------------------------
    |
    | Logs connection, EHLO, encryption, auth result, sender, recipient,
    | DATA and SMTP acceptance/rejection.
    |
    |--------------------------------------------------------------------------
    */

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->Debugoutput =
        function (
            string $debugLine,
            int $debugLevel
        ) use (
            $smtpUser,
            $smtpPass
        ): void {
            webhookLog(
                'SMTP DEBUG',
                [
                    'level' =>
                        $debugLevel,

                    'message' =>
                        sanitizeSmtpDebugLine(
                            $debugLine,
                            $smtpUser,
                            $smtpPass
                        ),
                ]
            );
        };

    /*
    |--------------------------------------------------------------------------
    | SMTP Connection
    |--------------------------------------------------------------------------
    */

    $mail->isSMTP();

    $mail->Host =
        $smtpHost;

    $mail->SMTPAuth =
        true;

    $mail->Username =
        $smtpUser;

    $mail->Password =
        $smtpPass;

    $mail->Port =
        $smtpPort;

    $mail->Timeout =
        30;

    $mail->Timelimit =
        30;

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    */

    if (
        $smtpSecureSetting === 'tls'
        ||
        $smtpSecureSetting === 'starttls'
    ) {
        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->SMTPAutoTLS =
            true;

    } elseif (
        $smtpSecureSetting === 'ssl'
        ||
        $smtpSecureSetting === 'smtps'
    ) {
        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_SMTPS;

        $mail->SMTPAutoTLS =
            false;

    } else {
        $mail->SMTPSecure =
            '';

        $mail->SMTPAutoTLS =
            false;
    }

    /*
    |--------------------------------------------------------------------------
    | Sender / Recipient
    |--------------------------------------------------------------------------
    */

    $mail->CharSet =
        'UTF-8';

    $mail->Encoding =
        'base64';

    $mail->setFrom(
        $fromEmail,
        $fromName
    );

    $mail->addAddress(
        $recipient,
        trim(
            $firstName
            . ' '
            . $lastName
        )
    );

    if (
        $replyTo !== ''
        &&
        filter_var(
            $replyTo,
            FILTER_VALIDATE_EMAIL
        )
    ) {
        $mail->addReplyTo(
            $replyTo,
            $fromName
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Message
    |--------------------------------------------------------------------------
    */

    $mail->isHTML(true);

    /*
    |--------------------------------------------------------------------------
    | Dynamic Email Subject
    |--------------------------------------------------------------------------
    |
    | Use the GHL services/product value as the subject so this webhook can be
    | reused for different programs without changing the PHP file.
    |
    | Example:
    | $997 - 30-Day Readiness Implementation Program
    | $3,497 - 90-Day Premium Readiness Program
    |
    |--------------------------------------------------------------------------
    */

    $emailSubject = trim(
        (string)(
            $customer['product']
            ?? ''
        )
    );

    if ($emailSubject === '') {
        $emailSubject =
            'PHELONPRENEUR Program Confirmation';
    }

    $mail->Subject =
        $emailSubject;

    $mail->Body =
        buildClientEmail(
            $customer
        );

    $mail->AltBody =
        buildClientPlainText(
            $customer
        );

    $mail->MessageID =
        $messageId;

    webhookLog(
        'PHPMAILER READY TO SEND',
        [
            'recipient' =>
                $recipient,

            'recipient_name' =>
                trim(
                    $firstName
                    . ' '
                    . $lastName
                ),

            'from_email' =>
                $fromEmail,

            'from_name' =>
                $fromName,

            'subject' =>
                $mail->Subject,

            'smtp_host' =>
                $smtpHost,

            'smtp_port' =>
                $smtpPort,

            'smtp_secure' =>
                $smtpSecureSetting === ''
                    ? 'none'
                    : $smtpSecureSetting,

            'message_id' =>
                $messageId,
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Send
    |--------------------------------------------------------------------------
    */

    try {
        $sendResult =
            $mail->send();

    } catch (Throwable $e) {
        webhookLog(
            'PHPMAILER THROWABLE',
            [
                'exception_class' =>
                    get_class($e),

                'exception_message' =>
                    $e->getMessage(),

                'phpmailer_error_info' =>
                    $mail->ErrorInfo,

                'recipient' =>
                    $recipient,

                'smtp_host' =>
                    $smtpHost,

                'smtp_port' =>
                    $smtpPort,

                'smtp_secure' =>
                    $smtpSecureSetting === ''
                        ? 'none'
                        : $smtpSecureSetting,
            ]
        );

        throw new RuntimeException(
            'PHPMailer send failed: '
            . (
                $mail->ErrorInfo !== ''
                    ? $mail->ErrorInfo
                    : $e->getMessage()
            ),
            0,
            $e
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SMTP Accepted
    |--------------------------------------------------------------------------
    |
    | A true result means the SMTP server accepted the message.
    | It does not guarantee inbox placement.
    |
    |--------------------------------------------------------------------------
    */

    webhookLog(
        'PHPMAILER SEND RESULT',
        [
            'send_returned' =>
                $sendResult,

            'error_info' =>
                $mail->ErrorInfo,

            'smtp_server_accepted' =>
                $sendResult === true,

            'recipient' =>
                $recipient,

            'message_id' =>
                $messageId,
        ]
    );

    if ($sendResult !== true) {
        throw new RuntimeException(
            'PHPMailer returned false. '
            . (
                $mail->ErrorInfo !== ''
                    ? $mail->ErrorInfo
                    : 'No additional PHPMailer error was provided.'
            )
        );
    }

    return [
        'message_id' =>
            $messageId,

        'recipient' =>
            $recipient,

        'smtp_server_accepted' =>
            true,

        'smtp_host' =>
            $smtpHost,

        'smtp_port' =>
            $smtpPort,

        'smtp_secure' =>
            $smtpSecureSetting === ''
                ? 'none'
                : $smtpSecureSetting,

        'error_info' =>
            $mail->ErrorInfo,
    ];
}

/*
|--------------------------------------------------------------------------
| Request Started
|--------------------------------------------------------------------------
*/

webhookLog('WEBHOOK REQUEST STARTED', [
    'request_id' => $requestId,
    'date' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'] ?? null,
    'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
    'query_string' => $_SERVER['QUERY_STRING'] ?? null,
    'remote_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    'forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? null,
    'content_length' => $_SERVER['CONTENT_LENGTH'] ?? null,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
]);

webhookLog('REQUEST HEADERS', getRequestHeadersSafe());
webhookLog('GET / QUERY PARAMETERS', $_GET);
webhookLog('PHP $_POST DATA', $_POST);

/*
|--------------------------------------------------------------------------
| Raw Input + Parse
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents('php://input');
webhookLog('RAW WEBHOOK BODY', $rawInput);

$data = postedData($rawInput);
$data = normalizeCustomData($data);

if (empty($data)) {
    respondJson(400, [
        'success' => false,
        'message' => 'No webhook data received.',
        'request_id' => $requestId,
    ]);
}

webhookLog('FULL PARSED WEBHOOK PAYLOAD', $data);

if (isset($data['customData'])) {
    webhookLog('FULL CUSTOM DATA', $data['customData']);
}

if (isset($data['payment'])) {
    webhookLog('FULL PAYMENT DATA', $data['payment']);
}

if (isset($data['appointment'])) {
    webhookLog('FULL APPOINTMENT DATA', $data['appointment']);
}

if (isset($data['calendar'])) {
    webhookLog('FULL CALENDAR DATA', $data['calendar']);
}

if (isset($data['triggerData'])) {
    webhookLog('FULL TRIGGER DATA', $data['triggerData']);
}

if (isset($data['workflow'])) {
    webhookLog('FULL WORKFLOW DATA', $data['workflow']);
}

/*
|--------------------------------------------------------------------------
| Exact Duplicate Protection
|--------------------------------------------------------------------------
*/

if (!createDuplicateLock($rawInput, $data)) {
    webhookLog(
        'EXACT DUPLICATE REQUEST DETECTED - CONTINUING',
        [
            'reason' =>
                'Allow failed SMTP delivery to retry safely. Email-once protection is handled by transactionId.',
        ]
    );
}

/*
|--------------------------------------------------------------------------
| Database / Environment
|--------------------------------------------------------------------------
*/

webhookLog('LOADING DATABASE CONFIGURATION', [
    'env_file' => __DIR__ . '/env.php',
]);

require_once __DIR__ . '/env.php';

/*
|--------------------------------------------------------------------------
| Verify SMTP Environment After env.php Load
|--------------------------------------------------------------------------
|
| Password value is NEVER logged.
|
|--------------------------------------------------------------------------
*/

webhookLog(
    'ENV / SMTP VALUES AFTER env.php LOAD',
    [
        'env_value_function_available' =>
            function_exists('envValue'),

        'dot_env_path' =>
            __DIR__ . '/.env',

        'dot_env_exists' =>
            is_file(__DIR__ . '/.env'),

        'dot_env_readable' =>
            is_readable(__DIR__ . '/.env'),

        'SMTP_HOST' =>
            smtpEnvValue('SMTP_HOST'),

        'SMTP_USERNAME' =>
            smtpEnvValue(
                'SMTP_USERNAME',
                'SMTP_USER'
            ),

        'SMTP_PASSWORD_SET' =>
            smtpEnvValue(
                'SMTP_PASSWORD',
                'SMTP_PASS'
            ) !== '',

        'SMTP_PORT' =>
            smtpEnvValue(
                'SMTP_PORT',
                null,
                '465'
            ),
    ]
);

if (!isset($pdo) || !($pdo instanceof PDO)) {
    respondJson(500, [
        'success' => false,
        'message' => 'Database connection is not available.',
        'request_id' => $requestId,
    ]);
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ensureMergeTable($pdo);
ensureEmailDeliveryTable($pdo);

$activeDatabase = $pdo->query('SELECT DATABASE()')->fetchColumn();
webhookLog('DATABASE CONNECTION AVAILABLE', [
    'database' => $activeDatabase,
]);

/*
|--------------------------------------------------------------------------
| Extract Webhook Values
|--------------------------------------------------------------------------
*/

$contactId = getPostedValue($data, [
    'contact_id',
    'contact.id',
    'payment.customer.id',
    'customer.id',
]);

$firstName = getPostedValue($data, [
    'customData.firstName',
    'contact.first_name',
    'contact.firstName',
    'first_name',
    'firstName',
]);

$lastName = getPostedValue($data, [
    'customData.lastName',
    'contact.last_name',
    'contact.lastName',
    'last_name',
    'lastName',
]);

$email = getPostedValue($data, [
    'customData.email',
    'contact.email',
    'payment.customer.email',
    'email',
]);

$phone = getPostedValue($data, [
    'customData.phone',
    'contact.phone',
    'payment.customer.phone',
    'phone',
]);

$product = getPostedValue($data, [
    'customData.services',
    'customData.service',
    'services',
    'service',
    'product',
    'appointment.title',
    'payment.calendar.name',
    'calendar.calendarName',
    'calendar.name',
]);

$totalRaw = getPostedValue($data, [
    'customData.total',
    'custom_data.total',
    'payment.total_amount',
    'payment.totalAmount',
    'total_amount',
    'total',
]);

$total = normalizeMoneyNullable($totalRaw);

$transactionId = getPostedValue($data, [
    'customData.transactionId',
    'custom_data.transactionId',
    'payment.transaction_id',
    'payment.transactionId',
    'transaction_id',
    'transactionId',
]);

/*
|--------------------------------------------------------------------------
| Appointment Start
|--------------------------------------------------------------------------
|
| GHL Custom Data:
|
| appointmentStart = {{appointment.start_time}}
|
|--------------------------------------------------------------------------
*/

$appointmentStart = getPostedValue($data, [
    'customData.appointmentStart',
    'custom_data.appointmentStart',
    'appointmentStart',
    'appointment.start_time',
    'appointment.startTime',
    'calendar.startTime',
]);

/*
|--------------------------------------------------------------------------
| Appointment Action Links
|--------------------------------------------------------------------------
|
| GHL Custom Data mapping:
|
| reschedule   = {{appointment.reschedule_link}}
| cancellation = {{appointment.cancellation_link}}
| google_cal   = {{appointment.add_to_google_calendar}}
| ical_outlook = {{appointment.add_to_ical_outlook}}
|
|--------------------------------------------------------------------------
*/

$rescheduleLink = getPostedValue($data, [
    'customData.reschedule',
    'custom_data.reschedule',
    'reschedule',
    'appointment.reschedule_link',
    'appointment.rescheduleLink',
]);

$cancellationLink = getPostedValue($data, [
    'customData.cancellation',
    'custom_data.cancellation',
    'cancellation',
    'appointment.cancellation_link',
    'appointment.cancellationLink',
]);

$googleCalendarLink = getPostedValue($data, [
    'customData.google_cal',
    'custom_data.google_cal',
    'google_cal',
    'appointment.add_to_google_calendar',
    'appointment.addToGoogleCalendar',
]);

$outlookCalendarLink = getPostedValue($data, [
    'customData.ical_outlook',
    'custom_data.ical_outlook',
    'ical_outlook',
    'appointment.add_to_ical_outlook',
    'appointment.addToIcalOutlook',
]);

$hasPaymentData = (
    $transactionId !== ''
    || $total !== null
    || isset($data['payment'])
);

$hasAppointmentData = (
    $appointmentStart !== ''
    || isset($data['appointment'])
    || isset($data['calendar'])
);

webhookLog('EXTRACTED WEBHOOK VALUES', [
    'contactId' => $contactId,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $email,
    'phone' => $phone,
    'product' => $product,
    'totalRaw' => $totalRaw,
    'total' => $total,
    'transactionId' => $transactionId,
    'appointmentStart' => $appointmentStart,
    'rescheduleLink_present' => $rescheduleLink !== '',
    'cancellationLink_present' => $cancellationLink !== '',
    'googleCalendarLink_present' => $googleCalendarLink !== '',
    'outlookCalendarLink_present' => $outlookCalendarLink !== '',
    'hasPaymentData' => $hasPaymentData,
    'hasAppointmentData' => $hasAppointmentData,
]);

/*
|--------------------------------------------------------------------------
| Common Merge Identity
|--------------------------------------------------------------------------
*/

if ($contactId !== '') {
    $contactKey = 'ghl:' . strtolower($contactId);
} elseif ($email !== '') {
    $contactKey = 'email:' . strtolower($email);
} elseif ($phone !== '') {
    $phoneKey = normalizePhoneKey($phone);
    $contactKey = $phoneKey !== '' ? 'phone:' . $phoneKey : '';
} else {
    $contactKey = '';
}

if ($contactKey === '') {
    respondJson(400, [
        'success' => false,
        'message' => 'Cannot merge webhook events because no contact identifier was received.',
        'request_id' => $requestId,
    ]);
}

/*
|--------------------------------------------------------------------------
| Resolve Existing Active Merge
|--------------------------------------------------------------------------
|
| IMPORTANT:
|
| Do NOT use product text as part of the merge identity.
|
| GHL's Payment Received trigger and Appointment trigger can provide slightly
| different product/service text. If product is included in the hash, the two
| webhook calls can be stored as two different staging rows and never meet.
|
| We now match primarily by the same contact.
|
| Priority:
|
| 1. Exact transactionId, when available.
| 2. Opposite half of the same contact's unprocessed merge:
|      appointment event -> existing payment-only row
|      payment event     -> existing appointment-only row
| 3. Any recent unprocessed row for the same contact.
| 4. New active key based only on contactKey.
|
| Product is validated AFTER the two payloads are merged.
|
|--------------------------------------------------------------------------
*/

$existingActiveKey = '';
$existingMergeId = null;
$mergeMatchBasis = 'new_contact';

try {
    /*
    |--------------------------------------------------------------------------
    | 1. Exact Transaction ID
    |--------------------------------------------------------------------------
    */

    if ($transactionId !== '') {
        $matchStmt = $pdo->prepare(
            "SELECT id, active_key
             FROM ghl_webhook_merge
             WHERE processed = 0
               AND active_key IS NOT NULL
               AND transactionId = ?
             ORDER BY id DESC
             LIMIT 1"
        );

        $matchStmt->execute([
            limitString($transactionId, 100)
        ]);

        $match = $matchStmt->fetch(PDO::FETCH_ASSOC);

        if ($match) {
            $existingMergeId = (int)$match['id'];
            $existingActiveKey = (string)$match['active_key'];
            $mergeMatchBasis = 'transaction_id';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Find The Opposite Half For Same Contact
    |--------------------------------------------------------------------------
    */

    if ($existingActiveKey === '') {

        if ($hasAppointmentData && !$hasPaymentData) {

            $matchStmt = $pdo->prepare(
                "SELECT id, active_key
                 FROM ghl_webhook_merge
                 WHERE processed = 0
                   AND active_key IS NOT NULL
                   AND contact_key = ?
                   AND payment_received = 1
                   AND appointment_received = 0
                   AND updated_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
                 ORDER BY id DESC
                 LIMIT 1"
            );

            $matchStmt->execute([
                $contactKey
            ]);

            $match = $matchStmt->fetch(PDO::FETCH_ASSOC);

            if ($match) {
                $existingMergeId = (int)$match['id'];
                $existingActiveKey = (string)$match['active_key'];
                $mergeMatchBasis = 'contact_payment_counterpart';
            }

        } elseif ($hasPaymentData && !$hasAppointmentData) {

            $matchStmt = $pdo->prepare(
                "SELECT id, active_key
                 FROM ghl_webhook_merge
                 WHERE processed = 0
                   AND active_key IS NOT NULL
                   AND contact_key = ?
                   AND appointment_received = 1
                   AND payment_received = 0
                   AND updated_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
                 ORDER BY id DESC
                 LIMIT 1"
            );

            $matchStmt->execute([
                $contactKey
            ]);

            $match = $matchStmt->fetch(PDO::FETCH_ASSOC);

            if ($match) {
                $existingMergeId = (int)$match['id'];
                $existingActiveKey = (string)$match['active_key'];
                $mergeMatchBasis = 'contact_appointment_counterpart';
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Any Recent Active Row For Same Contact
    |--------------------------------------------------------------------------
    */

    if ($existingActiveKey === '') {
        $matchStmt = $pdo->prepare(
            "SELECT id, active_key
             FROM ghl_webhook_merge
             WHERE processed = 0
               AND active_key IS NOT NULL
               AND contact_key = ?
               AND updated_at >= DATE_SUB(NOW(), INTERVAL 48 HOUR)
             ORDER BY id DESC
             LIMIT 1"
        );

        $matchStmt->execute([
            $contactKey
        ]);

        $match = $matchStmt->fetch(PDO::FETCH_ASSOC);

        if ($match) {
            $existingMergeId = (int)$match['id'];
            $existingActiveKey = (string)$match['active_key'];
            $mergeMatchBasis = 'contact_recent_active';
        }
    }

} catch (Throwable $matchError) {
    webhookLog(
        'ACTIVE MERGE LOOKUP FAILED - USING NEW CONTACT KEY',
        [
            'contact_key' =>
                $contactKey,

            'message' =>
                $matchError->getMessage(),
        ]
    );
}

/*
|--------------------------------------------------------------------------
| Stable Active Key
|--------------------------------------------------------------------------
|
| For new work, one contact has one active payment+appointment merge at a time.
| Once processed, active_key is set to NULL, so the same contact may purchase
| again later.
|
|--------------------------------------------------------------------------
*/

$activeKey =
    $existingActiveKey !== ''
        ? $existingActiveKey
        : hash(
            'sha256',
            $contactKey
        );

webhookLog(
    'MERGE MATCH RESOLVED',
    [
        'contact_key' =>
            $contactKey,

        'incoming_product' =>
            $product,

        'incoming_transactionId' =>
            $transactionId,

        'hasPaymentData' =>
            $hasPaymentData,

        'hasAppointmentData' =>
            $hasAppointmentData,

        'matched_existing_merge_id' =>
            $existingMergeId,

        'match_basis' =>
            $mergeMatchBasis,

        'active_key' =>
            $activeKey,
    ]
);

/*
|--------------------------------------------------------------------------
| Merge Both Workflow Trigger Runs
|--------------------------------------------------------------------------
*/

try {
    $pdo->beginTransaction();

    $mergeSql = "
        INSERT INTO ghl_webhook_merge (
            active_key,
            contact_key,
            contact_id,
            firstName,
            lastName,
            phone,
            email,
            product,
            total,
            transactionId,
            appointmentStart,
            reschedule_link,
            cancellation_link,
            google_calendar_link,
            outlook_calendar_link,
            payment_received,
            appointment_received,
            last_payload
        ) VALUES (
            :active_key,
            :contact_key,
            :contact_id,
            :firstName,
            :lastName,
            :phone,
            :email,
            :product,
            :total,
            :transactionId,
            :appointmentStart,
            :reschedule_link,
            :cancellation_link,
            :google_calendar_link,
            :outlook_calendar_link,
            :payment_received,
            :appointment_received,
            :last_payload
        )
        ON DUPLICATE KEY UPDATE
            contact_id = COALESCE(NULLIF(VALUES(contact_id), ''), contact_id),
            firstName = COALESCE(NULLIF(VALUES(firstName), ''), firstName),
            lastName = COALESCE(NULLIF(VALUES(lastName), ''), lastName),
            phone = COALESCE(NULLIF(VALUES(phone), ''), phone),
            email = COALESCE(NULLIF(VALUES(email), ''), email),
            product = COALESCE(NULLIF(VALUES(product), ''), product),
            total = COALESCE(VALUES(total), total),
            transactionId = COALESCE(NULLIF(VALUES(transactionId), ''), transactionId),
            appointmentStart = COALESCE(NULLIF(VALUES(appointmentStart), ''), appointmentStart),
            reschedule_link = COALESCE(NULLIF(VALUES(reschedule_link), ''), reschedule_link),
            cancellation_link = COALESCE(NULLIF(VALUES(cancellation_link), ''), cancellation_link),
            google_calendar_link = COALESCE(NULLIF(VALUES(google_calendar_link), ''), google_calendar_link),
            outlook_calendar_link = COALESCE(NULLIF(VALUES(outlook_calendar_link), ''), outlook_calendar_link),
            payment_received = GREATEST(payment_received, VALUES(payment_received)),
            appointment_received = GREATEST(appointment_received, VALUES(appointment_received)),
            last_payload = VALUES(last_payload),
            updated_at = CURRENT_TIMESTAMP
    ";

    $mergeStmt = $pdo->prepare($mergeSql);

    $mergeStmt->execute([
        ':active_key' => $activeKey,
        ':contact_key' => $contactKey,
        ':contact_id' => nullableString(limitString($contactId, 255)),
        ':firstName' => nullableString(limitString($firstName, 100)),
        ':lastName' => nullableString(limitString($lastName, 100)),
        ':phone' => nullableString(limitString($phone, 20)),
        ':email' => nullableString(limitString($email, 100)),
        ':product' => nullableString(limitString($product, 200)),
        ':total' => $total,
        ':transactionId' => nullableString(limitString($transactionId, 100)),
        ':appointmentStart' => nullableString(limitString($appointmentStart, 255)),
        ':reschedule_link' => nullableString($rescheduleLink),
        ':cancellation_link' => nullableString($cancellationLink),
        ':google_calendar_link' => nullableString($googleCalendarLink),
        ':outlook_calendar_link' => nullableString($outlookCalendarLink),
        ':payment_received' => $hasPaymentData ? 1 : 0,
        ':appointment_received' => $hasAppointmentData ? 1 : 0,
        ':last_payload' => $rawInput !== ''
            ? $rawInput
            : json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    ]);

    $selectStmt = $pdo->prepare(
        "SELECT *
         FROM ghl_webhook_merge
         WHERE active_key = ?
         LIMIT 1
         FOR UPDATE"
    );

    $selectStmt->execute([$activeKey]);
    $merged = $selectStmt->fetch(PDO::FETCH_ASSOC);

    if (!$merged) {
        throw new RuntimeException('Merged staging record could not be loaded.');
    }

    webhookLog('MERGED STAGING RECORD', $merged);

    webhookLog(
        'MERGED APPOINTMENT ACTION LINKS',
        [
            'reschedule_link_present' =>
                trim((string)($merged['reschedule_link'] ?? '')) !== '',

            'cancellation_link_present' =>
                trim((string)($merged['cancellation_link'] ?? '')) !== '',

            'google_calendar_link_present' =>
                trim((string)($merged['google_calendar_link'] ?? '')) !== '',

            'outlook_calendar_link_present' =>
                trim((string)($merged['outlook_calendar_link'] ?? '')) !== '',

            'reschedule_link' =>
                (string)($merged['reschedule_link'] ?? ''),

            'cancellation_link' =>
                (string)($merged['cancellation_link'] ?? ''),

            'google_calendar_link' =>
                (string)($merged['google_calendar_link'] ?? ''),

            'outlook_calendar_link' =>
                (string)($merged['outlook_calendar_link'] ?? ''),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Completion Gate
    |--------------------------------------------------------------------------
    */

    $missing = [];

    /*
    |--------------------------------------------------------------------------
    | Core Fields Required Before Customer Insert / Email
    |--------------------------------------------------------------------------
    |
    | Appointment action links are OPTIONAL.
    |
    | GHL can occasionally return one or more appointment action links blank.
    | A missing calendar/reschedule/cancel URL must NEVER block:
    |
    | - customers table insert/update
    | - payment + appointment merge completion
    | - confirmation email
    |
    |--------------------------------------------------------------------------
    */

    $required = [
        'firstName' => $merged['firstName'] ?? '',
        'lastName' => $merged['lastName'] ?? '',
        'email' => $merged['email'] ?? '',
        'phone' => $merged['phone'] ?? '',
        'product' => $merged['product'] ?? '',
        'transactionId' => $merged['transactionId'] ?? '',
        'appointmentStart' => $merged['appointmentStart'] ?? '',
    ];

    foreach ($required as $field => $value) {
        if ($value === null || trim((string)$value) === '') {
            $missing[] = $field;
        }
    }

    if ($merged['total'] === null || $merged['total'] === '') {
        $missing[] = 'total';
    }

    if ((int)$merged['payment_received'] !== 1) {
        $missing[] = 'payment_event';
    }

    if ((int)$merged['appointment_received'] !== 1) {
        $missing[] = 'appointment_event';
    }

    $missing = array_values(array_unique($missing));

    if (!empty($missing)) {
        $pdo->commit();

        webhookLog('WEBHOOK STORED - WAITING FOR SECOND TRIGGER', [
            'merge_id' => $merged['id'],
            'missing' => $missing,
            'match_basis' => $mergeMatchBasis,
            'contact_key' => $contactKey,
            'payment_received' => (int)$merged['payment_received'],
            'appointment_received' => (int)$merged['appointment_received'],
            'transactionId' => $merged['transactionId'] ?? null,
            'appointmentStart' => $merged['appointmentStart'] ?? null,
        ]);

        respondJson(200, [
            'success' => true,
            'ready' => false,
            'stored' => true,
            'email_sent' => false,
            'message' => 'Webhook data stored. Waiting for the other half of the payment + appointment pair.',
            'merge_id' => (int)$merged['id'],
            'merge_match_basis' => $mergeMatchBasis,
            'payment_received' => (int)$merged['payment_received'],
            'appointment_received' => (int)$merged['appointment_received'],
            'missing' => $missing,
            'request_id' => $requestId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize To Actual customers Table
    |--------------------------------------------------------------------------
    */

    $customer = [
        'firstName' => limitString((string)$merged['firstName'], 100),
        'lastName' => limitString((string)$merged['lastName'], 100),
        'phone' => limitString((string)$merged['phone'], 20),
        'email' => limitString((string)$merged['email'], 100),
        'product' => limitString((string)$merged['product'], 200),
        'total' => round((float)$merged['total'], 2),
        'transactionId' => limitString((string)$merged['transactionId'], 100),
        'appointmentStart' => limitString((string)$merged['appointmentStart'], 255),

        // Email-only appointment actions. These are NOT written to customers.
        'rescheduleLink' => (string)($merged['reschedule_link'] ?? ''),
        'cancellationLink' => (string)($merged['cancellation_link'] ?? ''),
        'googleCalendarLink' => (string)($merged['google_calendar_link'] ?? ''),
        'outlookCalendarLink' => (string)($merged['outlook_calendar_link'] ?? ''),
    ];

    /*
    |--------------------------------------------------------------------------
    | Insert / Update Customer
    |--------------------------------------------------------------------------
    |
    | These are the ONLY fields written to customers:
    |
    | firstName
    | lastName
    | phone
    | email
    | product
    | total
    | transactionId
    | appointmentStart
    |
    | id is AUTO_INCREMENT.
    | date_created uses CURRENT_TIMESTAMP.
    | access_code is untouched.
    | expiry_code is untouched.
    |
    |--------------------------------------------------------------------------
    */

    $existingCustomerStmt = $pdo->prepare(
        "SELECT id
         FROM customers
         WHERE transactionId = :transactionId
         LIMIT 1
         FOR UPDATE"
    );

    $existingCustomerStmt->execute([
        ':transactionId' => $customer['transactionId'],
    ]);

    $existingCustomerId = $existingCustomerStmt->fetchColumn();

    if ($existingCustomerId) {
        $updateCustomerStmt = $pdo->prepare(
            "UPDATE customers
             SET
                firstName = :firstName,
                lastName = :lastName,
                phone = :phone,
                email = :email,
                product = :product,
                total = :total,
                appointmentStart = :appointmentStart
             WHERE id = :id"
        );

        $updateCustomerStmt->execute([
            ':firstName' => $customer['firstName'],
            ':lastName' => $customer['lastName'],
            ':phone' => $customer['phone'],
            ':email' => $customer['email'],
            ':product' => $customer['product'],
            ':total' => $customer['total'],
            ':appointmentStart' => $customer['appointmentStart'],
            ':id' => $existingCustomerId,
        ]);

        $customerId = (string)$existingCustomerId;
        $customerAction = 'updated';
    } else {
        $insertCustomerStmt = $pdo->prepare(
            "INSERT INTO customers (
                firstName,
                lastName,
                phone,
                email,
                product,
                total,
                transactionId,
                appointmentStart
            ) VALUES (
                :firstName,
                :lastName,
                :phone,
                :email,
                :product,
                :total,
                :transactionId,
                :appointmentStart
            )"
        );

        $insertCustomerStmt->execute([
            ':firstName' => $customer['firstName'],
            ':lastName' => $customer['lastName'],
            ':phone' => $customer['phone'],
            ':email' => $customer['email'],
            ':product' => $customer['product'],
            ':total' => $customer['total'],
            ':transactionId' => $customer['transactionId'],
            ':appointmentStart' => $customer['appointmentStart'],
        ]);

        $customerId = (string)$pdo->lastInsertId();
        $customerAction = 'inserted';
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Customer Row
    |--------------------------------------------------------------------------
    */

    $verifyStmt = $pdo->prepare(
        "SELECT
            id,
            firstName,
            lastName,
            phone,
            email,
            product,
            total,
            transactionId,
            appointmentStart,
            date_created
         FROM customers
         WHERE id = ?
         LIMIT 1"
    );

    $verifyStmt->execute([$customerId]);
    $verifiedCustomer = $verifyStmt->fetch(PDO::FETCH_ASSOC);

    if (!$verifiedCustomer) {
        throw new RuntimeException('Customer record was not found after insert/update.');
    }

    webhookLog('CUSTOMER TABLE VERIFICATION', [
        'found' => true,
        'customer' => $verifiedCustomer,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Email Idempotency By Transaction ID
    |--------------------------------------------------------------------------
    |
    | The same transactionId can never create two email delivery rows.
    | If status is already "sent", PHPMailer will NOT send again.
    |
    |--------------------------------------------------------------------------
    */

    $deliveryStmt = $pdo->prepare(
        "SELECT *
         FROM ghl_email_delivery
         WHERE transactionId = ?
         LIMIT 1
         FOR UPDATE"
    );

    $deliveryStmt->execute([$customer['transactionId']]);
    $delivery = $deliveryStmt->fetch(PDO::FETCH_ASSOC);

    if ($delivery && $delivery['status'] === 'sent') {
        $closeStmt = $pdo->prepare(
            "UPDATE ghl_webhook_merge
             SET
                processed = 1,
                active_key = NULL,
                processed_at = CURRENT_TIMESTAMP
             WHERE id = ?"
        );

        $closeStmt->execute([$merged['id']]);
        $pdo->commit();

        webhookLog('CLIENT EMAIL ALREADY SENT - SKIPPING DUPLICATE', [
            'transactionId' => $customer['transactionId'],
            'recipient' => $customer['email'],
            'delivery_id' => $delivery['id'],
        ]);

        respondJson(200, [
            'success' => true,
            'ready' => true,
            'processed' => true,
            'email_sent' => true,
            'email_duplicate_prevented' => true,
            'message' => 'Customer record processed. Client email was already sent for this transaction.',
            'customer_id' => $customerId,
            'transactionId' => $customer['transactionId'],
            'request_id' => $requestId,
        ]);
    }

    if (!$delivery) {
        $insertDeliveryStmt = $pdo->prepare(
            "INSERT INTO ghl_email_delivery (
                transactionId,
                customer_id,
                recipient,
                status
             ) VALUES (
                :transactionId,
                :customer_id,
                :recipient,
                'pending'
             )"
        );

        $insertDeliveryStmt->execute([
            ':transactionId' => $customer['transactionId'],
            ':customer_id' => $customerId,
            ':recipient' => $customer['email'],
        ]);
    } else {
        $resetDeliveryStmt = $pdo->prepare(
            "UPDATE ghl_email_delivery
             SET
                customer_id = :customer_id,
                recipient = :recipient,
                status = 'pending',
                last_error = NULL
             WHERE transactionId = :transactionId"
        );

        $resetDeliveryStmt->execute([
            ':customer_id' => $customerId,
            ':recipient' => $customer['email'],
            ':transactionId' => $customer['transactionId'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Status: PENDING
    |--------------------------------------------------------------------------
    */

    logEmailDeliveryStatus(
        $pdo,
        $customer['transactionId'],
        'EMAIL DELIVERY STATUS - PENDING'
    );

    /*
    |--------------------------------------------------------------------------
    | Commit Customer + Pending Email Record Before SMTP
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

    /*
    |--------------------------------------------------------------------------
    | Send Client Email ONCE
    |--------------------------------------------------------------------------
    */

    try {
        /*
        |--------------------------------------------------------------------------
        | Email Status: SENDING
        |--------------------------------------------------------------------------
        */

        $sendingStmt = $pdo->prepare(
            "UPDATE ghl_email_delivery
             SET
                status = 'sending',
                last_error = NULL
             WHERE transactionId = :transactionId"
        );

        $sendingStmt->execute([
            ':transactionId' =>
                $customer['transactionId'],
        ]);

        logEmailDeliveryStatus(
            $pdo,
            $customer['transactionId'],
            'EMAIL DELIVERY STATUS - SENDING'
        );

        webhookLog(
            'CLIENT EMAIL SEND STARTING',
            [
                'customer_id' =>
                    $customerId,

                'recipient' =>
                    $customer['email'],

                'transactionId' =>
                    $customer['transactionId'],

                'appointment_links' => [
                    'reschedule' =>
                        $customer['rescheduleLink'] !== '',

                    'cancellation' =>
                        $customer['cancellationLink'] !== '',

                    'google_calendar' =>
                        $customer['googleCalendarLink'] !== '',

                    'outlook_calendar' =>
                        $customer['outlookCalendarLink'] !== '',
                ],
            ]
        );

        $mailResult =
            sendClientEmail(
                $customer
            );

        $pdo->beginTransaction();

        $sentStmt = $pdo->prepare(
            "UPDATE ghl_email_delivery
             SET
                status = 'sent',
                message_id = :message_id,
                last_error = NULL,
                sent_at = CURRENT_TIMESTAMP
             WHERE transactionId = :transactionId"
        );

        $sentStmt->execute([
            ':message_id' => $mailResult['message_id'],
            ':transactionId' => $customer['transactionId'],
        ]);

        $closeStmt = $pdo->prepare(
            "UPDATE ghl_webhook_merge
             SET
                processed = 1,
                active_key = NULL,
                processed_at = CURRENT_TIMESTAMP
             WHERE id = ?"
        );

        $closeStmt->execute([$merged['id']]);

        $pdo->commit();

        logEmailDeliveryStatus(
            $pdo,
            $customer['transactionId'],
            'EMAIL DELIVERY STATUS - SENT'
        );

        webhookLog(
            'CLIENT EMAIL SENT SUCCESSFULLY',
            [
                'customer_id' =>
                    $customerId,

                'recipient' =>
                    $mailResult['recipient'],

                'transactionId' =>
                    $customer['transactionId'],

                'message_id' =>
                    $mailResult['message_id'],

                'smtp_server_accepted' =>
                    $mailResult['smtp_server_accepted']
                    ?? false,

                'smtp_host' =>
                    $mailResult['smtp_host']
                    ?? null,

                'smtp_port' =>
                    $mailResult['smtp_port']
                    ?? null,

                'smtp_secure' =>
                    $mailResult['smtp_secure']
                    ?? null,

                'phpmailer_error_info' =>
                    $mailResult['error_info']
                    ?? '',
            ]
        );

        respondJson(200, [
            'success' => true,
            'ready' => true,
            'processed' => true,
            'customer_action' => $customerAction,
            'customer_id' => $customerId,
            'merge_id' => (int)$merged['id'],
            'transactionId' => $customer['transactionId'],
            'total' => $customer['total'],
            'appointmentStart' => $customer['appointmentStart'],
            'appointment_links' => [
                'reschedule' => $customer['rescheduleLink'] !== '',
                'cancellation' => $customer['cancellationLink'] !== '',
                'google_calendar' => $customer['googleCalendarLink'] !== '',
                'outlook_calendar' => $customer['outlookCalendarLink'] !== '',
            ],
            'email_sent' => true,
            'email_status' => 'sent',
            'smtp_server_accepted' => $mailResult['smtp_server_accepted'] ?? true,
            'email_recipient' => $mailResult['recipient'],
            'message' => 'Payment and appointment data merged. Customer saved and SMTP server accepted the client email.',
            'request_id' => $requestId,
        ]);

    } catch (Throwable $mailError) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        try {
            $failedStmt = $pdo->prepare(
                "UPDATE ghl_email_delivery
                 SET
                    status = 'failed',
                    last_error = :last_error
                 WHERE transactionId = :transactionId"
            );

            $failedStmt->execute([
                ':last_error' =>
                    limitString(
                        $mailError->getMessage(),
                        5000
                    ),

                ':transactionId' =>
                    $customer['transactionId'],
            ]);

            logEmailDeliveryStatus(
                $pdo,
                $customer['transactionId'],
                'EMAIL DELIVERY STATUS - FAILED'
            );

        } catch (Throwable $deliveryError) {
            webhookLog('EMAIL FAILURE STATUS COULD NOT BE SAVED', [
                'message' => $deliveryError->getMessage(),
            ]);
        }

        webhookLog(
            'CLIENT EMAIL SEND FAILED',
            [
                'customer_id' =>
                    $customerId,

                'recipient' =>
                    $customer['email'],

                'transactionId' =>
                    $customer['transactionId'],

                'exception_class' =>
                    get_class($mailError),

                'message' =>
                    $mailError->getMessage(),

                'previous_message' =>
                    $mailError->getPrevious()
                        ? $mailError->getPrevious()->getMessage()
                        : null,
            ]
        );

        respondJson(
            500,
            [
                'success' =>
                    false,

                'ready' =>
                    true,

                'customer_saved' =>
                    true,

                'email_sent' =>
                    false,

                'email_status' =>
                    'failed',

                'smtp_error' =>
                    $mailError->getMessage(),

                'message' =>
                    'Customer was saved, but the confirmation email could not be sent. Check logs/ghl-webhook.log for SMTP DEBUG entries.',

                'customer_id' =>
                    $customerId,

                'transactionId' =>
                    $customer['transactionId'],

                'request_id' =>
                    $requestId,
            ]
        );
    }

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    webhookLog('MERGE / DATABASE ERROR', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);

    respondJson(500, [
        'success' => false,
        'message' => 'Webhook data could not be merged or saved.',
        'request_id' => $requestId,
    ]);
}
