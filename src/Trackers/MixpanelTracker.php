<?php

namespace Placetopay\AnalyticsTracker\Trackers;

use Mixpanel;
use Placetopay\AnalyticsTracker\Contracts\Tracker;
use Placetopay\AnalyticsTracker\Enums\TrackerLabelsEnum;

class MixpanelTracker implements Tracker
{
    public ?Mixpanel $mixpanel = null;
    private bool $identified = false;
    private array $defaultPayload = [];

    public function __construct()
    {
        if (!$this->enabled()) {
            logger()->warning('Calling mixpanel but it is disabled');
            return;
        }

        if (!$this->hasToken()) {
            logger()->warning('Calling mixpanel but there is not token');
            return;
        }

        $this->mixpanel = Mixpanel::getInstance(config('analytics-tracker.mixpanel.project_token'));
    }

    public function track(TrackerLabelsEnum $label, array $payload = []): void
    {
        if (!$this->identified) {
            logger()->warning('You must identify the user in order to track an event');
            return;
        }

        $this->mixpanel?->track($label->value, array_merge($this->defaultPayload, $payload, ['backend' => true]));
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

    public function hasToken(): bool
    {
        return (bool)config('analytics-tracker.mixpanel.project_token');
    }

    public function identify(string $identifier): self
    {
        $this->mixpanel?->identify(strtolower(trim($identifier)));
        $this->identified = true;
        return $this;
    }
}
