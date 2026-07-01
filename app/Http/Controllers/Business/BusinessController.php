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
        $validated = $this->validateBusinessInput($request);

        [$businessData, $locationData, $categories] = $this->splitInput($validated);

        $businessData['user_id'] = auth()->id();
        $businessData['slug'] = Str::slug($businessData['name']);
        $businessData['status'] = 'pending';

        $business = Business::create($businessData);

        if (!empty($categories)) {
            $business->categories()->attach($categories);
        }

        $this->syncPrimaryLocation($business, $locationData);

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

        $validated = $this->validateBusinessInput($request);

        [$businessData, $locationData, $categories] = $this->splitInput($validated);

        $business->update($businessData);
        $business->categories()->sync($categories);

        $this->syncPrimaryLocation($business, $locationData);

        return redirect()
            ->route('business.dashboard')
            ->with('success', 'Business information updated successfully!');
    }

    protected function validateBusinessInput(Request $request): array
    {
        return $request->validate([
            'name' => 'required|min:2|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'place_id' => 'nullable|string|max:255',
            'formatted_address' => 'nullable|string|max:500',
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
    }

    /**
     * Split the validated payload into the pieces each destination needs.
     */
    protected function splitInput(array $validated): array
    {
        $categories = $validated['categories'] ?? [];

        $locationData = [
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'zip' => $validated['zip'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ];

        // These four aren't columns on either businesses or business_locations —
        // they're carried through from the Places response for possible future use
        // but not persisted yet. Drop them so Business::create doesn't complain.
        unset(
            $validated['categories'],
            $validated['latitude'],
            $validated['longitude'],
            $validated['place_id'],
            $validated['formatted_address'],
        );

        return [$validated, $locationData, $categories];
    }

    /**
     * Ensure the business has exactly one primary BusinessLocation reflecting
     * the address the owner just entered. Only run when we actually have an
     * address or coordinates to write.
     */
    protected function syncPrimaryLocation(Business $business, array $data): void
    {
        $hasAddress = !empty($data['address']);
        $hasCoords = !empty($data['latitude']) && !empty($data['longitude']);
        if (!$hasAddress && !$hasCoords) {
            return;
        }

        $payload = [
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?: 'Bellefontaine',
            'state' => $data['state'] ?: 'OH',
            'zip' => $data['zip'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'is_primary' => true,
            'is_active' => true,
        ];

        $primary = $business->locations()->where('is_primary', true)->first();
        if ($primary) {
            $primary->update($payload);
        } else {
            $business->locations()->create($payload);
        }
    }
}
