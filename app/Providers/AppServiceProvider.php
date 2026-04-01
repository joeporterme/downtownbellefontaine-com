<?php

namespace App\Providers;

use App\Services\AI\AIService;
use App\Services\BusinessImportService;
use App\Services\Google\GeocodingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('ai', function ($app) {
            return new AIService();
        });

        $this->app->singleton(AIService::class, function ($app) {
            return new AIService();
        });

        $this->app->singleton(GeocodingService::class, function ($app) {
            return new GeocodingService();
        });

        $this->app->singleton(BusinessImportService::class, function ($app) {
            return new BusinessImportService(
                $app->make(AIService::class),
                $app->make(GeocodingService::class)
            );
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
