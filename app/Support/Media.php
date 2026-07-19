<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Resolve an image reference to a usable URL.
     * - Absolute URLs and root-relative public paths (e.g. /images/...) pass through.
     * - Everything else is treated as a path on the "public" storage disk.
     */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Absolute URL variant (for og:image / structured data).
     */
    public static function absoluteUrl(?string $path): ?string
    {
        $url = static::url($path);

        if (blank($url)) {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url($url);
    }
}
