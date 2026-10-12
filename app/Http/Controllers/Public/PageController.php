<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\PressItem;

class PageController extends Controller
{
    // Static content pages (moved off route closures so `route:cache` works).
    public function planAVisit()
    {
        return view('pages.plan-a-visit');
    }

    public function downtownDays()
    {
        return view('pages.first-fridays');
    }

    public function meetingSpaces()
    {
        return view('pages.meeting-spaces');
    }

    public function dora()
    {
        return view('pages.dora');
    }

    public function revitalization()
    {
        return view('pages.revitalization');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function termsOfService()
    {
        return view('pages.terms-of-service');
    }

    public function media()
    {
        $pressItems = PressItem::active()
            ->orderByDesc('published_date')
            ->get()
            ->groupBy(fn ($item) => $item->published_date->format('Y'));

        return view('pages.media', compact('pressItems'));
    }

    public function placesToShop()
    {
        $businesses = $this->businessesForCategory('shopping');

        return view('pages.places-to-shop', compact('businesses'));
    }

    public function thingsToDo()
    {
        $businesses = $this->businessesForCategory('things-to-do');

        return view('pages.things-to-do', compact('businesses'));
    }

    public function foodDrinks()
    {
        $businesses = $this->businessesForCategory('food-drink');

        return view('pages.food-drinks', compact('businesses'));
    }

    public function stay()
    {
        $businesses = $this->businessesForCategory('hotel-lodging');

        return view('pages.stay', compact('businesses'));
    }

    private function businessesForCategory(string $slug)
    {
        $category = BusinessCategory::where('slug', $slug)->first();

        if (!$category) {
            return collect();
        }

        return Business::approved()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('business_categories.id', $category->id);
            })
            ->with(['categories', 'locations', 'primaryLocation'])
            ->orderBy('name')
            ->get();
    }
}
