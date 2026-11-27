<?php

namespace App\Http\Middleware;

use App\Services\MigrationAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequireMigrationAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(MigrationAccess::allows($request->user()), 403, 'You do not have access to database migrations.');

        return $next($request);
    }
}
