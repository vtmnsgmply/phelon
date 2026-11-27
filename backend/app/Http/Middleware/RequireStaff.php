<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class RequireStaff
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->active && time() - (int) $request->session()->get('staff_signed_at') <= 28800, 401);
        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store');
        return $response;
    }
}
