<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;

class PageController extends Controller
{
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
            ->with(['categories', 'locations'])
            ->orderBy('name')
            ->get();
    }
}
