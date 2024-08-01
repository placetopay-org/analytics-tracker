<?php

namespace Placetopay\AnalyticsTracker\Trackers;

use Mixpanel;
use Placetopay\AnalyticsTracker\Contracts\AnalyticsTracker;

class MixpanelTracker implements AnalyticsTracker
{
    private ?Mixpanel $mixpanel = null;
    private array $defaultPayload = [];
    private bool $shouldTrackEvents = true;

    public function __construct()
    {
        if (!$this->enabled()) {
            return;
        }

        if (!$token = config('analytics-tracker.mixpanel.project_token')) {
            logger()->warning('Calling mixpanel but there is not token');
            return;
        }

        $this->mixpanel = app(Mixpanel::class, ['token' => $token]);
    }

    public function track(string $label, array $payload = []): void
    {
        if ($this->shouldTrackEvents) {
            $this->mixpanel?->track($label, array_merge($this->defaultPayload, $payload));
        }
    }

    public function setDefaultPayload(array $payload): self
    {
        $this->defaultPayload = $payload;
        return $this;
    }

    public function shouldTrackEvents(bool $shouldTrackEvents): self
    {
        $this->shouldTrackEvents = $shouldTrackEvents;
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
