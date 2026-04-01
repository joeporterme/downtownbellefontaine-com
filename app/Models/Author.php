<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'bio',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Author $author) {
            if (empty($author->slug)) {
                $author->slug = Str::slug($author->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
