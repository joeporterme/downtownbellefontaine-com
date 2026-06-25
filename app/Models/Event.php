<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

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
                $base = Str::slug($event->title);
                $slug = $base;
                $suffix = 2;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $suffix++;
                }
                $event->slug = $slug;
            }
        });
    }

    /**
     * Description sanitized for safe HTML output. Admin authors rich text via
     * the Filament editor; business owners submit plain text via a textarea.
     * Strips scripts/event handlers/unsafe URIs from either source so the
     * public view can render it without stored-XSS risk.
     */
    protected function safeDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (blank($this->description)) {
                    return null;
                }

                $config = (new HtmlSanitizerConfig())
                    ->allowSafeElements()
                    ->allowRelativeLinks()
                    ->allowLinkSchemes(['https', 'http', 'mailto']);

                return (new HtmlSanitizer($config))->sanitize($this->description);
            }
        );
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
