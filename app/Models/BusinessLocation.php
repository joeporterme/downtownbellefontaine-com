<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessLocation extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'latitude',
        'longitude',
        'streetview_pano_id',
        'streetview_heading',
        'streetview_pitch',
        'streetview_zoom',
        'streetview_image',
        'listing_image',
        'listing_image_credit',
        'places_photo_url',
        'hours',
        'is_primary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'streetview_heading' => 'decimal:4',
            'streetview_pitch' => 'decimal:3',
            'streetview_zoom' => 'decimal:2',
            'hours' => 'array',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip,
        ]);

        return implode(', ', $parts);
    }
}
