<?php

if (!function_exists('loadEnvFile')) {
    function loadEnvFile(string $path): void
    {
        if (!is_file($path)) {
            error_log("ENV ERROR: .env file not found at: " . $path);
            return;
        }

        if (!is_readable($path)) {
            error_log("ENV ERROR: .env file is not readable: " . $path);
            return;
        }

        $lines = file(
            $path,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach ($lines as $line) {

            $line = trim($line);

            /*
             * Avoid str_starts_with() so this still works
             * if the server is running PHP 7.x.
             */
            if ($line === '' || substr($line, 0, 1) === '#') {
                continue;
            }

            $parts = explode('=', $line, 2);

            $key = trim($parts[0] ?? '');
            $value = trim($parts[1] ?? '');

            if ($key === '') {
                continue;
            }

            if (
                strlen($value) >= 2 &&
                (
                    ($value[0] === '"' && substr($value, -1) === '"') ||
                    ($value[0] === "'" && substr($value, -1) === "'")
                )
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;

            putenv($key . '=' . $value);
        }
    }
}

if (!function_exists('envValue')) {
    function envValue(
        string $key,
        ?string $fallbackKey = null,
        string $default = ''
    ): string {

        $value = getenv($key);

        if ($value !== false && $value !== '') {
            return $value;
        }

        if ($fallbackKey !== null) {

            $fallback = getenv($fallbackKey);

            if ($fallback !== false && $fallback !== '') {
                return $fallback;
            }
        }

        return $default;
    }
}


loadEnvFile(__DIR__ . '/.env');


$dbHost = envValue('DB_HOST', null, 'localhost');
$dbName = envValue('DB_NAME', 'DB_DATABASE');
$dbUser = envValue('DB_USERNAME', 'DB_USER');
$dbPass = envValue('DB_PASSWORD', 'DB_PASS');
$dbCharset = envValue('DB_CHARSET', null, 'utf8mb4');


/*
|--------------------------------------------------------------------------
| Validate configuration
|--------------------------------------------------------------------------
*/

$pdo = null;

if ($dbName === '') {
    error_log('DATABASE ERROR: DB_NAME is empty.');
}

if ($dbUser === '') {
    error_log('DATABASE ERROR: DB_USERNAME is empty.');
}


/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
*/

if ($dbName !== '' && $dbUser !== '') {

    try {

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $dbHost,
            $dbName,
            $dbCharset
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO(
            $dsn,
            $dbUser,
            $dbPass,
            $options
        );

    } catch (Throwable $e) {

        error_log(
            'DATABASE CONNECTION ERROR: ' .
            $e->getMessage()
        );

        $pdo = null;
    }
}