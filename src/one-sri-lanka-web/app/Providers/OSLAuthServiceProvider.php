<?php

namespace App\Providers;

use App\Services\OSLAuthService;
use Illuminate\Support\ServiceProvider;

class OSLAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->singleton(OSLAuthService::class, function ($app) {
        //     return new OSLAuthService();
        // });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
