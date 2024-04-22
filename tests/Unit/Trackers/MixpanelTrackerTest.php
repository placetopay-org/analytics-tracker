<?php

namespace Placetopay\AnalyticsTracker\Tests\Unit\Trackers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mixpanel;
use Placetopay\AnalyticsTracker\Enums\TrackerLabelsEnum;
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
        $key = $this->faker->word();
        $value = $this->faker->word();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with(
                $this->equalTo(TrackerLabelsEnum::REQUESTED_3DS->value),
                $this->equalTo(['backend' => true, $key => $value])
            );

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->mixpanel = $mixpanelMock;
        $mixpanelTracker->setDefaultPayload([$key => $value]);
        $mixpanelTracker->identify($this->faker->email());
        $mixpanelTracker->track(TrackerLabelsEnum::REQUESTED_3DS);
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

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->mixpanel = $mixpanelMock;
        $mixpanelTracker->identify($fakeEmail);
    }

    /**
     * @test
     */
    public function it_can_not_track_if_has_not_been_identified(): void
    {
        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->never())
            ->method('track');

        Log::shouldReceive('warning')->once();

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->mixpanel = $mixpanelMock;
        $mixpanelTracker->track(TrackerLabelsEnum::REQUESTED_3DS);
    }

    public static function configProvider(): array
    {
        return [
            ['analytics-tracker.mixpanel.project_token', null],
            ['analytics-tracker.mixpanel.enabled', false],
        ];
    }

    /**
     * @test
     * @dataProvider configProvider
     */
    public function it_does_not_initialize_mixpanel_when_disabled_or_has_no_token($setting, $value): void
    {
        config()->set($setting, $value);
        Log::shouldReceive('warning')->once();
        $mixpanelTracker = new MixpanelTracker();
        $this->assertNull($mixpanelTracker->mixpanel);
    }
}
