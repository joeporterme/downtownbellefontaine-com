<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'blog_category_id',
        'author_id',
        'featured_image',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'blog_post_business')
            ->withTimestamps();
    }

    public function businessCategories(): BelongsToMany
    {
        return $this->belongsToMany(BusinessCategory::class, 'blog_post_business_category')
            ->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '>', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function isScheduled(): bool
    {
        return $this->status === 'published' && $this->published_at > now();
    }
}
