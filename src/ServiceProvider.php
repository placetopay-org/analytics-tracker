<?php

namespace Placetopay\AnalyticsTracker;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Placetopay\AnalyticsTracker\Providers\TrackerServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/analytics-tracker.php' => config_path('analytics-tracker.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/analytics-tracker.php', 'analytics-tracker');
        $this->app->register(TrackerServiceProvider::class);
    }
}
