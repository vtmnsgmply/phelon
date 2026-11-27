<?php
declare(strict_types=1);
require dirname(__DIR__) . '/bootstrap.php';

\Phelon\Core\Api::run([new \Phelon\Login\Controllers\SessionController(), 'handle']);
