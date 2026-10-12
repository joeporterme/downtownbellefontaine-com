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
        ];
    }
}
