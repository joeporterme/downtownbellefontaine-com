<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Business;
use App\Mail\EventSubmission;
use App\Models\Event;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::approved()
            ->upcoming()
            ->with('business')
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

    public function store(EventRequest $request)
    {
        $validated = $request->validated();

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

        $event = Event::create($validated);

        $this->notifyReviewers($event);

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

    public function update(EventRequest $request, Event $event)
    {
        // Ownership is enforced by EventRequest::authorize().
        $validated = $request->validated();

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
        $wasApproved = $event->status === 'approved';
        if ($wasApproved) {
            $validated['status'] = 'pending';
            $validated['approved_at'] = null;
            $validated['approved_by'] = null;
        }

        $event->update($validated);

        // An approved event just dropped off the calendar; let reviewers know.
        if ($wasApproved) {
            $this->notifyReviewers($event, isUpdate: true);
        }

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

    /**
     * Email the reviewers set in Admin › Site Settings › Event Submissions.
     * Mail failures are logged, never shown to the owner: the pending event in
     * the admin is the source of truth.
     */
    protected function notifyReviewers(Event $event, bool $isUpdate = false): void
    {
        try {
            $recipients = SiteSetting::current()->eventRecipients();
            if ($recipients) {
                Mail::to($recipients)->send(new EventSubmission($event->load('business', 'submittedBy'), $isUpdate));
            }
        } catch (\Throwable $e) {
            Log::warning('Event submission mail dispatch failed', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function processImage($file): string
    {
        $filename = 'events/' . uniqid() . '.jpg';

        $image = Image::read($file);

        // Resize and crop to 1920x1080 (16:9)
        $image->cover(1920, 1080);

        // Save with compression (quality 80)
        $encoded = $image->toJpeg(80);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}
