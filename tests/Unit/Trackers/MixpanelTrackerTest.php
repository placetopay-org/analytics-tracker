<?php

namespace Placetopay\AnalyticsTracker\Tests\Unit\Trackers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mixpanel;
use Placetopay\AnalyticsTracker\Tests\TestCase;
use Placetopay\AnalyticsTracker\Trackers\MixpanelTracker;

class MixpanelTrackerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('analytics-tracker.mixpanel.enabled', true);
        config()->set('analytics-tracker.mixpanel.project_token', Str::random(32));
    }

    /**
     * @test
     */
    public function it_can_track_an_event(): void
    {
        $eventLabel = $this->faker->word();

        $testPayload = [
            $this->faker->word(),
            'backend' => true,
            'customProperty' => 'johnDue@user.com',
        ];

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with(
                $this->equalTo($eventLabel),
                $this->equalTo($testPayload)
            );
        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker
            ->setDefaultPayload($testPayload)
            ->track($eventLabel);
    }

    /**
     * @test
     */
    public function it_can_identify_the_user(): void
    {
        $fakeEmail = $this->faker->email();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('identify')
            ->with($fakeEmail);
        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->setIdentifier($fakeEmail);
    }

    /**
     * @test
     */
    public function it_can_track_if_user_has_not_been_identified(): void
    {
        $label = $this->faker->word();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with($label);
        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->track($label);
    }

    /**
     * @test
     */
    public function it_does_not_track_when_disabled(): void
    {
        config()->set('analytics-tracker.mixpanel.enabled', false);

        $label = $this->faker->word();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->never())
            ->method('track')
            ->with($label);
        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->track($label);
    }

    /**
     * @test
     */
    public function it_decides_to_track_events_with_function(): void
    {
        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with('EventTracked', ['prop1' => 'value1']);

        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $shouldTrackEvent = function (string $label, array $payload) {
            $this->assertArrayHasKey('prop1', $payload);

            return $label === 'EventTracked';
        };

        $mixpanelTracker = (new MixpanelTracker())->shouldTrackEvents($shouldTrackEvent);
        $mixpanelTracker->track('EventDropped', ['prop1' => 'value1']);
        $mixpanelTracker->track('EventTracked', ['prop1' => 'value1']);
    }

    /**
     * @test
     */
    public function it_decides_to_track_events_with_an_invokable(): void
    {
        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with('EventTracked', ['prop1' => 'value1']);

        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $shouldTrackEvent = new class {
            public function __invoke(string $label, array $payload)
            {
                return $label === 'EventTracked';
            }
        };

        $mixpanelTracker = (new MixpanelTracker())->shouldTrackEvents($shouldTrackEvent);
        $mixpanelTracker->track('EventDropped', ['prop1' => 'value1']);
        $mixpanelTracker->track('EventTracked', ['prop1' => 'value1']);
    }

    /**
     * @test
     */
    public function it_does_not_track_when_should_not_track_events(): void
    {
        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->never())
            ->method('track')
            ->with('EventDropped', ['prop1' => 'value1']);

        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        $mixpanelTracker = (new MixpanelTracker())->shouldTrackEvents(fn () => false);
        $mixpanelTracker->track('EventDropped', ['prop1' => 'value1']);
    }

    /**
     * @test
     */
    public function it_does_not_track_when_has_no_token(): void
    {
        config()->set('analytics-tracker.mixpanel.project_token', '');

        $label = $this->faker->word();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->never())
            ->method('track')
            ->with($label);
        $this->app->offsetSet(Mixpanel::class, $mixpanelMock);

        Log::shouldReceive('warning')->with('Calling mixpanel but there is not token')->once();

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->track($label);
    }
}
