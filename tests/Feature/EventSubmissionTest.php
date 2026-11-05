<?php

namespace Tests\Feature;

use App\Mail\EventSubmission;
use App\Models\Event;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create(['role' => 'business_owner']);
    }

    private function eventData(array $overrides = []): array
    {
        return $overrides + [
            'title' => 'Fall Sidewalk Sale',
            'description' => 'Deals up and down Main Street.',
            'event_date' => now()->addWeek()->toDateString(),
            'start_time' => '10:00',
            'end_time' => '14:00',
            'location_name' => 'Main Street',
        ];
    }

    public function test_new_event_emails_every_event_recipient_set_in_admin(): void
    {
        Mail::fake();
        SiteSetting::current()->update([
            'contact_form_recipients' => ['contact@example.com'],
            'event_submission_recipients' => ['Bri@Example.com', 'adam@example.com', 'nope', 'bri@example.com'],
        ]);
        $owner = $this->owner();

        $this->actingAs($owner)->post('/portal/events', $this->eventData())->assertRedirect();

        $this->assertDatabaseHas('events', ['title' => 'Fall Sidewalk Sale', 'status' => 'pending']);
        Mail::assertSent(EventSubmission::class, function ($mail) use ($owner) {
            return $mail->hasTo('bri@example.com')
                && $mail->hasTo('adam@example.com')
                && ! $mail->hasTo('contact@example.com')
                && count($mail->to) === 2
                && $mail->hasReplyTo($owner->email)
                && ! $mail->isUpdate;
        });
    }

    public function test_falls_back_to_contact_form_recipients(): void
    {
        Mail::fake();
        SiteSetting::current()->update([
            'contact_form_recipients' => ['contact@example.com'],
            'event_submission_recipients' => [],
        ]);

        $this->actingAs($this->owner())->post('/portal/events', $this->eventData());

        Mail::assertSent(EventSubmission::class, fn ($mail) => $mail->hasTo('contact@example.com'));
    }

    public function test_editing_an_approved_event_sends_a_reapproval_email(): void
    {
        Mail::fake();
        SiteSetting::current()->update(['event_submission_recipients' => ['bri@example.com']]);
        $owner = $this->owner();
        $event = Event::create($this->eventData([
            'submitted_by' => $owner->id,
            'status' => 'approved',
        ]));

        $this->actingAs($owner)->put("/portal/events/{$event->id}", $this->eventData(['title' => 'Fall Sidewalk Sale, Extended']));

        $this->assertSame('pending', $event->fresh()->status);
        Mail::assertSent(EventSubmission::class, fn ($mail) => $mail->isUpdate && $mail->hasTo('bri@example.com'));
    }

    public function test_editing_a_pending_event_does_not_email_again(): void
    {
        Mail::fake();
        SiteSetting::current()->update(['event_submission_recipients' => ['bri@example.com']]);
        $owner = $this->owner();
        $event = Event::create($this->eventData(['submitted_by' => $owner->id, 'status' => 'pending']));

        $this->actingAs($owner)->put("/portal/events/{$event->id}", $this->eventData(['title' => 'Typo fixed']));

        Mail::assertNotSent(EventSubmission::class);
    }

    public function test_email_renders_with_review_link(): void
    {
        $owner = $this->owner();
        $event = Event::create($this->eventData(['submitted_by' => $owner->id, 'status' => 'pending']));

        $html = (new EventSubmission($event))->render();

        $this->assertStringContainsString('Fall Sidewalk Sale', $html);
        $this->assertStringContainsString("/admin/events/{$event->id}/edit", $html);
        $this->assertStringContainsString($owner->email, $html);
    }
}
