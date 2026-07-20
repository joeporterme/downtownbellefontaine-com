<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressItem extends Model
{
    protected $fillable = [
        'title',
        'url',
        'source',
        'published_date',
        'is_active',
    ];

    protected $casts = [
        'published_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
