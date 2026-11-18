<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Store (POST) is open to any authenticated owner; update (PUT/PATCH) is
     * restricted to the event's submitter.
     */
    public function authorize(): bool
    {
        $event = $this->route('event');

        return $event === null || $event->submitted_by === $this->user()?->id;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
            'business_id' => 'nullable|exists:businesses,id',
            // New events can't be back-dated; edits may touch past events.
            'event_date' => $this->isMethod('post') ? 'required|date|after_or_equal:today' : 'required|date',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'start_time' => 'nullable|date_format:H:i',
            // On a multi-day event the end time is the last day's, so it can be earlier.
            'end_time' => $this->filled('end_date') && $this->input('end_date') !== $this->input('event_date')
                ? 'nullable|date_format:H:i'
                : 'nullable|date_format:H:i|after:start_time',
            'schedule' => 'nullable|string|max:1000',
            'more_info_url' => 'nullable|url|max:255',
            'location_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }
}
