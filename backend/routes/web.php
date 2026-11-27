<?php

use App\Services\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/{retired}', fn () => abort(410))->where('retired', '(?:aa_discovery_call|aa_payment_recieved|webhook|webhook_services|webhook_services_calendar)(?:\.php)?');

$aliases = [
    'index' => '/', 'about' => '/about', 'starthere' => '/start-here', 'start-here' => '/start-here',
    'services' => '/services', 'service' => '/services', 'book' => '/book', 'contact' => '/contact',
    'privacy-policy' => '/privacy-policy', 'cookie-policy' => '/cookie-policy',
    'terms-of-service' => '/terms-of-service', 'disclaimer' => '/disclaimer', 'intake' => '/intake',
    'thanks' => '/thanks', 'thankyou' => '/thank-you', 'webinar_index' => '/webinar', 'login' => '/login/',
];
Route::get('/{file}.php', function (Request $request, string $file) use ($aliases) {
    $slug = strtolower($file);
    abort_unless(isset($aliases[$slug]), 404);
    $target = $aliases[$slug];
    $service = $request->query('service');
    if ($slug === 'service' && is_string($service) && preg_match('/\A[a-z0-9-]+\z/', $service)) {
        $target .= '/'.$service;
    }
    return redirect($target.($request->getQueryString() ? '?'.$request->getQueryString() : ''), 301);
});
Route::get('/webinar/{file}.php', function (Request $request, string $file) {
    $lookup = [
        'index' => '/webinar', 'thankyou' => '/webinar/resources', 'purchasedsuccessfully' => '/webinar/resources',
        'payment-47' => '/webinar/checkout/47', 'payment-497' => '/webinar/checkout/497',
        'payment-3497' => '/webinar/checkout/3497', 'payment-997' => '/book?service=private-readiness-life-planning',
        'accesspdf' => '/webinar/resources', 'viewpdf' => '/webinar/resources',
    ];
    $target = $lookup[strtolower($file)] ?? null;
    abort_unless($target, 404);
    return redirect($target.($request->getQueryString() && !str_contains($target, '?') ? '?'.$request->getQueryString() : ''), 301);
});

Route::get('/Login', fn () => redirect('/login/', 301));
Route::get('/Login/', fn () => redirect('/login/', 301));
Route::get('/login/{path?}', function () {
    $file = public_path('staff/index.html');
    abort_unless(is_file($file), 503, 'Build the staff React application first.');
    return response()->file($file, ['Cache-Control' => 'no-cache', 'X-Robots-Tag' => 'noindex']);
})->where('path', '.*')->name('login');

Route::get('/{path?}', function (Request $request, ?string $path = null) {
    if ($path !== null && $path !== strtolower($path) && !str_contains($path, '/')) {
        return redirect('/'.strtolower($path).($request->getQueryString() ? '?'.$request->getQueryString() : ''), 301);
    }
    if ($path === 'index') {
        return redirect('/', 301);
    }
    if ($path === 'starthere') {
        return redirect('/start-here', 301);
    }
    $file = public_path('site/index.html');
    abort_unless(is_file($file), 503, 'Build the public React website first.');
    $simple = ['home','services','book','contact','about','start-here','privacy-policy','cookie-policy',
        'terms-of-service','disclaimer','intake','thanks','thank-you','webinar','webinar/resources'];
    $catalog = json_decode(file_get_contents(resource_path('catalog/services.json')), true, flags: JSON_THROW_ON_ERROR);
    $service = $path !== null && str_starts_with($path, 'services/') ? ($catalog[substr($path, 9)] ?? null) : null;
    $known = $path === null
        || in_array(trim($path, '/'), $simple, true)
        || $service
        || preg_match('~\Awebinar/checkout/(?:47|497|3497)\z~', $path);
    if ($path === 'book') {
        $key = $request->query('service');
        $subtype = $request->query('subservice');
        if (($key !== null && !is_string($key)) || ($subtype !== null && !is_string($subtype))
            || ($subtype !== null && !$key)
            || app(ServiceCatalog::class)->resolve($key === null ? null : $key.($subtype ? '/'.$subtype : '')) === null) {
            return response()->file($file, ['Cache-Control' => 'no-cache'])->setStatusCode(400);
        }
    }
    if ($service) {
        $title = e($service['title']).' | The PHELONPRENEUR Network';
        $description = e($service['description']);
        $canonical = e(rtrim(config('app.url'), '/').'/'.$path);
        $html = file_get_contents($file);
        $html = preg_replace_callback('~<title>[^<]*</title>~', static fn () => '<title>'.$title.'</title>', $html, 1);
        $html = preg_replace_callback('~<meta name="description" content="[^"]*">~',
            static fn () => '<meta name="description" content="'.$description.'"><link rel="canonical" href="'.$canonical.'">', $html, 1);
        return response($html)->header('Content-Type', 'text/html; charset=UTF-8')->header('Cache-Control', 'no-cache');
    }
    return response()->file($file, ['Cache-Control' => 'no-cache'])->setStatusCode($known ? 200 : 404);
})->where('path', '(?!api(?:/|$)|site(?:/|$)|staff(?:/|$)|.*\.).*');
