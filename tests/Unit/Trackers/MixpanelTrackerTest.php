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
        $key = $this->faker->word();
        $value = $this->faker->word();
        $eventLabel = $this->faker->word();

        $mixpanelMock = $this->createMock(Mixpanel::class);
        $mixpanelMock->expects($this->once())
            ->method('track')
            ->with(
                $this->equalTo($eventLabel),
                $this->equalTo([
                    'backend' => true,
                    $key => $value,
                    'customProperty' => 'johnDue@user.com'
                ])
            );
        $this->app->bind(Mixpanel::class, fn() => $mixpanelMock);

        $mixpanelTracker = new MixpanelTracker();
        //$mixpanelTracker->mixpanel = $mixpanelMock;
        $mixpanelTracker
            ->setDefaultPayload([
                'backend' => true,
                'customProperty' => 'johnDue@user.com',
                $key => $value,
            ])
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

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->mixpanel = $mixpanelMock;
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

        Log::shouldReceive('warning')->once();

        $mixpanelTracker = new MixpanelTracker();
        $mixpanelTracker->mixpanel = $mixpanelMock;
        $mixpanelTracker->track($label);
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

    public static function configProvider(): array
    {
        return [
            ['analytics-tracker.mixpanel.project_token', null],
            ['analytics-tracker.mixpanel.enabled', false],
        ];
    }
}
