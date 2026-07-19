<?php

namespace App\Services\Google;

use App\Models\Business;
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
     * stored Places photo for this business.
     *
     * The URL is a pre-authorized image CDN link, so no API key is needed here.
     */
    public function store(Business $business, string $url): ?string
    {
        if (blank($url) || ! $this->isAllowedHost($url)) {
            return null;
        }

        try {
            $response = Http::timeout(30)
                ->withoutRedirecting()
                ->get($url);

            if (! $response->successful() || ! str_starts_with($response->header('Content-Type', ''), 'image/')) {
                Log::warning('Places photo download failed', [
                    'business_id' => $business->id,
                    'status' => $response->status(),
                ]);

                return null;
            }

            $image = Image::read($response->body());
            $image->cover(1200, 800);
            $encoded = $image->toJpeg(88);

            $hash = substr(md5($url), 0, 8);
            $path = self::DIRECTORY."/{$business->id}-places-{$hash}.jpg";

            // Remove the previous Places photo if it was a different file. We
            // only clean up our own auto-generated Places files, never a photo
            // the admin uploaded by hand.
            $previous = $business->getOriginal('listing_image') ?: $business->listing_image;
            if (filled($previous)
                && $previous !== $path
                && str_contains($previous, '-places-')) {
                Storage::disk('public')->delete($previous);
            }

            Storage::disk('public')->put($path, (string) $encoded);

            unset($image, $encoded);

            return $path;
        } catch (\Throwable $e) {
            Log::warning('Places photo exception', [
                'business_id' => $business->id,
                'error' => $e->getMessage(),
            ]);

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
