<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class DayAgenda extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'pdf_url',
        'sort',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'sort' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (DayAgenda $agenda) {
            if (empty($agenda->slug)) {
                $agenda->slug = static::generateUniqueSlug($agenda->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Itinerary body sanitized for safe HTML output — content comes from the
     * Filament rich editor (trusted) and the WordPress importer (untrusted), so
     * sanitize on render exactly like BlogPost::safeContent().
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
