<?php

namespace Placetopay\AnalyticsTracker\Trackers;

use Closure;
use Mixpanel;
use Placetopay\AnalyticsTracker\Contracts\AnalyticsTracker;

class MixpanelTracker implements AnalyticsTracker
{
    private ?Mixpanel $mixpanel = null;
    private array $defaultPayload = [];
    private ?Closure $shouldTrackEvents = null;

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
        if ($this->shouldTrack($label, $payload)) {
            $this->mixpanel?->track($label, array_merge($this->defaultPayload, $payload));
        }
    }

    private function shouldTrack(string $label, array $payload = [])
    {
        return is_callable($this->shouldTrackEvents)
            ? ($this->shouldTrackEvents)($label, $payload)
            : true;
    }

    public function setDefaultPayload(array $payload): self
    {
        $this->defaultPayload = $payload;
        return $this;
    }

    public function shouldTrackEvents(callable $shouldTrackEvents): self
    {
        $this->shouldTrackEvents = $shouldTrackEvents(...);

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
