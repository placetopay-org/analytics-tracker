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

## Supported analytics trackers

- [Mixpanel](https://mixpanel.com/)

## Usage

Add the following variables to your .env:

- MIXPANEL_ENABLED=true
- MIXPANEL_PROJECT_TOKEN={{token}}

```php
use Placetopay\AnalyticsTracker\Contracts\Tracker;

app(Tracker::class)
->setIdentifier("user@company.com") // (optional) Associate a user to the tracked events
->setDefaultPayload(['key' => 'value']) // Set the default data to be sent on every track call
->track('Label', ['key' => 'value']); // Tracks an event
```