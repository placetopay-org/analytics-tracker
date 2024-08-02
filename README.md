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
use Placetopay\AnalyticsTracker\Facades\Analytics;

Analytics::setIdentifier("user@company.com") // (optional) Associate a user to the tracked events
    ->setDefaultPayload(['key' => 'value']) // Set the default data to be sent on every track call
    ->track('Label', ['key' => 'value']); // Tracks an event
```

## Conditional tracking

You may call the `shouldTrackEvents` method to define a condition for when to track events. The method receives a callback that should return a boolean value.

```php
use Placetopay\AnalyticsTracker\Facades\Analytics;

Analytics::setIdentifier("user@company.com")
    ->shouldTrackEvents(fn($label, $payload) => $payload['siteId'] === '1234')
    ->track('Label', ['siteId' => '5678']);


Analytics::setIdentifier("user@company.com")
    ->shouldTrackEvents(new InvokableClass()) // May use an Invokable class 
    ->track('Label', ['siteId' => '5678']);

```