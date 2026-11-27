<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('staff-login', static function (Request $request): array {
            $email = is_string($request->input('email')) ? $request->input('email') : '';
            return [
                Limit::perMinute(24)->by('staff:ip:'.$request->ip()),
                Limit::perMinute(6)->by('staff:user:'.hash('sha256', strtolower($email).'|'.$request->ip())),
            ];
        });
    }
}
