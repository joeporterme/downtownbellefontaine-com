<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_honeypot_submissions_are_ignored(): void
    {
        $this->post('/contact', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam',
            'message' => 'buy stuff',
            'website_url' => 'http://spam.example', // honeypot field
        ]);

        $this->assertSame(0, ContactMessage::count());
    }
}
