<?php
    // The View lives inside a Repeater item, so $getStatePath() returns the
    // Repeater item's dotted path (e.g. "data.locations.abc123"). Sibling
    // Hidden fields live at $itemPath.<field>, and we update them via
    // $wire.set() from Alpine below.
    $itemPath = $getStatePath();
?>

{{-- Scoped styles — Filament's admin CSS doesn't scan this file for Tailwind
     utilities, so we can't rely on class names like w-6/mt-3/flex being present
     in the compiled bundle. Everything visual lives in .dtb-places-* rules. --}}
<style>
    .dtb-places { grid-column: 1 / -1; }
    .dtb-places-label { display: block; font-size: 0.875rem; font-weight: 500; line-height: 1.5rem; color: rgb(3 7 18); }
    .dark .dtb-places-label { color: rgb(255 255 255); }
    .dtb-places-required { color: rgb(220 38 38); }
    .dtb-places-hint { font-size: 0.75rem; color: rgb(107 114 128); margin-top: 0.25rem; margin-bottom: 0.5rem; }
    .dtb-places-input-wrp {
        display: flex;
        align-items: center;
        border-radius: 0.5rem;
        background: rgb(255 255 255);
        box-shadow: inset 0 0 0 1px rgba(3, 7, 18, 0.1);
    }
    .dark .dtb-places-input-wrp { background: rgba(255,255,255,0.05); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2); }
    .dtb-places-input-wrp:focus-within { box-shadow: inset 0 0 0 2px rgb(1 117 127); }
    .dtb-places-input {
        width: 100%;
        border: 0;
        background: transparent;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: rgb(3 7 18);
        outline: none;
    }
    .dtb-places-input::placeholder { color: rgb(156 163 175); }
    .dark .dtb-places-input { color: rgb(255 255 255); }
    .dtb-places-warning { margin-top: 0.375rem; font-size: 0.875rem; color: rgb(220 38 38); }
    .dtb-places-card {
        margin-top: 0.75rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid rgb(229 231 235);
        background: rgb(249 250 251);
        padding: 1rem;
    }
    .dark .dtb-places-card { border-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); }
    .dtb-places-pin {
        width: 24px;
        height: 24px;
        flex: 0 0 auto;
        margin-top: 0.125rem;
        color: rgb(1 117 127);
    }
    .dtb-places-body { flex: 1; min-width: 0; }
    .dtb-places-body-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: rgb(107 114 128); font-weight: 600; margin-bottom: 0.25rem; }
    .dtb-places-body-address { font-weight: 600; color: rgb(3 7 18); margin: 0; }
    .dark .dtb-places-body-address { color: rgb(255 255 255); }
    .dtb-places-body-citystate { font-size: 0.875rem; color: rgb(75 85 99); margin: 0.125rem 0 0; }
    .dark .dtb-places-body-citystate { color: rgb(209 213 219); }
    .dtb-places-body-coords { font-size: 0.75rem; color: rgb(107 114 128); margin: 0.25rem 0 0; }
    .dtb-places-clear {
        font-size: 0.75rem;
        color: rgb(107 114 128);
        background: none;
        border: 0;
        cursor: pointer;
        padding: 0;
    }
    .dtb-places-clear:hover { color: rgb(220 38 38); }
</style>

<div
    x-data="placesAutocomplete({
        statePath: @js($itemPath),
    })"
    x-init="init()"
    class="dtb-places"
>
    <label class="dtb-places-label">
        Business Address <span class="dtb-places-required">*</span>
    </label>
    <p class="dtb-places-hint">
        Start typing and pick from the dropdown &mdash; we&rsquo;ll fill in the rest.
    </p>

    <div class="dtb-places-input-wrp">
        <input
            type="text"
            x-ref="searchInput"
            x-model="searchValue"
            @keydown.enter.prevent
            placeholder="e.g., 100 N Main St, Bellefontaine, OH"
            autocomplete="off"
            class="dtb-places-input"
        />
    </div>

    <p x-show="showWarning" x-cloak class="dtb-places-warning">
        Please pick an address from the dropdown so we can save the coordinates.
    </p>

    <template x-if="picked">
        <div class="dtb-places-card">
            <svg class="dtb-places-pin" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd"/>
            </svg>
            <div class="dtb-places-body">
                <p class="dtb-places-body-label">Selected Address</p>
                <p class="dtb-places-body-address" x-text="address"></p>
                <p class="dtb-places-body-citystate" x-text="cityStateZip"></p>
                <p class="dtb-places-body-coords" x-text="coordsLabel"></p>
            </div>
            <button type="button" @click="clear()" class="dtb-places-clear">
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
