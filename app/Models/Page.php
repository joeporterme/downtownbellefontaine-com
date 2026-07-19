<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'key',
        'title',
        'nav_label',
        'hero_eyebrow',
        'hero_heading',
        'hero_subheading',
        'hero_image',
        'seo_title',
        'seo_description',
        'og_image',
        'status',
        'published_at',
        'sort',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function forKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && (is_null($this->published_at) || $this->published_at <= now());
    }
}
