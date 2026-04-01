<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::approved()
            ->with(['categories', 'locations'])
            ->orderBy('name')
            ->paginate(24);

        $categories = BusinessCategory::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories'));
    }

    public function show(Business $business)
    {
        if ($business->status !== 'approved') {
            abort(404);
        }

        $business->load(['categories', 'locations', 'events' => function ($query) {
            $query->approved()->upcoming()->orderBy('event_date')->limit(5);
        }]);

        return view('businesses.show', compact('business'));
    }

    public function category(BusinessCategory $category)
    {
        $businesses = Business::approved()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('business_categories.id', $category->id);
            })
            ->with(['categories', 'locations'])
            ->orderBy('name')
            ->paginate(24);

        $categories = BusinessCategory::orderBy('name')->get();

        return view('businesses.index', compact('businesses', 'categories', 'category'));
    }
}
