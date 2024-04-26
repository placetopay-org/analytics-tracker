<?php

namespace Placetopay\AnalyticsTracker\Facades;

use Illuminate\Support\Facades\Facade;
use Placetopay\AnalyticsTracker\Contracts\AnalyticsTracker;

/**
 * @mixin AnalyticsTracker
 */
class Analytics extends Facade
{
    public const ACCESSOR = 'analytics';

    protected static function getFacadeAccessor(): string
    {
        return self::ACCESSOR;
    }
}
