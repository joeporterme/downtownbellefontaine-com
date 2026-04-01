@extends('business.layout')

@section('title', 'Submit Event')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-theme-primary mb-6">Submit an Event</h1>

    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="bg-theme-primary shadow border border-theme rounded-lg p-6">
        @csrf

        <div class="space-y-6">
            {{-- Event Details --}}
            <div>
                <label for="title" class="block text-sm font-medium text-theme-secondary">Event Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                @error('title')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-theme-secondary">Description</label>
                <textarea name="description" id="description" rows="5"
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="featured_image" class="block text-sm font-medium text-theme-secondary">Featured Image</label>
                <p class="text-xs text-theme-tertiary mb-2">Will be cropped to 1920x1080 (16:9 aspect ratio)</p>
                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                    class="mt-1 block w-full text-theme-secondary file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-100 dark:file:bg-primary-900 file:text-primary-700 dark:file:text-primary-300 hover:file:bg-primary-200 dark:hover:file:bg-primary-800">
                @error('featured_image')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            @if($businesses->isNotEmpty())
            <div>
                <label for="business_id" class="block text-sm font-medium text-theme-secondary">Associated Business</label>
                <select name="business_id" id="business_id"
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                    <option value="">-- None --</option>
                    @foreach($businesses as $business)
                        <option value="{{ $business->id }}" {{ old('business_id') == $business->id ? 'selected' : '' }}>
                            {{ $business->name }}
                        </option>
                    @endforeach
                </select>
                @error('business_id')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <hr class="my-6 border-theme">
            <h3 class="text-lg font-medium text-theme-primary">Date & Time</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="event_date" class="block text-sm font-medium text-theme-secondary">Event Date *</label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" required min="{{ date('Y-m-d') }}"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                    @error('event_date')
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-theme-secondary">Start Time</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                    @error('start_time')
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-theme-secondary">End Time</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                    @error('end_time')
                        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="my-6 border-theme">
            <h3 class="text-lg font-medium text-theme-primary">Location</h3>

            <div>
                <label for="location_name" class="block text-sm font-medium text-theme-secondary">Venue/Location Name</label>
                <input type="text" name="location_name" id="location_name" value="{{ old('location_name') }}"
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                @error('location_name')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address_autocomplete" class="block text-sm font-medium text-theme-secondary">Search Address</label>
                <input type="text" id="address_autocomplete" placeholder="Start typing an address..."
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-theme-secondary">Street Address</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                @error('address')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-theme-secondary">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', 'Bellefontaine') }}"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                </div>
                <div>
                    <label for="state" class="block text-sm font-medium text-theme-secondary">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state', 'OH') }}" maxlength="2"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                </div>
                <div>
                    <label for="zip" class="block text-sm font-medium text-theme-secondary">ZIP</label>
                    <input type="text" name="zip" id="zip" value="{{ old('zip') }}"
                        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                </div>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

            <hr class="my-6 border-theme">
            <h3 class="text-lg font-medium text-theme-primary">Additional Info</h3>

            <div>
                <label for="more_info_url" class="block text-sm font-medium text-theme-secondary">More Info URL</label>
                <input type="url" name="more_info_url" id="more_info_url" value="{{ old('more_info_url') }}" placeholder="https://..."
                    class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
                @error('more_info_url')
                    <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('business.events.index') }}" class="px-4 py-2 border border-theme rounded-md text-theme-secondary hover:bg-theme-secondary transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                Submit for Approval
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let autocomplete;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address_autocomplete'),
            {
                types: ['address'],
                componentRestrictions: { country: 'us' }
            }
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();

        if (!place.geometry) {
            return;
        }

        // Set lat/lng
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();

        // Parse address components
        let streetNumber = '';
        let route = '';

        for (const component of place.address_components) {
            const type = component.types[0];

            switch (type) {
                case 'street_number':
                    streetNumber = component.long_name;
                    break;
                case 'route':
                    route = component.long_name;
                    break;
                case 'locality':
                    document.getElementById('city').value = component.long_name;
                    break;
                case 'administrative_area_level_1':
                    document.getElementById('state').value = component.short_name;
                    break;
                case 'postal_code':
                    document.getElementById('zip').value = component.long_name;
                    break;
            }
        }

        document.getElementById('address').value = streetNumber + ' ' + route;

        // If location_name is empty, use the place name
        if (!document.getElementById('location_name').value && place.name) {
            document.getElementById('location_name').value = place.name;
        }
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete"></script>
@endpush
@endsection
