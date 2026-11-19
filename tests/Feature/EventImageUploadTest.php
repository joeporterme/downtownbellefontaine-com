<?php

namespace Tests\Feature;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EventImageUploadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Event flyers come in all shapes (the Brunch with Santa flyer is
     * 700x444). The admin must accept them instead of failing with
     * "invalid image dimensions".
     */
    public function test_admin_can_upload_a_non_16x9_event_flyer(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        Livewire::test(CreateEvent::class)
            ->fillForm([
                'title' => 'Brunch with Santa',
                'event_date' => now()->addWeek()->toDateString(),
                'status' => 'approved',
                'featured_image' => UploadedFile::fake()->image('santa-brunch-2026-700x444.jpg', 700, 444),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $event = Event::firstWhere('title', 'Brunch with Santa');
        $this->assertNotNull($event->featured_image);
        Storage::disk('public')->assertExists($event->featured_image);
    }

    public function test_owner_portal_keeps_the_whole_flyer(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create(['role' => 'business_owner']);

        $this->actingAs($owner)->post('/portal/events', [
            'title' => 'Flyer Event',
            'event_date' => now()->addWeek()->toDateString(),
            'featured_image' => UploadedFile::fake()->image('flyer.jpg', 700, 444),
        ])->assertSessionHasNoErrors();

        $path = Event::firstWhere('title', 'Flyer Event')->featured_image;
        [$w, $h] = getimagesizefromstring(Storage::disk('public')->get($path));
        $this->assertSame([700, 444], [$w, $h]); // not cropped or stretched
    }
}
