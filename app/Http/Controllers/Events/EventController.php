<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::approved()
            ->upcoming()
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }

        return view('events.show', compact('event'));
    }

    public function create()
    {
        $businesses = auth()->user()->businesses ?? collect();

        return view('events.create', compact('businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
            'business_id' => 'nullable|exists:businesses,id',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'more_info_url' => 'nullable|url|max:255',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle image upload with resizing
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->processImage($request->file('featured_image'));
        }

        $validated['submitted_by'] = auth()->id();
        $validated['status'] = 'pending';

        // Verify business belongs to user if specified
        if (!empty($validated['business_id'])) {
            $business = Business::find($validated['business_id']);
            if (!$business || $business->user_id !== auth()->id()) {
                unset($validated['business_id']);
            }
        }

        Event::create($validated);

        return redirect()->route('business.events.index')
            ->with('success', 'Event submitted successfully! It will be visible after admin approval.');
    }

    public function edit(Event $event)
    {
        // Ensure user owns this event
        if ($event->submitted_by !== auth()->id()) {
            abort(403);
        }

        $businesses = auth()->user()->businesses ?? collect();

        return view('events.edit', compact('event', 'businesses'));
    }

    public function update(Request $request, Event $event)
    {
        // Ensure user owns this event
        if ($event->submitted_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
            'business_id' => 'nullable|exists:businesses,id',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'more_info_url' => 'nullable|url|max:255',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Handle image upload with resizing
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $validated['featured_image'] = $this->processImage($request->file('featured_image'));
        }

        // Verify business belongs to user if specified
        if (!empty($validated['business_id'])) {
            $business = Business::find($validated['business_id']);
            if (!$business || $business->user_id !== auth()->id()) {
                unset($validated['business_id']);
            }
        }

        // Reset to pending if significant changes were made
        if ($event->status === 'approved') {
            $validated['status'] = 'pending';
            $validated['approved_at'] = null;
            $validated['approved_by'] = null;
        }

        $event->update($validated);

        return redirect()->route('business.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        // Ensure user owns this event
        if ($event->submitted_by !== auth()->id()) {
            abort(403);
        }

        // Delete image
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('business.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function myEvents()
    {
        $events = Event::where('submitted_by', auth()->id())
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('events.my-events', compact('events'));
    }

    protected function processImage($file): string
    {
        $filename = 'events/' . uniqid() . '.jpg';

        $image = Image::read($file);

        // Resize and crop to 1920x1080 (16:9)
        $image->cover(1920, 1080);

        // Save with compression (quality 80)
        $image->toJpeg(80);

        Storage::disk('public')->put($filename, (string) $image->encode());

        return $filename;
    }
}
