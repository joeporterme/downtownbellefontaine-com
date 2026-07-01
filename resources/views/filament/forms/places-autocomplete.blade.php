<?php
    // The View lives inside a Repeater item, so $getStatePath() returns the
    // Repeater item's dotted path (e.g. "data.locations.abc123"). Sibling
    // Hidden fields live at $itemPath.<field>, and we update them via
    // $wire.set() from Alpine below.
    $itemPath = $getStatePath();
?>

<div
    x-data="placesAutocomplete({
        statePath: @js($itemPath),
    })"
    x-init="init()"
    class="col-span-2"
>
    <label class="block text-sm font-medium leading-6 text-gray-950 dark:text-white">
        Business Address <span class="text-danger-600">*</span>
    </label>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-2">
        Start typing and pick from the dropdown &mdash; we&rsquo;ll fill in the rest.
    </p>

    <div class="fi-input-wrp flex items-center rounded-lg bg-white shadow-sm ring-1 ring-inset ring-gray-950/10 dark:bg-white/5 dark:ring-white/20 focus-within:ring-2 focus-within:ring-primary-500">
        <input
            type="text"
            x-ref="searchInput"
            x-model="searchValue"
            @keydown.enter.prevent
            placeholder="e.g., 100 N Main St, Bellefontaine, OH"
            autocomplete="off"
            class="w-full border-0 bg-transparent py-1.5 px-3 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6"
        />
    </div>

    <p x-show="showWarning" x-cloak class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">
        Please pick an address from the dropdown so we can save the coordinates.
    </p>

    <template x-if="picked">
        <div class="mt-3 flex items-start gap-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4">
            <svg class="w-6 h-6 text-primary-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1 min-w-0">
                <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-semibold mb-1">Selected Address</p>
                <p class="font-semibold text-gray-950 dark:text-white" x-text="address"></p>
                <p class="text-sm text-gray-600 dark:text-gray-300" x-text="cityStateZip"></p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="coordsLabel"></p>
            </div>
            <button type="button" @click="clear()" class="text-xs text-gray-500 hover:text-danger-600 transition-colors">
                Clear
            </button>
        </div>
    </template>
</div>

@assets
<script>
    window.placesAutocomplete = function ({ statePath }) {
            return {
                statePath,
                searchValue: '',
                picked: false,
                showWarning: false,
                address: '',
                city: '',
                state: '',
                zip: '',
                lat: '',
                lng: '',

                get cityStateZip() {
                    const cs = [this.city, this.state].filter(Boolean).join(', ');
                    return this.zip ? `${cs} ${this.zip}` : cs;
                },
                get coordsLabel() {
                    if (!this.lat || !this.lng) return '';
                    return `Coordinates: ${Number(this.lat).toFixed(6)}, ${Number(this.lng).toFixed(6)}`;
                },

                init() {
                    // Read existing state (edit mode)
                    const item = this.$wire.get(this.statePath) || {};
                    this.address = item.address || '';
                    this.city = item.city || '';
                    this.state = item.state || '';
                    this.zip = item.zip || '';
                    this.lat = item.latitude || '';
                    this.lng = item.longitude || '';

                    if (this.address || (this.lat && this.lng)) {
                        this.picked = true;
                        this.searchValue = [this.address, this.city, this.state].filter(Boolean).join(', ');
                    }

                    this.attachAutocomplete();

                    // If the user retypes after picking, clear coords so a stale
                    // lat/lng doesn't sneak through.
                    this.$refs.searchInput.addEventListener('input', () => {
                        if (this.lat) {
                            this.picked = false;
                            this.address = '';
                            this.city = '';
                            this.state = '';
                            this.zip = '';
                            this.lat = '';
                            this.lng = '';
                            this.pushToWire();
                        }
                    });
                },

                attachAutocomplete(retries = 20) {
                    if (!window.google || !google.maps || !google.maps.places) {
                        if (retries <= 0) return;
                        setTimeout(() => this.attachAutocomplete(retries - 1), 250);
                        return;
                    }

                    const ac = new google.maps.places.Autocomplete(this.$refs.searchInput, {
                        fields: ['address_components', 'geometry', 'formatted_address'],
                        types: ['geocode'],
                        componentRestrictions: { country: 'us' },
                    });

                    ac.addListener('place_changed', () => {
                        const place = ac.getPlace();
                        if (!place || !place.geometry) return;
                        this.applyPlace(place);
                        this.showWarning = false;
                    });
                },

                applyPlace(place) {
                    const c = {};
                    (place.address_components || []).forEach((comp) => {
                        comp.types.forEach((t) => {
                            c[t] = comp.long_name;
                            c[t + '_short'] = comp.short_name;
                        });
                    });

                    this.address = [c.street_number || '', c.route || ''].filter(Boolean).join(' ');
                    this.city = c.locality
                        || c.sublocality_level_1
                        || c.sublocality
                        || c.administrative_area_level_3
                        || '';
                    this.state = c.administrative_area_level_1_short || '';
                    this.zip = c.postal_code || '';
                    this.lat = place.geometry.location.lat();
                    this.lng = place.geometry.location.lng();

                    this.picked = true;
                    this.searchValue = place.formatted_address
                        || [this.address, this.city, this.state].filter(Boolean).join(', ');

                    this.pushToWire();
                },

                pushToWire() {
                    // Deferred = true batches these into a single roundtrip when
                    // Livewire next syncs (e.g. on save or field blur).
                    this.$wire.set(`${this.statePath}.address`, this.address, true);
                    this.$wire.set(`${this.statePath}.city`, this.city, true);
                    this.$wire.set(`${this.statePath}.state`, this.state, true);
                    this.$wire.set(`${this.statePath}.zip`, this.zip, true);
                    this.$wire.set(`${this.statePath}.latitude`, this.lat, true);
                    this.$wire.set(`${this.statePath}.longitude`, this.lng, true);
                },

                clear() {
                    this.searchValue = '';
                    this.picked = false;
                    this.address = '';
                    this.city = '';
                    this.state = '';
                    this.zip = '';
                    this.lat = '';
                    this.lng = '';
                    this.pushToWire();
                    this.$refs.searchInput.focus();
                },
            };
        };
</script>
@endassets
