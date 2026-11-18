<?php

namespace App\Models;

use App\Models\Concerns\SanitizesHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    use SanitizesHtml;

    protected $fillable = [
        'business_id',
        'submitted_by',
        'title',
        'slug',
        'description',
        'featured_image',
        'event_date',
        'end_date',
        'start_time',
        'end_time',
        'schedule',
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
        'end_date' => 'date',
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
        return Attribute::make(get: fn () => $this->sanitizeHtml($this->description, allowMedia: false));
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

    /**
     * Events that haven't finished yet. A multi-day event stays listed until
     * its end date passes; ordering by event_date keeps one that's already
     * running near the top.
     */
    public function scopeUpcoming($query)
    {
        $today = now()->toDateString();

        return $query->where(fn ($q) => $q
            ->where('end_date', '>=', $today)
            ->orWhere(fn ($q) => $q->whereNull('end_date')->where('event_date', '>=', $today)));
    }

    public function scopePast($query)
    {
        $today = now()->toDateString();

        return $query->where(fn ($q) => $q
            ->where('end_date', '<', $today)
            ->orWhere(fn ($q) => $q->whereNull('end_date')->where('event_date', '<', $today)));
    }

    public function isMultiDay(): bool
    {
        return $this->end_date && $this->event_date && $this->end_date->gt($this->event_date);
    }

    /** A multi-day event that started before today and hasn't ended. */
    public function isHappeningNow(): bool
    {
        return $this->isMultiDay()
            && $this->event_date->lt(today())
            && $this->end_date->gte(today());
    }

    /**
     * Short date for cards: "Dec 6", or "Nov 28 – Dec 20" for a run.
     */
    public function getDateRangeAttribute(): ?string
    {
        if (!$this->event_date) {
            return null;
        }
        if (!$this->isMultiDay()) {
            return $this->event_date->format('M j');
        }
        if ($this->event_date->year !== $this->end_date->year) {
            return $this->event_date->format('M j, Y') . ' – ' . $this->end_date->format('M j, Y');
        }

        return $this->event_date->format('M j') . ' – ' . $this->end_date->format('M j');
    }

    /**
     * Long date for detail views: "Saturday, December 6, 2026", or
     * "November 28 – December 20, 2026" for a run.
     */
    public function getDateLabelAttribute(): ?string
    {
        if (!$this->event_date) {
            return null;
        }
        if (!$this->isMultiDay()) {
            return $this->event_date->format('l, F j, Y');
        }
        if ($this->event_date->year !== $this->end_date->year) {
            return $this->event_date->format('F j, Y') . ' – ' . $this->end_date->format('F j, Y');
        }

        return $this->event_date->format('F j') . ' – ' . $this->end_date->format('F j, Y');
    }

    /** What to show for "when" on a card: the schedule text if set, else the time range. */
    public function getHoursLabelAttribute(): ?string
    {
        return $this->schedule ? trim($this->schedule) : $this->formatted_time;
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
