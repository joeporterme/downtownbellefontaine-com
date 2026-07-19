<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

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

    /**
     * Post body sanitized for safe HTML output. Content comes from the Filament
     * rich editor (trusted) and the WordPress importer (untrusted external
     * source). strip_tags in the importer leaves attributes intact, so an
     * imported <img onerror=…> or javascript: link would otherwise be stored
     * XSS. Sanitize on render so both sources are safe.
     */
    protected function safeContent(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (blank($this->content)) {
                    return null;
                }

                $config = (new HtmlSanitizerConfig())
                    ->allowSafeElements()
                    ->allowRelativeLinks()
                    ->allowRelativeMedias()
                    ->allowLinkSchemes(['https', 'http', 'mailto'])
                    ->allowMediaSchemes(['https', 'http', 'data']);

                return (new HtmlSanitizer($config))->sanitize($this->content);
            }
        );
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
