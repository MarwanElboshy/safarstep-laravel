<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
    }
}
