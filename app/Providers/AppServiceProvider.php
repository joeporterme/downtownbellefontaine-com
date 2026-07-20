<?php

namespace App\Providers;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\SiteSetting;
use App\Observers\BusinessLocationObserver;
use App\Observers\BusinessObserver;
use App\Services\AI\AIService;
use App\Services\BusinessImportService;
use App\Services\Google\GeocodingService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        // Regenerate the cached Street View snapshot when a location's camera changes,
        // and download a picked Google photo into a location's / business's images.
        BusinessLocation::observe(BusinessLocationObserver::class);
        Business::observe(BusinessObserver::class);

        // Branded pagination bar sitewide.
        Paginator::defaultView('vendor.pagination.downtown');

        // Make global site settings available to every view.
        try {
            if (Schema::hasTable('site_settings')) {
                View::share('siteSettings', SiteSetting::current());
            }
        } catch (\Throwable $e) {
            // Settings table not migrated yet (e.g. during install) — ignore.
        }
    }
}
