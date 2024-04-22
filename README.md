# Placetopay analytics-tracker

Package to track events on your application 

## Prerequisites

- `php8.2+`
- `Laravel 9+`

## Installation

This package can be installed via composer:

``` bash
composer require "placetopay/analytics-tracker"
```

## Usage

Add the following variables to your .env:

- MIXPANEL_ENABLED=true
- MIXPANEL_PROJECT_TOKEN={{token}}

```php
use Placetopay\AnalyticsTracker\Contracts\Tracker;
use Placetopay\AnalyticsTracker\Enums\TrackerLabelsEnum;

app(Tracker::class)
->identify("user@company.com") // Associate a user to the tracked events
->setDefaultPayload(['key' => 'value']) // Set the default data to be sent on every event track
->track(TrackerLabelsEnum::TRANSACTION_MADE, ['key' => 'value']); // Tracks an event
```