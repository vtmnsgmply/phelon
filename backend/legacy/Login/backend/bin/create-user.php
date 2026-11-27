<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/bootstrap.php';

$email = strtolower(trim($argv[1] ?? ''));
$name = trim($argv[2] ?? '');
$password = rtrim((string) stream_get_contents(STDIN, 74), "\r\n");
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || $name === '' || strlen($name) > 100
    || strlen($password) < 12 || strlen($password) > 72 || str_contains($password, "\0")) {
    fwrite(STDERR, "Provide a valid email and display name as arguments and a 12–72 byte password through stdin.\n");
    exit(1);
}
try {
    $pdo = \Phelon\Core\Database::connect();
    (new \Phelon\Login\Repositories\StaffRepository($pdo))
        ->create($email, $name, password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));
    echo "Staff account created.\n";
} catch (Throwable) {
    fwrite(STDERR, "Account was not created. Check database access, schema, and email uniqueness.\n");
    exit(1);
}
