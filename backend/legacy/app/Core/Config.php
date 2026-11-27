<?php
declare(strict_types=1);

namespace Phelon\Core;

final class Config
{
    private static ?array $file = null;

    public static function get(string $key, string $default = ''): string
    {
        $override = getenv($key);
        if ($override !== false) {
            return $override;
        }
        if (self::$file === null) {
            $path = dirname(__DIR__, 2) . '/.env';
            self::$file = [];
            foreach (is_readable($path) ? file($path, FILE_IGNORE_NEW_LINES) ?: [] : [] as $line) {
                if (preg_match('/^\s*([A-Z][A-Z0-9_]*)\s*=\s*(.*?)\s*$/D', $line, $matches)) {
                    $value = $matches[2];
                    if (strlen($value) >= 2 && in_array($value[0], ['"', "'"], true)
                        && $value[0] === substr($value, -1)) {
                        $value = substr($value, 1, -1);
                    }
                    self::$file[$matches[1]] = $value;
                }
            }
        }
        return self::$file[$key] ?? $default;
    }
}
