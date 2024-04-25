<?php

namespace Placetopay\AnalyticsTracker\Contracts;

interface AnalyticsTracker
{
    public function track(string $label, array $payload = []);

    public function setDefaultPayload(array $payload): self;

    public function setIdentifier(string $identifier): self;
}
