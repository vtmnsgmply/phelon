<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'Phelon\\Login\\')) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen('Phelon\\Login\\'))) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});
