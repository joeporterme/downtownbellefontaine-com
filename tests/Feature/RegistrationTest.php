<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_register_but_must_verify_before_the_portal(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jane Owner',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Registered + logged in, but sent to verify their email first.
        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('business_owner', $user->role);
        $this->assertNull($user->email_verified_at, 'new account should start unverified');

        // The portal is gated by the verified middleware.
        $this->get(route('business.dashboard'))->assertRedirect(route('verification.notice'));
    }

    public function test_a_verified_owner_reaches_the_portal(): void
    {
        $user = User::factory()->create(['role' => 'business_owner']); // factory sets email_verified_at

        $this->actingAs($user)->get(route('business.dashboard'))->assertOk();
    }

    public function test_registration_requires_a_valid_email(): void
    {
        $this->post('/register', [
            'name' => 'Jane',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }
}
