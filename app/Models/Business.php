<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'website',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'snapchat_url',
        'x_url',
        'logo',
        'featured_image',
        'listing_image',
        'listing_image_credit',
        'hours',
        'social_links',
        'status',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'array',
            'social_links' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function owner(): BelongsTo
    {
        return $this->user();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BusinessCategory::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(BusinessLocation::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function primaryLocation()
    {
        return $this->hasOne(BusinessLocation::class)->where('is_primary', true);
    }

    public function activeLocations(): HasMany
    {
        return $this->locations()->where('is_active', true);
    }

    /**
     * The main listing image, in priority order:
     * 1. the primary location's curated override (manual upload / Google photo),
     * 2. a legacy business-level override (kept as a fallback),
     * 3. the primary location's saved Street View snapshot,
     * 4. the uploaded featured image, else null (placeholder).
     */
    public function getListingImageUrlAttribute(): ?string
    {
        $loc = $this->primaryLocation;

        if ($loc?->listing_image) {
            return Media::url($loc->listing_image);
        }

        if ($this->listing_image) {
            return Media::url($this->listing_image);
        }

        if ($loc?->streetview_image) {
            return Media::url($loc->streetview_image);
        }

        return $this->featured_image ? Media::url($this->featured_image) : null;
    }

    /**
     * The small logo/avatar shown on cards + the detail header.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->logo) {
            return Media::url($this->logo);
        }

        return $this->featured_image ? Media::url($this->featured_image) : null;
    }

    /**
     * Whether the listing image is a Street View snapshot (drives the vibrance
     * filter + logo-avatar de-dup). A curated photo override is NOT enhanced.
     */
    public function getHasStreetViewAttribute(): bool
    {
        $loc = $this->primaryLocation;

        return ! $loc?->listing_image
            && ! $this->listing_image
            && (bool) $loc?->streetview_image;
    }

    /**
     * The big listing image resolved to its owning location (for a per-location
     * override) or null when the resolved image is business-level/featured.
     */
    public function resolvedListingCredit(): ?string
    {
        return $this->primaryLocation?->listing_image
            ? $this->primaryLocation->listing_image_credit
            : ($this->listing_image ? $this->listing_image_credit : null);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->isApproved();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name);
            }
        });
    }
}
