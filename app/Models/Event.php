<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'business_id',
        'submitted_by',
        'title',
        'slug',
        'description',
        'featured_image',
        'event_date',
        'start_time',
        'end_time',
        'more_info_url',
        'location_name',
        'address',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'approved_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
                $count = static::where('slug', 'like', $event->slug . '%')->count();
                if ($count > 0) {
                    $event->slug .= '-' . ($count + 1);
                }
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    public function getFullAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    public function getFormattedTimeAttribute(): ?string
    {
        if (!$this->start_time) {
            return null;
        }

        $start = $this->start_time->format('g:i A');

        if ($this->end_time) {
            return $start . ' - ' . $this->end_time->format('g:i A');
        }

        return $start;
    }

    public function approve(User $user): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $user->id,
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }
}
