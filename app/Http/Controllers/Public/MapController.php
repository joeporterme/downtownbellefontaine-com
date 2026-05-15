<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $businesses = Business::approved()
            ->with(['categories', 'locations'])
            ->orderBy('name')
            ->get()
            ->map(function (Business $business) {
                $location = $business->locations->firstWhere('is_primary', true)
                    ?? $business->locations->first();

                if (!$location || !$location->latitude || !$location->longitude) {
                    return null;
                }

                return [
                    'id' => $business->id,
                    'name' => $business->name,
                    'url' => route('businesses.show', $business),
                    'image' => $business->featured_image
                        ? Storage::url($business->featured_image)
                        : ($business->logo ? Storage::url($business->logo) : null),
                    'address' => $location->address,
                    'city' => $location->city,
                    'state' => $location->state,
                    'zip' => $location->zip,
                    'phone' => $business->phone ?: $location->phone,
                    'website' => $business->website,
                    'facebook_url' => $business->facebook_url,
                    'instagram_url' => $business->instagram_url,
                    'latitude' => (float) $location->latitude,
                    'longitude' => (float) $location->longitude,
                    'category_ids' => $business->categories->pluck('id')->values(),
                    'category_name' => $business->categories->first()?->name,
                    'is_parking' => $business->categories->contains('slug', 'parking'),
                ];
            })
            ->filter()
            ->values();

        return view('map', compact('businesses', 'categories'));
    }
}
