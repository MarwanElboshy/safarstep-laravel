<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
        // Ensure generated URLs respect subfolder deployments like /v2
        $appUrl = config('app.url');
        if (!empty($appUrl)) {
            URL::forceRootUrl($appUrl);
            if (Str::startsWith($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }
        // Auth endpoint rate limiting
        RateLimiter::for('auth-general', function (Request $request) {
            $key = strtolower((string) $request->input('email')) . '|' . $request->ip();
            return [
                Limit::perMinute(60)->by($request->ip()),
                Limit::perMinute(30)->by($key),
            ];
        });

        RateLimiter::for('auth-login', function (Request $request) {
            $key = strtolower((string) $request->input('email')) . '|' . $request->ip();
            return [
                Limit::perMinute(10)->by($key)->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many login attempts. Please try again later.',
                    ], 429);
                }),
            ];
        });
    }
}
