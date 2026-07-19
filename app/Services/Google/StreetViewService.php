<?php

namespace App\Services\Google;

use App\Models\BusinessLocation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class StreetViewService
{
    protected const STATIC_ENDPOINT = 'https://maps.googleapis.com/maps/api/streetview';
    protected const META_ENDPOINT = 'https://maps.googleapis.com/maps/api/streetview/metadata';
    protected const DIRECTORY = 'businesses/streetview';

    /**
     * Fetch a Street View snapshot for the location's saved camera, process it,
     * store it on the public disk, and return the stored path (or null on failure).
     * Deletes any previously stored snapshot for this location.
     */
    public function snapshot(BusinessLocation $location): ?string
    {
        $key = config('services.google.maps_server_key');

        if (blank($key) || (blank($location->streetview_pano_id) && (blank($location->latitude) || blank($location->longitude)))) {
            return null;
        }

        $params = [
            // Street View Static maxes out at 640x640 regardless of what we ask
            // for, so request the full native square and upscale ourselves.
            'size' => '640x640',
            'fov' => $this->fovFromZoom($location->streetview_zoom),
            'return_error_code' => 'true',
            'key' => $key,
        ];

        // Only pin the camera when it was framed in the admin. Otherwise omit
        // heading so Google auto-points it toward the storefront (its default).
        if (! is_null($location->streetview_heading)) {
            $params['heading'] = (float) $location->streetview_heading;
        }
        if (! is_null($location->streetview_pitch)) {
            $params['pitch'] = (float) $location->streetview_pitch;
        }

        if (filled($location->streetview_pano_id)) {
            $params['pano'] = $location->streetview_pano_id;
        } else {
            $params['location'] = "{$location->latitude},{$location->longitude}";
            // Outdoor street-level imagery only — never a business's indoor 360.
            $params['source'] = 'outdoor';
        }

        try {
            $response = Http::timeout(30)->get(self::STATIC_ENDPOINT, $params);

            if (! $response->successful() || ! str_starts_with($response->header('Content-Type', ''), 'image/')) {
                Log::warning('StreetView snapshot failed', [
                    'location_id' => $location->id,
                    'status' => $response->status(),
                    'body' => str($response->body())->limit(200)->toString(),
                ]);

                return null;
            }

            $image = Image::read($response->body());
            $image->cover(1200, 800);
            // Native source is only 640px, so upscaling softens it. A light
            // sharpen counters that, and a higher JPEG quality avoids stacking
            // fresh compression grain on top of Google's.
            $image->sharpen(6);
            $encoded = $image->toJpeg(88);

            $hash = substr(md5(implode('|', [
                $location->streetview_pano_id,
                $location->streetview_heading,
                $location->streetview_pitch,
                $location->streetview_zoom,
            ])), 0, 8);

            $path = self::DIRECTORY."/{$location->id}-{$hash}.jpg";

            // Remove the previous snapshot if it was a different file.
            if (filled($location->streetview_image) && $location->streetview_image !== $path) {
                Storage::disk('public')->delete($location->streetview_image);
            }

            Storage::disk('public')->put($path, (string) $encoded);

            unset($image, $encoded);

            return $path;
        } catch (\Throwable $e) {
            Log::warning('StreetView snapshot exception', [
                'location_id' => $location->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Whether Google has Street View imagery near this location (free metadata call).
     */
    public function hasImagery(BusinessLocation $location): bool
    {
        $key = config('services.google.maps_server_key');

        if (blank($key) || blank($location->latitude) || blank($location->longitude)) {
            return false;
        }

        try {
            $response = Http::timeout(15)->get(self::META_ENDPOINT, [
                'location' => "{$location->latitude},{$location->longitude}",
                'key' => $key,
            ]);

            return ($response->json('status') ?? null) === 'OK';
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Convert a Maps JS Street View zoom level to a Static API field-of-view (degrees).
     * zoom 0 ≈ 180°, each step roughly halves the FOV; clamped to a sane range.
     */
    protected function fovFromZoom($zoom): float
    {
        if (blank($zoom)) {
            return 90.0;
        }

        $fov = 180 / (2 ** (float) $zoom);

        return round(max(20.0, min(110.0, $fov)), 2);
    }
}
