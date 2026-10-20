<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One clue in the Visitors Guide's downtown scavenger hunt.
 */
class ScavengerClue extends Model
{
    protected $fillable = [
        'sort',
        'clue',
        'answer',
        'business_id',
        'image',
        'is_active',
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }
}
