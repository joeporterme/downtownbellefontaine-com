<div
    x-data="{
        init() {
            this.initAutocomplete();
        },
        initAutocomplete() {
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                // Load Google Maps script if not already loaded
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places';
                script.async = true;
                script.defer = true;
                script.onload = () => this.setupAutocomplete();
                document.head.appendChild(script);
            } else {
                this.setupAutocomplete();
            }
        },
        setupAutocomplete() {
            const input = this.$refs.autocompleteInput;
            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address'],
                componentRestrictions: { country: 'us' }
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    return;
                }

                // Set lat/lng
                $wire.set('data.latitude', place.geometry.location.lat());
                $wire.set('data.longitude', place.geometry.location.lng());

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
                            $wire.set('data.city', component.long_name);
                            break;
                        case 'administrative_area_level_1':
                            $wire.set('data.state', component.short_name);
                            break;
                        case 'postal_code':
                            $wire.set('data.zip', component.long_name);
                            break;
                    }
                }

                $wire.set('data.address', (streetNumber + ' ' + route).trim());

                // If location_name is empty, use the place name
                const locationName = $wire.get('data.location_name');
                if (!locationName && place.name) {
                    $wire.set('data.location_name', place.name);
                }
            });
        }
    }"
    class="fi-fo-field-wrp"
>
    <div class="fi-fo-field-wrp-label-ctn">
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                Search Address
            </span>
        </label>
    </div>

    <div class="grid gap-y-2">
        <input
            x-ref="autocompleteInput"
            type="text"
            placeholder="Start typing an address..."
            class="fi-input block w-full border-none bg-transparent py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6 ps-3 pe-3 fi-input-wrp-ctn overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10 focus-within:ring-2 focus-within:ring-primary-600 dark:bg-white/5 dark:ring-white/20 dark:focus-within:ring-primary-500"
        />
        <p class="fi-fo-field-wrp-helper-text text-sm text-gray-500 dark:text-gray-400">
            Search for an address to auto-fill the location fields below
        </p>
    </div>
</div>
