<?php

namespace Placetopay\AnalyticsTracker\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Placetopay\AnalyticsTracker\Facades\AnalyticsTracker;
use Placetopay\AnalyticsTracker\Trackers\MixpanelTracker;

class TrackerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(AnalyticsTracker::ACCESSOR, MixpanelTracker::class);
    }

    public function provides(): array
    {
        return [AnalyticsTracker::ACCESSOR::ACCESOR];
    }
}
