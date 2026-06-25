<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Event;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    /**
     * Build an XML sitemap of the static pages plus approved businesses and events.
     */
    public function index()
    {
        $urls = [];

        // Static, indexable pages.
        $staticRoutes = [
            'pages.places-to-shop', 'pages.food-drinks', 'pages.stay',
            'pages.things-to-do', 'events.index', 'pages.plan-a-visit',
            'pages.map', 'pages.first-fridays', 'pages.meeting-spaces',
            'pages.dora', 'pages.media', 'pages.contact',
            'pages.historic-walking-tour', 'pages.privacy-policy',
            'businesses.index',
        ];

        $urls[] = ['loc' => url('/'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        foreach ($staticRoutes as $name) {
            $urls[] = ['loc' => route($name), 'changefreq' => 'weekly', 'priority' => '0.7'];
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

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
