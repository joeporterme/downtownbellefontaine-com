<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Event;
use App\Models\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Build an XML sitemap of the static pages plus approved businesses and events.
     */
    public function index()
    {
        $urls = [];

        // Published CMS pages (drops any set to draft).
        Page::published()->orderBy('sort')->get(['key', 'updated_at'])->each(function ($page) use (&$urls) {
            if ($page->key === 'home') {
                $loc = url('/');
            } elseif (Route::has("pages.{$page->key}")) {
                $loc = route("pages.{$page->key}");
            } else {
                return; // no matching route (e.g. an admin-created page without a blade)
            }

            $urls[] = [
                'loc' => $loc,
                'lastmod' => $page->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => $page->key === 'home' ? '1.0' : '0.7',
            ];
        });

        // Dynamic index routes not backed by a Page record.
        foreach (['businesses.index', 'events.index', 'blog.index'] as $name) {
            $urls[] = ['loc' => route($name), 'changefreq' => 'weekly', 'priority' => '0.6'];
        }

        Business::approved()->get(['slug', 'updated_at'])->each(function ($b) use (&$urls) {
            $urls[] = [
                'loc' => route('businesses.show', $b->slug),
                'lastmod' => $b->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        });

        Event::approved()->where('event_date', '>=', Carbon::today()->subMonth())
            ->get(['slug', 'updated_at'])->each(function ($e) use (&$urls) {
                $urls[] = [
                    'loc' => route('events.show', $e->slug),
                    'lastmod' => $e->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ];
            });

        \App\Models\BlogPost::published()->get(['slug', 'updated_at'])->each(function ($p) use (&$urls) {
            $urls[] = [
                'loc' => route('blog.show', $p->slug),
                'lastmod' => $p->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
