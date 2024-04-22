<?php

namespace Placetopay\AnalyticsTracker\Tests;

use Illuminate\Support\Facades\Http;
use Placetopay\AnalyticsTracker\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
