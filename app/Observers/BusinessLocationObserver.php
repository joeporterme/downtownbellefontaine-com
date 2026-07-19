<?php

namespace App\Observers;

use App\Models\BusinessLocation;
use App\Services\Google\PlacesPhotoService;
use App\Services\Google\StreetViewService;

class BusinessLocationObserver
{
    public function __construct(
        protected StreetViewService $streetView,
        protected PlacesPhotoService $placesPhoto,
    ) {}

    /**
     * Regenerate the cached Street View snapshot when the saved camera changes,
     * and download a newly-picked Google Places photo into the listing override.
     */
    public function saved(BusinessLocation $location): void
    {
        $this->maybeDownloadPlacesPhoto($location);

        $povChanged = $location->wasChanged([
            'streetview_pano_id',
            'streetview_heading',
            'streetview_pitch',
            'streetview_zoom',
        ]);

        $newWithPov = $location->wasRecentlyCreated && filled($location->streetview_pano_id);

        if (! $povChanged && ! $newWithPov) {
            return;
        }

        if (blank($location->streetview_pano_id)
            && (blank($location->latitude) || blank($location->longitude))) {
            return;
        }

        $path = $this->streetView->snapshot($location);

        if ($path && $path !== $location->streetview_image) {
            $location->streetview_image = $path;
            $location->saveQuietly(); // won't re-trigger this observer
        }
    }

    /**
     * When the admin picks a Google Places photo, the picker stashes its URL in
     * places_photo_url. Download it into listing_image, then clear the marker so
     * it isn't fetched again on the next save.
     */
    protected function maybeDownloadPlacesPhoto(BusinessLocation $location): void
    {
        if (! $location->wasChanged('places_photo_url') || blank($location->places_photo_url)) {
            return;
        }

        $path = $this->placesPhoto->store($location, $location->places_photo_url);

        $location->places_photo_url = null; // consume the marker either way

        if ($path) {
            $location->listing_image = $path;
        }

        $location->saveQuietly(); // won't re-trigger this observer
    }
}
