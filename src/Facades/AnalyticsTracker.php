<?php

namespace Placetopay\AnalyticsTracker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Placetopay\AnalyticsTracker\Contracts\AnalyticsTracker
 */
class AnalyticsTracker extends Facade
{
    public const ACCESSOR = 'AnalyticsTracker';

    protected static function getFacadeAccessor(): string
    {
        return self::ACCESSOR;
    }
}
