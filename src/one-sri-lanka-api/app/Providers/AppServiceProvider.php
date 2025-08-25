<?php

namespace App\Providers;

use App\Services\ApiResponseService;
use App\Services\ComplaintService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('complaintservice', function ($app) {
            return new ComplaintService();
        });
        $this->app->singleton('apiresponse', function ($app) {
            return new ApiResponseService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
