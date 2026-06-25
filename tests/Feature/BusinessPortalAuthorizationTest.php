<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessPortalAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function owner(string $email): User
    {
        return User::factory()->create([
            'email' => $email,
            'role' => 'business_owner',
        ]);
    }

    public function test_owner_can_edit_their_own_business(): void
    {
        $owner = $this->owner('owner@example.com');
        $business = Business::create([
            'user_id' => $owner->id,
            'name' => 'Owner Cafe',
            'status' => 'approved',
        ]);

        $this->actingAs($owner)
            ->get("/portal/business/{$business->id}/edit")
            ->assertOk();
    }

    public function test_owner_cannot_edit_another_owners_business(): void
    {
        $owner = $this->owner('owner@example.com');
        $intruder = $this->owner('intruder@example.com');
        $business = Business::create([
            'user_id' => $owner->id,
            'name' => 'Owner Cafe',
            'status' => 'approved',
        ]);

        $this->actingAs($intruder)
            ->get("/portal/business/{$business->id}/edit")
            ->assertForbidden();
    }
}
