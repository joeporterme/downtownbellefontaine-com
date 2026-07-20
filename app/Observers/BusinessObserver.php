<?php

namespace App\Observers;

use App\Models\Business;
use App\Services\Google\PlacesPhotoService;

class BusinessObserver
{
    public function __construct(protected PlacesPhotoService $placesPhoto) {}

    /**
     * When the admin picks a Google Places photo for the featured image, the
     * picker stashes its URL in featured_places_url. Download it into
     * featured_image, then clear the marker so it isn't fetched again.
     */
    public function saved(Business $business): void
    {
        if (! $business->wasChanged('featured_places_url') || blank($business->featured_places_url)) {
            return;
        }

        $path = $this->placesPhoto->storeFeatured($business, $business->featured_places_url);

        $business->featured_places_url = null; // consume the marker either way

        if ($path) {
            $business->featured_image = $path;
        }

        $business->saveQuietly(); // won't re-trigger this observer
    }
}
