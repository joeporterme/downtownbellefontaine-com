<?php

namespace App\Services\Google;

use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PlacesPhotoService
{
    protected const DIRECTORY = 'businesses/listing';

    /**
     * Hosts a Places photo URL is allowed to point at. The URL comes from the
     * admin form, so this allowlist (plus disabling redirects below) keeps the
     * server-side fetch from being pointed at internal/metadata endpoints.
     */
    protected const ALLOWED_HOST_SUFFIXES = [
        'googleusercontent.com',
        'ggpht.com',
        'googleapis.com',
        'gstatic.com',
    ];

    /**
     * Download a Google Places photo (the temporary googleusercontent URL the
     * Places JS library hands back), process it, store it on the public disk,
     * and return the stored path (or null on failure). Replaces any previously
     * stored Places photo for this location.
     *
     * The URL is a pre-authorized image CDN link, so no API key is needed here.
     */
    public function store(BusinessLocation $location, string $url): ?string
    {
        $previous = $location->getOriginal('listing_image') ?: $location->listing_image;

        return $this->download($url, self::DIRECTORY."/loc-{$location->id}-places-".substr(md5($url), 0, 8).'.jpg', $previous);
    }

    /**
     * Same, but store a chosen Google photo as a business's featured image.
     */
    public function storeFeatured(Business $business, string $url): ?string
    {
        $previous = $business->getOriginal('featured_image') ?: $business->featured_image;

        return $this->download($url, "businesses/{$business->id}-places-".substr(md5($url), 0, 8).'.jpg', $previous);
    }

    /**
     * Download + process a Places photo to $path, removing a previous auto
     * -generated Places file. Returns the stored path (or null on failure).
     * The URL is host-checked and fetched with a matching Referer so the
     * referrer-restricted browser key accepts the server-side request.
     */
    protected function download(string $url, string $path, ?string $previous): ?string
    {
        if (blank($url) || ! $this->isAllowedHost($url)) {
            return null;
        }

        try {
            // Host is allowlisted to Google above; media URLs redirect within
            // Google's CDN (not an SSRF vector). The new Places media URL carries
            // our referrer-restricted key, so send a matching Referer.
            $response = Http::timeout(30)
                ->withHeaders(['Referer' => rtrim(config('app.url'), '/').'/'])
                ->withOptions(['allow_redirects' => ['max' => 5, 'strict' => true]])
                ->get($url);

            if (! $response->successful() || ! str_starts_with($response->header('Content-Type', ''), 'image/')) {
                Log::warning('Places photo download failed', ['path' => $path, 'status' => $response->status()]);

                return null;
            }

            $image = Image::read($response->body());
            $image->cover(1200, 800);
            $encoded = $image->toJpeg(88);

            // Only clean up our own auto-generated Places files, never a manual upload.
            if (filled($previous) && $previous !== $path && str_contains($previous, '-places-')) {
                Storage::disk('public')->delete($previous);
            }

            Storage::disk('public')->put($path, (string) $encoded);

            unset($image, $encoded);

            return $path;
        } catch (\Throwable $e) {
            Log::warning('Places photo exception', ['path' => $path, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Only allow https URLs whose host is (a subdomain of) a Google-owned host.
     */
    protected function isAllowedHost(string $url): bool
    {
        if (! str_starts_with($url, 'https://')) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (blank($host)) {
            return false;
        }

        foreach (self::ALLOWED_HOST_SUFFIXES as $suffix) {
            if ($host === $suffix || str_ends_with($host, '.'.$suffix)) {
                return true;
            }
        }

        return false;
    }
}
