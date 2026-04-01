<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function create()
    {
        $categories = BusinessCategory::active()->ordered()->get();
        return view('business.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'snapchat_url' => 'nullable|url|max:255',
            'x_url' => 'nullable|url|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:business_categories,id',
        ]);

        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'pending';

        $business = Business::create($validated);

        if (!empty($categories)) {
            $business->categories()->attach($categories);
        }

        return redirect()
            ->route('business.dashboard')
            ->with('success', 'Your business has been submitted for approval!');
    }

    public function edit(Business $business)
    {
        $this->authorize('update', $business);

        $categories = BusinessCategory::active()->ordered()->get();
        $business->load(['categories', 'locations']);

        return view('business.edit', compact('business', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $validated = $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'snapchat_url' => 'nullable|url|max:255',
            'x_url' => 'nullable|url|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:business_categories,id',
        ]);

        $categories = $validated['categories'] ?? [];
        unset($validated['categories']);

        $business->update($validated);
        $business->categories()->sync($categories);

        return redirect()
            ->route('business.dashboard')
            ->with('success', 'Business information updated successfully!');
    }
}
