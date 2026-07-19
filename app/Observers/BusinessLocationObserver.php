<?php

namespace App\Observers;

use App\Models\BusinessLocation;
use App\Services\Google\StreetViewService;

class BusinessLocationObserver
{
    public function __construct(protected StreetViewService $streetView) {}

    /**
     * Regenerate the cached Street View snapshot when the saved camera changes.
     */
    public function saved(BusinessLocation $location): void
    {
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
}
