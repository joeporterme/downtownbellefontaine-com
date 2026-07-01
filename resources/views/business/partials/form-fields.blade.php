{{-- Business Information --}}
<div>
    <label for="name" class="block text-sm font-medium text-theme-secondary">Business Name *</label>
    <input type="text" name="name" id="name" value="{{ old('name', $business->name ?? '') }}" required
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    @error('name')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-theme-secondary">Description</label>
    <textarea name="description" id="description" rows="4"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">{{ old('description', $business->description ?? '') }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

{{-- Categories --}}
<div>
    <label class="block text-sm font-medium text-theme-secondary mb-2">Categories</label>
    <div class="grid grid-cols-2 gap-2">
        @foreach($categories as $category)
            <label class="flex items-center">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                    {{ in_array($category->id, old('categories', isset($business) ? $business->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                    class="rounded border-theme text-primary-600 focus:ring-primary-500">
                <span class="ml-2 text-sm text-theme-secondary">{{ $category->name }}</span>
            </label>
        @endforeach
    </div>
</div>

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Location</h3>

@php
    $existingAddress = old('address', $business->address ?? '');
    $existingCity = old('city', $business->city ?? 'Bellefontaine');
    $existingState = old('state', $business->state ?? 'OH');
    $existingZip = old('zip', $business->zip ?? '');
    $existingPrimary = isset($business)
        ? ($business->locations->firstWhere('is_primary', true) ?? $business->locations->first())
        : null;
    $existingLat = old('latitude', $existingPrimary?->latitude ?? '');
    $existingLng = old('longitude', $existingPrimary?->longitude ?? '');
    $searchDefault = trim(collect([$existingAddress, $existingCity, $existingState])->filter()->implode(', '));
@endphp

<div>
    <label for="address_search" class="block text-sm font-medium text-theme-secondary">
        <i class="fa-duotone fa-light fa-magnifying-glass text-primary-500 mr-1"></i>
        Business Address <span class="text-danger-500">*</span>
    </label>
    <p class="text-xs text-theme-tertiary mb-2">Start typing and select from the dropdown — we'll fill in the rest.</p>
    <input type="text" id="address_search" data-places-input
        value="{{ $searchDefault }}"
        placeholder="e.g., 100 N Main St, Bellefontaine, OH"
        autocomplete="off"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    <p class="hidden mt-1.5 text-sm text-danger-600 dark:text-danger-400" data-places-warning>
        <i class="fa-duotone fa-light fa-circle-exclamation mr-1"></i>
        Please pick an address from the dropdown so we can save the location on the map.
    </p>
    @error('address')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
    @error('latitude')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

{{-- Preview of the selected address (populated by JS) --}}
<div data-address-preview class="{{ $existingAddress ? '' : 'hidden' }} bg-theme-secondary border border-theme rounded-lg p-4 flex items-start gap-3">
    <i class="fa-duotone fa-light fa-location-dot text-2xl text-accent-500 mt-0.5 flex-shrink-0"></i>
    <div class="flex-1 min-w-0">
        <p class="text-xs uppercase tracking-wider text-theme-tertiary font-semibold mb-1">Selected Address</p>
        <p class="font-semibold text-theme-primary" data-preview-line-1>{{ $existingAddress }}</p>
        <p class="text-sm text-theme-secondary" data-preview-line-2>{{ trim($existingCity . ($existingState ? ', ' . $existingState : '') . ($existingZip ? ' ' . $existingZip : '')) }}</p>
        <p class="text-xs text-theme-tertiary mt-1" data-preview-coords>{{ $existingLat && $existingLng ? 'Coordinates: ' . number_format((float) $existingLat, 6) . ', ' . number_format((float) $existingLng, 6) : '' }}</p>
    </div>
    <button type="button" data-clear-address class="text-xs text-theme-tertiary hover:text-danger-500 transition-colors">
        <i class="fa-duotone fa-light fa-xmark"></i>
        Clear
    </button>
</div>

{{-- Hidden fields populated by the Places autocomplete --}}
<input type="hidden" name="address" id="address" value="{{ $existingAddress }}">
<input type="hidden" name="city" id="city" value="{{ $existingCity }}">
<input type="hidden" name="state" id="state" value="{{ $existingState }}">
<input type="hidden" name="zip" id="zip" value="{{ $existingZip }}">
<input type="hidden" name="latitude" id="latitude" value="{{ $existingLat }}">
<input type="hidden" name="longitude" id="longitude" value="{{ $existingLng }}">
<input type="hidden" name="place_id" id="place_id" value="">
<input type="hidden" name="formatted_address" id="formatted_address" value="">

@push('scripts')
<script>
    (function() {
        function initPlacesAutocomplete() {
            const input = document.querySelector('[data-places-input]');
            if (!input || !window.google || !google.maps || !google.maps.places) return;

            const autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ['address_components', 'geometry', 'place_id', 'formatted_address'],
                types: ['geocode'],
                componentRestrictions: { country: 'us' },
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (!place || !place.geometry) return;
                applyPlace(place);
                hideWarning();
            });

            // Prevent the enter key from submitting the form while the dropdown is open
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && document.querySelector('.pac-container:not(:empty)')) {
                    e.preventDefault();
                }
            });

            // Reset hidden coords if the user edits the search after picking a place
            input.addEventListener('input', function() {
                if (document.getElementById('latitude').value) {
                    // They picked something and are now typing again — treat as reset
                    clearHidden();
                }
            });

            // Clear button
            const clearBtn = document.querySelector('[data-clear-address]');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    input.value = '';
                    clearHidden();
                    document.querySelector('[data-address-preview]').classList.add('hidden');
                    hideWarning();
                    input.focus();
                });
            }

            // Block submit if the user typed but didn't pick a dropdown result
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const typedText = input.value.trim();
                    const hasCoords = document.getElementById('latitude').value !== '';
                    if (typedText && !hasCoords) {
                        e.preventDefault();
                        showWarning();
                        input.focus();
                    }
                });
            }
        }

        function applyPlace(place) {
            const components = {};
            (place.address_components || []).forEach(function(c) {
                c.types.forEach(function(t) {
                    components[t] = c.long_name;
                    components[t + '_short'] = c.short_name;
                });
            });

            const streetNumber = components.street_number || '';
            const route = components.route || '';
            const address = [streetNumber, route].filter(Boolean).join(' ');
            const city = components.locality
                || components.sublocality_level_1
                || components.sublocality
                || components.administrative_area_level_3
                || '';
            const state = components.administrative_area_level_1_short || '';
            const zip = components.postal_code || '';
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            document.getElementById('address').value = address;
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            document.getElementById('zip').value = zip;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('place_id').value = place.place_id || '';
            document.getElementById('formatted_address').value = place.formatted_address || '';

            updatePreview(address, city, state, zip, lat, lng);
        }

        function updatePreview(address, city, state, zip, lat, lng) {
            const preview = document.querySelector('[data-address-preview]');
            document.querySelector('[data-preview-line-1]').textContent = address;
            document.querySelector('[data-preview-line-2]').textContent =
                (city ? city + (state ? ', ' + state : '') : state) + (zip ? ' ' + zip : '');
            const coords = document.querySelector('[data-preview-coords]');
            if (lat && lng) {
                coords.textContent = 'Coordinates: ' + Number(lat).toFixed(6) + ', ' + Number(lng).toFixed(6);
            } else {
                coords.textContent = '';
            }
            preview.classList.remove('hidden');
        }

        function clearHidden() {
            document.getElementById('address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('zip').value = '';
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('place_id').value = '';
            document.getElementById('formatted_address').value = '';
        }

        function showWarning() {
            const w = document.querySelector('[data-places-warning]');
            if (w) w.classList.remove('hidden');
        }

        function hideWarning() {
            const w = document.querySelector('[data-places-warning]');
            if (w) w.classList.add('hidden');
        }

        window.initPlacesAutocomplete = initPlacesAutocomplete;
    })();
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initPlacesAutocomplete&loading=async"></script>
@endpush

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Contact Information</h3>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="phone" class="block text-sm font-medium text-theme-secondary">Phone</label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone', $business->phone ?? '') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('phone')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-theme-secondary">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $business->email ?? '') }}"
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('email')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<div>
    <label for="website" class="block text-sm font-medium text-theme-secondary">Website</label>
    <input type="url" name="website" id="website" value="{{ old('website', $business->website ?? '') }}" placeholder="https://"
        class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
    @error('website')
        <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
    @enderror
</div>

<hr class="my-6 border-theme">
<h3 class="text-lg font-medium text-theme-primary">Social Media</h3>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="facebook_url" class="block text-sm font-medium text-theme-secondary">Facebook</label>
        <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $business->facebook_url ?? '') }}" placeholder="https://facebook.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('facebook_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="instagram_url" class="block text-sm font-medium text-theme-secondary">Instagram</label>
        <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $business->instagram_url ?? '') }}" placeholder="https://instagram.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('instagram_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="tiktok_url" class="block text-sm font-medium text-theme-secondary">TikTok</label>
        <input type="url" name="tiktok_url" id="tiktok_url" value="{{ old('tiktok_url', $business->tiktok_url ?? '') }}" placeholder="https://tiktok.com/@..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('tiktok_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="snapchat_url" class="block text-sm font-medium text-theme-secondary">Snapchat</label>
        <input type="url" name="snapchat_url" id="snapchat_url" value="{{ old('snapchat_url', $business->snapchat_url ?? '') }}" placeholder="https://snapchat.com/add/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('snapchat_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="x_url" class="block text-sm font-medium text-theme-secondary">X (Twitter)</label>
        <input type="url" name="x_url" id="x_url" value="{{ old('x_url', $business->x_url ?? '') }}" placeholder="https://x.com/..."
            class="mt-1 block w-full rounded-md border-theme bg-theme-primary text-theme-primary shadow-sm focus:border-primary-500 focus:ring-primary-500 px-3 py-2 border">
        @error('x_url')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>
</div>
