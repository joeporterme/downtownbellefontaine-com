<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    protected $fillable = [
        'from_path',
        'to_url',
        'status_code',
        'match_type',
        'priority',
        'is_active',
        'hits',
        'last_hit_at',
        'notes',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'hits' => 'integer',
        'last_hit_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // The matcher caches a compiled map; bust it whenever a rule changes.
        static::saved(fn () => Cache::forget('redirects.map'));
        static::deleted(fn () => Cache::forget('redirects.map'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Normalize an incoming request path for matching:
     * leading slash, no trailing slash (except root), no query string, lower-cased.
     */
    public static function normalize(?string $path): string
    {
        $path = (string) $path;
        $path = strtok($path, '?');          // drop any query string
        $path = '/'.ltrim($path, '/');       // ensure single leading slash
        $path = rtrim($path, '/');           // strip trailing slash

        return strtolower($path === '' ? '/' : $path);
    }

    /**
     * Compiled, cached matching map: exact lookups keyed by from_path,
     * plus an ordered list of active pattern rules.
     */
    public static function map(): array
    {
        return Cache::rememberForever('redirects.map', function () {
            $exact = [];
            $patterns = [];

            foreach (static::query()->where('is_active', true)->get() as $r) {
                $row = [
                    'id' => $r->id,
                    'from' => $r->from_path,
                    'to' => $r->to_url,
                    'status' => $r->status_code,
                    'priority' => $r->priority,
                ];

                if ($r->match_type === 'pattern') {
                    $patterns[] = $row;
                } else {
                    $exact[static::normalize($r->from_path)] = $row;
                }
            }

            // Higher priority evaluated first (more specific patterns before catch-alls).
            usort($patterns, fn ($a, $b) => ($b['priority'] <=> $a['priority']));

            return ['exact' => $exact, 'patterns' => $patterns];
        });
    }

    /**
     * Resolve a normalized path to a redirect rule, or null if none matches.
     * Returns ['id','to','status'] where 'to' is the (substituted) target.
     */
    public static function resolve(string $normalizedPath): ?array
    {
        $map = static::map();

        if (isset($map['exact'][$normalizedPath])) {
            $hit = $map['exact'][$normalizedPath];

            return ['id' => $hit['id'], 'to' => $hit['to'], 'status' => $hit['status']];
        }

        foreach ($map['patterns'] as $rule) {
            $delimited = '#'.str_replace('#', '\#', $rule['from']).'#i';

            if (@preg_match($delimited, $normalizedPath) === 1) {
                $to = $rule['to'] !== null
                    ? preg_replace($delimited, $rule['to'], $normalizedPath)
                    : null;

                return ['id' => $rule['id'], 'to' => $to, 'status' => $rule['status']];
            }
        }

        return null;
    }
}
