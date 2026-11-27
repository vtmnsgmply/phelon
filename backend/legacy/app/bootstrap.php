<?php
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'Phelon\\') && !str_starts_with($class, 'Phelon\\Login\\')) {
        $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, 7)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});
