<?php

namespace Placetopay\AnalyticsTracker\Contracts;

use Placetopay\AnalyticsTracker\Enums\TrackerLabelsEnum;

interface Tracker
{
    public function track(TrackerLabelsEnum $label, array $payload = []);

    public function identify(string $identifier): self;
}
