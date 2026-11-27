<?php
declare(strict_types=1);

namespace Phelon\Core;

use PDO;
use RuntimeException;

final class Database
{
    public static function connect(): PDO
    {
        $host = Config::get('DB_HOST', '127.0.0.1');
        $port = Config::get('DB_PORT', '3306');
        $name = Config::get('DB_NAME', Config::get('DB_DATABASE'));
        $user = Config::get('DB_USERNAME', Config::get('DB_USER'));
        if (!preg_match('/\A[a-zA-Z0-9.:-]+\z/', $host) || !preg_match('/\A[0-9]{1,5}\z/', $port)
            || (int) $port < 1 || (int) $port > 65535 || !preg_match('/\A[a-zA-Z0-9_-]+\z/', $name) || $user === '') {
            throw new RuntimeException('Incomplete database configuration.');
        }
        return new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
            $user, Config::get('DB_PASSWORD', Config::get('DB_PASS')), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 3,
            ]);
    }
}
