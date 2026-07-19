<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Business;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'featuredBusinesses' => $this->featuredBusinesses(),
            'upcomingEvents' => Event::approved()
                ->upcoming()
                ->orderBy('event_date')
                ->take(3)
                ->get(),
            'latestPosts' => BlogPost::published()
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
        ]);
    }

    /**
     * Up to 6 approved businesses, preferring those with a photo, padded with
     * others if needed. Eager-loads the relations the cards read (categories +
     * locations) to avoid an N+1 across the grid.
     */
    protected function featuredBusinesses()
    {
        $withRelations = fn ($query) => $query->with(['primaryLocation', 'categories', 'locations']);

        $featured = $withRelations(Business::approved())
            ->where(function ($q) {
                $q->whereNotNull('featured_image')
                    ->orWhereHas('primaryLocation', fn ($q) => $q->whereNotNull('streetview_image'));
            })
            ->inRandomOrder()
            ->take(6)
            ->get();

        if ($featured->count() < 6) {
            $featured = $featured->merge(
                $withRelations(Business::approved())
                    ->whereNotIn('id', $featured->pluck('id'))
                    ->inRandomOrder()
                    ->take(6 - $featured->count())
                    ->get()
            );
        }

        return $featured;
    }
}
