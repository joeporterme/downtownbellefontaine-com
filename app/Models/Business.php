<?php

namespace App\Models;

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
