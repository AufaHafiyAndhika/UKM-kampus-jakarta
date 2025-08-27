<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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
                if (
            env('APP_ENV') === 'production' ||
            (str_contains(env('APP_URL'), 'ngrok-free.app') && !str_contains(env('APP_URL'), '127.0.0.1'))
        ) {
            URL::forceScheme('https');
        }
        // Use custom pagination view
        Paginator::defaultView('custom.pagination');
        Paginator::defaultSimpleView('custom.pagination');
    }
}
