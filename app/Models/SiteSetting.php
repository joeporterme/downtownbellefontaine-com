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
        'contact_form_recipients',
        'event_submission_recipients',
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
        'gtm_container_id',
    ];

    protected $casts = [
        'contact_form_recipients' => 'array',
        'event_submission_recipients' => 'array',
    ];

    /**
     * Where contact form notifications go: the addresses set in the admin, or
     * the CONTACT_FORM_RECIPIENT env fallback when none are set.
     *
     * @return array<int, string>
     */
    public function contactRecipients(): array
    {
        $emails = static::cleanEmails($this->contact_form_recipients);

        if ($emails) {
            return $emails;
        }

        $fallback = config('services.contact.recipient');

        return $fallback ? [$fallback] : [];
    }

    /**
     * Where new event submission notifications go: the addresses set in the
     * admin, or the contact form recipients when none are set.
     *
     * @return array<int, string>
     */
    public function eventRecipients(): array
    {
        return static::cleanEmails($this->event_submission_recipients) ?: $this->contactRecipients();
    }

    /**
     * Lowercased, valid, de-duplicated addresses from an admin email list.
     *
     * @return array<int, string>
     */
    protected static function cleanEmails(?array $emails): array
    {
        return collect($emails ?? [])
            ->map(fn ($e) => strtolower(trim((string) $e)))
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

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
