<?php

namespace Placetopay\AnalyticsTracker\Contracts;

use Placetopay\AnalyticsTracker\Enums\TrackerLabelsEnum;

interface Tracker
{
    public function track(TrackerLabelsEnum $label, array $payload = []);

    public function setDefaultPayload(array $payload): self;

    public function identify(string $identifier): self;
}
