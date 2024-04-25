<?php

namespace Placetopay\AnalyticsTracker\Trackers;

use Mixpanel;
use Placetopay\AnalyticsTracker\Contracts\AnalyticsTracker;

class MixpanelTracker implements AnalyticsTracker
{
    public ?Mixpanel $mixpanel = null;
    private array $defaultPayload = [];

    public function __construct()
    {
        if (!$this->enabled()) {
            return;
        }

        if (!$token = config('analytics-tracker.mixpanel.project_token')) {
            logger()->warning('Calling mixpanel but there is not token');
            return;
        }

        $this->mixpanel = Mixpanel::getInstance($token);
    }

    public function track(string $label, array $payload = []): void
    {
        $this->mixpanel?->track($label, array_merge($this->defaultPayload, $payload));
    }

    public function setDefaultPayload(array $payload): self
    {
        $this->defaultPayload = $payload;
        return $this;
    }

    private function enabled(): bool
    {
        return config('analytics-tracker.mixpanel.enabled');
    }

    public function setIdentifier(string $identifier): self
    {
        $this->mixpanel?->identify(strtolower(trim($identifier)));

        return $this;
    }
}
