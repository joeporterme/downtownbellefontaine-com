<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MultiDayEventTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-12-05 12:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function event(string $title, string $start, ?string $end = null, array $extra = []): Event
    {
        return Event::create($extra + [
            'title' => $title,
            'event_date' => $start,
            'end_date' => $end,
            'status' => 'approved',
        ]);
    }

    public function test_running_event_stays_listed_until_its_end_date_and_sorts_first(): void
    {
        $this->event('Holiday Lights', '2026-11-28', '2026-12-20');
        $this->event('Parade', '2026-12-06');
        $this->event('Fall Fest', '2026-11-01', '2026-11-30');   // ended
        $this->event('Craft Show', '2026-12-01');                // single day, past

        $titles = Event::approved()->upcoming()->orderBy('event_date')->pluck('title')->all();

        $this->assertSame(['Holiday Lights', 'Parade'], $titles);
        $this->assertEqualsCanonicalizing(['Fall Fest', 'Craft Show'], Event::past()->pluck('title')->all());
    }

    public function test_event_is_listed_on_its_last_day(): void
    {
        $this->event('Last Day', '2026-12-01', '2026-12-05');

        $this->assertSame(1, Event::upcoming()->count());
    }

    public function test_date_labels(): void
    {
        $run = $this->event('Holiday Lights', '2026-11-28', '2026-12-20');
        $one = $this->event('Parade', '2026-12-06');
        $span = $this->event('New Year', '2026-12-30', '2027-01-02');

        $this->assertSame('Nov 28 – Dec 20', $run->date_range);
        $this->assertSame('November 28 – December 20, 2026', $run->date_label);
        $this->assertTrue($run->isHappeningNow());
        $this->assertSame('Dec 6', $one->date_range);
        $this->assertFalse($one->isMultiDay());
        $this->assertSame('Dec 30, 2026 – Jan 2, 2027', $span->date_range);
    }

    public function test_schedule_replaces_time_on_cards(): void
    {
        $e = $this->event('Market', '2026-12-10', '2026-12-12', [
            'start_time' => '10:00', 'end_time' => '16:00', 'schedule' => "Thu 4–8 PM\nSat 10–2",
        ]);

        $this->assertSame("Thu 4–8 PM\nSat 10–2", $e->hours_label);
    }

    public function test_events_page_shows_one_card_with_the_range(): void
    {
        $this->event('Holiday Lights', '2026-11-28', '2026-12-20', ['schedule' => 'Nightly 5 to 10 PM']);

        $html = $this->get('/events')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '>Holiday Lights<'));
        $this->assertStringContainsString('Nov 28 – Dec 20', $html);
        $this->assertStringContainsString('Happening now', $html);
        $this->assertStringContainsString('Nightly 5 to 10 PM', $html);
    }

    public function test_owner_can_submit_a_multi_day_event_with_hours(): void
    {
        $owner = User::factory()->create(['role' => 'business_owner']);

        $this->actingAs($owner)->post('/portal/events', [
            'title' => 'Holiday Market',
            'event_date' => '2026-12-10',
            'end_date' => '2026-12-12',
            'start_time' => '17:00',
            'end_time' => '14:00', // last day closes earlier; allowed on a multi-day run
            'schedule' => "Thu & Fri 5 to 9 PM\nSat 10 AM to 2 PM",
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('events', ['title' => 'Holiday Market', 'status' => 'pending']);
        $this->assertSame('2026-12-12', Event::firstWhere('title', 'Holiday Market')->end_date->toDateString());
    }

    public function test_end_date_cannot_be_before_start(): void
    {
        $owner = User::factory()->create(['role' => 'business_owner']);

        $this->actingAs($owner)->post('/portal/events', [
            'title' => 'Backwards',
            'event_date' => '2026-12-10',
            'end_date' => '2026-12-08',
        ])->assertSessionHasErrors('end_date');
    }

    public function test_single_day_end_time_must_follow_start(): void
    {
        $owner = User::factory()->create(['role' => 'business_owner']);

        $this->actingAs($owner)->post('/portal/events', [
            'title' => 'Odd Hours',
            'event_date' => '2026-12-10',
            'start_time' => '17:00',
            'end_time' => '14:00',
        ])->assertSessionHasErrors('end_time');
    }

    public function test_admin_event_form_loads_with_new_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $event = $this->event('Holiday Lights', '2026-11-28', '2026-12-20', ['schedule' => 'Nightly 5 to 10 PM']);

        $this->actingAs($admin)->get('/admin/events/create')->assertOk()->assertSee('End Date')->assertSee('Schedule / Hours');
        $this->actingAs($admin)->get("/admin/events/{$event->id}/edit")->assertOk();
        $this->actingAs($admin)->get('/admin/events')->assertOk()->assertSee('Nov 28 – Dec 20');
    }
}
