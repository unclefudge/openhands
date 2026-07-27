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

            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(10)->by($request->ip().'|'.$email),
            ];
        });
    }
}
