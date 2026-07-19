<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'default_meta_description',
        'default_og_image',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'zip',
        'facebook_url',
        'instagram_url',
        'x_url',
        'tiktok_url',
        'youtube_url',
        'google_analytics_id',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
    }

    /**
     * The single settings row (created with defaults on first access), cached.
     */
    public static function current(): self
    {
        return Cache::rememberForever('site_settings', fn () => static::query()->firstOrCreate([]));
    }
}
