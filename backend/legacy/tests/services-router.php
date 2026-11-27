<?php

// Loopback-only test router. Never route requests to data, webhooks, or form handlers.
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}

$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('~\A/Phelon(?=/)~', '', $path);
$nativeApi = ['/api/bookings.php', '/Login/backend/api/session.php'];
if (in_array($path, $nativeApi, true)) {
    if (getenv('PHELON_TEST_NATIVE_API') === '1') {
        require $root . $path;
    } elseif ($path === '/api/bookings.php' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');
        echo '{"minDate":"2030-06-03","maxDate":"2030-07-18","timezone":"America/New_York","durationMinutes":15,"csrfToken":"fixture","slots":["2030-06-03T14:00:00Z"]}';
    } elseif ($path === '/Login/backend/api/session.php' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');
        echo '{"user":null,"csrfToken":"fixture"}';
    } else {
        http_response_code(404);
    }
    return;
}
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(404);
    exit;
}
if (preg_match('~\A/(?:aa_discovery_call|aa_payment_recieved|webhook|webhook_services|webhook_services_calendar)(?:\.php)?\z~', $path)) {
    http_response_code(410);
    return;
}
$route = $path === '/' ? '/index.php' : $path;
if ($route === '/Login/') {
    $route = '/Login/index.php';
}
$pages = [
    '/index.php', '/Services.php', '/Service.php', '/Book.php', '/Contact.php', '/Intake.php',
    '/webinar/index.php', '/webinar/Payment-497.php', '/webinar/Payment-997.php', '/Login/index.php',
];
if (!str_ends_with($route, '.php')) {
    $route .= '.php';
}
if (in_array($route, $pages, true)) {
    require $root . $route;
    return;
}

if (preg_match('~\A/(?:css|js|images|Login/frontend)/[a-z0-9_.-]+\.(css|js|ico|png|jpg|webp|svg)\z~i', $path, $asset)
    && is_file($root . $path)) {
    $types = [
        'css' => 'text/css', 'js' => 'application/javascript', 'ico' => 'image/x-icon',
        'png' => 'image/png', 'jpg' => 'image/jpeg', 'webp' => 'image/webp', 'svg' => 'image/svg+xml',
    ];
    header('Content-Type: ' . $types[strtolower($asset[1])]);
    readfile($root . $path);
    return;
}

http_response_code(404);
