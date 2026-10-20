<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A recurring annual event shown on the Visitors Guide's 12-month calendar.
 */
class SignatureEvent extends Model
{
    protected $fillable = [
        'name',
        'month',
        'timing_note',
        'blurb',
        'url',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'month' => 'integer',
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    public const MONTHS = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('month')->orderBy('sort')->orderBy('name');
    }

    public function getMonthNameAttribute(): string
    {
        return self::MONTHS[$this->month] ?? '';
    }
}
