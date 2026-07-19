<?php

namespace Tests\Unit;

use App\Models\Business;
use App\Models\BusinessLocation;
use Tests\TestCase;

/**
 * The listing image resolves in priority order: curated photo > Street View
 * snapshot > featured image > null. Only a real Street View snapshot should
 * flag hasStreetView (which drives the vibrance filter + avatar de-dup).
 * These are pure accessors, exercised here without touching the database.
 */
class BusinessImagePriorityTest extends TestCase
{
    private function business(array $attributes, ?string $streetviewImage): Business
    {
        $business = new Business($attributes);

        $location = $streetviewImage === null
            ? null
            : new BusinessLocation(['streetview_image' => $streetviewImage]);

        $business->setRelation('primaryLocation', $location);

        return $business;
    }

    public function test_curated_photo_wins_over_street_view_and_featured(): void
    {
        $business = $this->business([
            'listing_image' => 'businesses/listing/curated.jpg',
            'featured_image' => 'businesses/featured.jpg',
        ], streetviewImage: 'businesses/streetview/sv.jpg');

        $this->assertStringContainsString('curated.jpg', $business->listingImageUrl);
        $this->assertFalse($business->hasStreetView);
    }

    public function test_street_view_used_when_no_curated_photo(): void
    {
        $business = $this->business([
            'featured_image' => 'businesses/featured.jpg',
        ], streetviewImage: 'businesses/streetview/sv.jpg');

        $this->assertStringContainsString('sv.jpg', $business->listingImageUrl);
        $this->assertTrue($business->hasStreetView);
    }

    public function test_featured_image_used_when_no_curated_or_street_view(): void
    {
        $business = $this->business([
            'featured_image' => 'businesses/featured.jpg',
        ], streetviewImage: null);

        $this->assertStringContainsString('featured.jpg', $business->listingImageUrl);
        $this->assertFalse($business->hasStreetView);
    }

    public function test_null_when_no_images_at_all(): void
    {
        $business = $this->business([], streetviewImage: null);

        $this->assertNull($business->listingImageUrl);
        $this->assertFalse($business->hasStreetView);
    }
}
