<?php

namespace Placetopay\AnalyticsTracker\Contracts;

interface Tracker
{
    public function track(string $label, array $payload = []);

    public function setDefaultPayload(array $payload): self;

    public function identify(string $identifier): self;
}
