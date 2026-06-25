<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_can_register_and_reach_the_portal(): void
    {
        $response = $this->post('/register', [
            'name' => 'Jane Owner',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Regression guard: this used to 500 on a missing verification.notice route.
        $response->assertRedirect(route('business.dashboard'));

        $this->assertAuthenticated();
        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('business_owner', $user->role);
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
