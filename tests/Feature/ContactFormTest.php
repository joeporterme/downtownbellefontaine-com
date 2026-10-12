<?php

namespace Tests\Feature;

use App\Mail\ContactSubmission;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_submission_is_stored(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Pat Visitor',
            'email' => 'pat@example.com',
            'subject' => 'Hello',
            'message' => 'I love downtown Bellefontaine.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'pat@example.com',
            'subject' => 'Hello',
        ]);
    }

    public function test_honeypot_submissions_are_flagged_as_spam_and_not_emailed(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => 'buy stuff',
            'website_url' => 'http://spam.example', // honeypot field
        ]);

        // Spam is kept (flagged) for admin review, not silently dropped...
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'bot@example.com',
            'is_spam' => true,
        ]);
        // ...and never triggers a notification email.
        Mail::assertNotSent(ContactSubmission::class);
    }

    public function test_clean_submission_is_not_flagged(): void
    {
        $this->post('/contact', [
            'name' => 'Pat Visitor',
            'email' => 'clean@example.com',
            'subject' => 'Hello',
            'message' => 'I would love to learn more about visiting downtown Bellefontaine this fall.',
            'form_start_time' => time() - 60, // human pacing, no timing penalty
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'clean@example.com',
            'is_spam' => false,
        ]);
    }
}
