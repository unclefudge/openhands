<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('enquiries', function (Request $request): array {
            $email = Str::lower((string) $request->input('email', 'unknown'));
            $ip = $request->hasHeader('CF-Ray')
                ? (string) $request->header('CF-Connecting-IP', $request->ip())
                : (string) $request->ip();

            return [
                Limit::perMinute(3)->by($ip),
                Limit::perDay(10)->by($ip.'|'.$email),
            ];
        });
    }
}
