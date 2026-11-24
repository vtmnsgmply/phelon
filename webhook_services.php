<?php

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', '0');
ini_set('log_errors', '1');

error_reporting(E_ALL);


/*
|--------------------------------------------------------------------------
| JSON Response
|--------------------------------------------------------------------------
*/

function respondJson(int $statusCode, array $payload): void
{
    http_response_code($statusCode);

    echo json_encode(
        $payload,
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Catch Fatal / Unhandled Errors
|--------------------------------------------------------------------------
*/

set_exception_handler(function (Throwable $e) {

    error_log(
        'WEBHOOK UNCAUGHT ERROR: ' .
        $e->getMessage() .
        ' | File: ' .
        $e->getFile() .
        ' | Line: ' .
        $e->getLine()
    );

    respondJson(500, [
        'success' => false,
        'message' => 'Internal webhook error.',
    ]);
});


/*
|--------------------------------------------------------------------------
| Read Request
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents('php://input');

error_log(
    'GHL WEBHOOK RECEIVED: ' .
    $rawInput
);


/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/env.php';


if (!isset($pdo) || !($pdo instanceof PDO)) {

    respondJson(500, [
        'success' => false,
        'message' => 'Database connection is not available.',
    ]);
}


/*
|--------------------------------------------------------------------------
| Parse Payload
|--------------------------------------------------------------------------
*/

function postedData(string $rawInput): array
{
    if ($rawInput !== '') {

        $data = json_decode(
            $rawInput,
            true
        );

        if (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($data)
        ) {
            return $data;
        }
    }

    if (!empty($_POST)) {
        return $_POST;
    }

    return [];
}


function getPostedValue(
    array $data,
    array $keys
): string {

    foreach ($keys as $key) {

        $parts = explode('.', $key);

        $value = $data;

        foreach ($parts as $part) {

            if (
                !is_array($value) ||
                !array_key_exists($part, $value)
            ) {
                continue 2;
            }

            $value = $value[$part];
        }

        if (
            $value !== null &&
            $value !== ''
        ) {
            return trim((string)$value);
        }
    }

    return '';
}


function normalizeMoney(string $value): float
{
    $normalized = preg_replace(
        '/[^0-9.\-]/',
        '',
        $value
    );

    if (
        $normalized === '' ||
        !is_numeric($normalized)
    ) {
        return 0.00;
    }

    return round(
        (float)$normalized,
        2
    );
}


$data = postedData($rawInput);


if (empty($data)) {

    respondJson(400, [
        'success' => false,
        'message' => 'No webhook data received.',
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? null,
    ]);
}

/*
|--------------------------------------------------------------------------
| Extract Data
|--------------------------------------------------------------------------
*/
$firstName = getPostedValue(
    $data,
    [
        'first_name',
        'customData.firstName',
        'contact.first_name',
        'firstName'
    ]
);

$lastName = getPostedValue(
    $data,
    [
        'last_name',
        'customData.lastName',
        'contact.last_name',
        'lastName'
    ]
);

$email = getPostedValue(
    $data,
    [
        'email',
        'customData.email',
        'contact.email'
    ]
);

$phone = getPostedValue(
    $data,
    [
        'phone',
        'customData.phone',
        'contact.phone'
    ]
);

function getCustomDataValue(array $data, string $key): string
{
    if (!isset($data['customData'])) {
        return '';
    }

    $customData = $data['customData'];

    /*
    |--------------------------------------------------------------------------
    | If customData is JSON encoded as a string
    |--------------------------------------------------------------------------
    */

    if (is_string($customData)) {

        $decoded = json_decode($customData, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $customData = $decoded;
        } else {
            return '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Standard associative format
    |
    | customData: {
    |    "services": "Starter"
    | }
    |--------------------------------------------------------------------------
    */

    if (
        is_array($customData) &&
        isset($customData[$key]) &&
        $customData[$key] !== ''
    ) {
        return trim((string)$customData[$key]);
    }


    /*
    |--------------------------------------------------------------------------
    | Array key/value format
    |
    | customData: [
    |    {
    |       "key": "services",
    |       "value": "Starter"
    |    }
    | ]
    |--------------------------------------------------------------------------
    */

    if (is_array($customData)) {

        foreach ($customData as $item) {

            if (!is_array($item)) {
                continue;
            }

            if (
                isset($item['key']) &&
                $item['key'] === $key &&
                isset($item['value'])
            ) {
                return trim((string)$item['value']);
            }
        }
    }

    return '';
}


$product = getCustomDataValue($data, 'services');

if ($product === '') {

    $product = getCustomDataValue(
        $data,
        'service'
    );
}

if ($product === '') {

    $product = getPostedValue(
        $data,
        [
            'services',
            'service',
            'product'
        ]
    );
}

$totalRaw = getPostedValue(
    $data,
    [
        'customData.total',
        'custom_data.total',
        'payment.total_amount',
        'payment.totalAmount',
        'total_amount',
        'total'
    ]
);

$transactionId = getPostedValue(
    $data,
    [
        'customData.transactionId',
        'custom_data.transactionId',
        'payment.transaction_id',
        'payment.transactionId',
        'transaction_id',
        'transactionId'
    ]
);


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

$missing = [];


$requiredFields = [

    'firstName' => $firstName,

    'lastName' => $lastName,

    'phone' => $phone,

    'product' => $product,

    'total' => $totalRaw,

    'transactionId' => $transactionId,
];


foreach ($requiredFields as $field => $value) {

    if ($value === '') {
        $missing[] = $field;
    }
}


if (!empty($missing)) {

    error_log(
        'GHL WEBHOOK MISSING FIELDS: ' .
        json_encode($missing)
    );

    respondJson(400, [
        'success' => false,
        'message' => 'Required webhook fields are missing.',
        'missing' => $missing,
        'received_keys' => array_keys($data),

        // TEMP DEBUG
        'customData' => $data['customData'] ?? null,
    ]);
}


$total = normalizeMoney(
    $totalRaw
);


/*
|--------------------------------------------------------------------------
| Insert Customer
|--------------------------------------------------------------------------
*/

$sql = "

    INSERT INTO customers (

        firstName,
        lastName,
        phone,
        email,
        product,
        total,
        transactionId

    ) VALUES (

        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?

    )

";


try {

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        $firstName,

        $lastName,

        $phone,

        $email,

        $product,

        $total,

        $transactionId,

    ]);


    $customerId = $pdo->lastInsertId();


} catch (PDOException $e) {

    error_log(
        'CUSTOMER INSERT ERROR: ' .
        $e->getMessage()
    );


    respondJson(500, [

        'success' => false,

        'message' =>
            'Customer record could not be saved.',

    ]);
}


/*
|--------------------------------------------------------------------------
| Success
|--------------------------------------------------------------------------
*/

respondJson(200, [

    'success' => true,

    'message' =>
        'Customer record saved successfully.',

    'customer_id' =>
        $customerId,

    'transactionId' =>
        $transactionId,

]);