<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/app/bootstrap.php';
try {
    $pdo = \Phelon\Core\Database::connect();
    $rows = $pdo->query('SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, COLUMN_KEY
        FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE()
        ORDER BY TABLE_NAME, ORDINAL_POSITION')->fetchAll();
    foreach ($rows as $row) {
        echo implode("\t", array_values($row)), PHP_EOL;
    }
    if (!$rows) {
        echo "Connected, but no table columns are visible to this account.\n";
    }
} catch (PDOException $error) {
    fwrite(STDERR, 'Database connection/schema check failed. SQLSTATE: ' . ($error->errorInfo[0] ?? $error->getCode())
        . ', driver code: ' . ($error->errorInfo[1] ?? 'unavailable') . PHP_EOL);
    exit(1);
} catch (Throwable) {
    fwrite(STDERR, "Database configuration or schema check failed.\n");
    exit(1);
}
