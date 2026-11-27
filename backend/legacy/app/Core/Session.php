<?php
declare(strict_types=1);

namespace Phelon\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        $savePath = Config::get('PHELON_SESSION_PATH');
        if ($savePath !== '') {
            session_save_path($savePath);
        }
        session_name('PHELONSESSID');
        session_set_cookie_params([
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => Config::get('PHELON_SECURE_COOKIES') === '1'
                || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'),
        ]);
        if (!session_start()) {
            throw new \RuntimeException('Session unavailable.');
        }
    }

    public static function token(): string
    {
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    public static function verify(): void
    {
        $provided = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($provided) || !hash_equals(self::token(), $provided)) {
            throw new HttpError(403, 'Reload the page and try again.');
        }
    }

    public static function rotate(): void
    {
        if (!session_regenerate_id(true)) {
            throw new \RuntimeException('Session rotation failed.');
        }
        unset($_SESSION['csrf']);
    }
}
