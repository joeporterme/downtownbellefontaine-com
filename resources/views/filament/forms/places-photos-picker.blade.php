<?php
    // Lives inside the locations Repeater item; siblings (name, address, city,
    // state, latitude, longitude) live at $itemPath.<field>. The chosen photo is
    // stashed on the *business* (root) state as places_photo_url / _credit.
    $itemPath = $getStatePath();
?>

{{-- Scoped styles: Filament's CSS bundle doesn't scan this file for Tailwind
     utilities, so all visuals live in .dtb-pp-* rules. --}}
<style>
    .dtb-pp { grid-column: 1 / -1; }
    .dtb-pp-label { display: block; font-size: 0.875rem; font-weight: 500; line-height: 1.5rem; color: rgb(3 7 18); }
    .dark .dtb-pp-label { color: rgb(255 255 255); }
    .dtb-pp-hint { font-size: 0.75rem; color: rgb(107 114 128); margin-top: 0.25rem; margin-bottom: 0.5rem; }
    .dtb-pp-btn {
        display: inline-flex; align-items: center; gap: 0.375rem;
        border-radius: 0.5rem; padding: 0.5rem 0.875rem;
        font-size: 0.8125rem; font-weight: 600; cursor: pointer;
        background: rgb(1 117 127); color: #fff; border: 0;
    }
    .dtb-pp-btn:hover { background: rgb(1 100 108); }
    .dtb-pp-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .dtb-pp-btn-secondary { background: transparent; color: rgb(107 114 128); box-shadow: inset 0 0 0 1px rgba(3,7,18,0.15); }
    .dark .dtb-pp-btn-secondary { color: rgb(209 213 219); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2); }
    .dtb-pp-btn-secondary:hover { background: rgba(3,7,18,0.04); }
    .dtb-pp-grid {
        margin-top: 0.75rem; display: grid; gap: 0.5rem;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    }
    .dtb-pp-thumb {
        position: relative; aspect-ratio: 4 / 3; border-radius: 0.5rem;
        overflow: hidden; cursor: pointer; border: 2px solid transparent;
        background: rgb(243 244 246); padding: 0;
    }
    .dtb-pp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .dtb-pp-thumb:hover { border-color: rgb(1 117 127); }
    .dtb-pp-thumb.is-selected { border-color: rgb(1 117 127); box-shadow: 0 0 0 2px rgb(1 117 127); }
    .dtb-pp-thumb.is-selected::after {
        content: '✓'; position: absolute; top: 4px; right: 4px;
        background: rgb(1 117 127); color: #fff; width: 20px; height: 20px;
        border-radius: 9999px; font-size: 12px; display: flex;
        align-items: center; justify-content: center;
    }
    .dtb-pp-status { margin-top: 0.5rem; font-size: 0.75rem; color: rgb(107 114 128); }
    .dtb-pp-note {
        margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.5rem;
        background: rgb(240 253 244); border: 1px solid rgb(187 247 208);
        font-size: 0.8125rem; color: rgb(22 101 52);
    }
    .dark .dtb-pp-note { background: rgba(34,197,94,0.08); border-color: rgba(34,197,94,0.25); color: rgb(134 239 172); }
    .dtb-pp-selected-wrap { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.75rem; }
    .dtb-pp-selected-img { width: 120px; height: 80px; object-fit: cover; border-radius: 0.5rem; }
</style>

<div
    x-data="placesPhotosPicker({ statePath: @js($itemPath) })"
    x-init="init()"
    class="dtb-pp"
>
    <label class="dtb-pp-label">Google photos</label>
    <p class="dtb-pp-hint">
        Pull owner- and visitor-uploaded photos from this business&rsquo;s Google listing and pick one as the listing image. It overrides the Street View snapshot (same as an uploaded photo). Prefer the manual upload above when you have your own photo.
    </p>

    <button type="button" class="dtb-pp-btn" @click="loadPhotos()" x-show="!loading" x-bind:disabled="loading">
        <span x-text="photos.length ? 'Reload Google photos' : 'Load Google photos'"></span>
    </button>
    <span x-show="loading" class="dtb-pp-status">Loading photos from Google&hellip;</span>

    {{-- Currently-selected pick (persists across reloads) --}}
    <template x-if="selectedUrl">
        <div class="dtb-pp-selected-wrap">
            <img class="dtb-pp-selected-img" :src="selectedUrl" alt="Selected Google photo">
            <div>
                <div class="dtb-pp-note" style="margin-top:0">This Google photo will be saved as the listing image when you save the business.</div>
                <button type="button" class="dtb-pp-btn dtb-pp-btn-secondary" style="margin-top:0.5rem" @click="clearSelection()">Clear selection</button>
            </div>
        </div>
    </template>

    <div class="dtb-pp-grid" x-show="photos.length">
        <template x-for="(photo, i) in photos" :key="i">
            <button type="button" class="dtb-pp-thumb" :class="{ 'is-selected': photo.full === selectedUrl }" @click="select(photo)">
                <img :src="photo.thumb" loading="lazy" alt="">
            </button>
        </template>
    </div>

    <p x-show="message" x-cloak class="dtb-pp-status" x-text="message"></p>
</div>

@assets
<script>
    window.placesPhotosPicker = function ({ statePath }) {
        return {
            statePath,
            // Business-level root state path (strip the ".locations.<uuid>" tail).
            rootPath: statePath.split('.locations.')[0] || 'data',
            loading: false,
            photos: [],
            selectedUrl: '',
            message: '',

            init() {
                // Restore a previously-stashed selection so it survives re-renders.
                this.selectedUrl = this.$wire.get(`${this.rootPath}.places_photo_url`) || '';
            },

            placesService() {
                if (!window.google || !google.maps || !google.maps.places) return null;
                // PlacesService needs an element to attribute results to.
                return new google.maps.places.PlacesService(document.createElement('div'));
            },

            loadPhotos() {
                const svc = this.placesService();
                if (!svc) { this.message = 'Google Maps is still loading — try again in a moment.'; return; }

                const item = this.$wire.get(this.statePath) || {};
                const name = item.name || '';
                const parts = [name, item.address, item.city, item.state].filter(Boolean).join(', ');
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);

                if (!parts && (isNaN(lat) || isNaN(lng))) {
                    this.message = 'Add the business name/address above first, then load photos.';
                    return;
                }

                this.loading = true;
                this.message = '';
                this.photos = [];

                const request = {
                    query: parts || `${lat},${lng}`,
                    fields: ['place_id'],
                };
                if (!isNaN(lat) && !isNaN(lng)) {
                    request.locationBias = { lat, lng };
                }

                svc.findPlaceFromQuery(request, (results, status) => {
                    if (status !== google.maps.places.PlacesServiceStatus.OK || !results || !results.length) {
                        this.loading = false;
                        this.message = 'No matching Google place found for this business.';
                        return;
                    }

                    svc.getDetails({ placeId: results[0].place_id, fields: ['photos'] }, (place, dStatus) => {
                        this.loading = false;

                        if (dStatus !== google.maps.places.PlacesServiceStatus.OK || !place || !place.photos || !place.photos.length) {
                            this.message = 'This Google listing has no photos to choose from.';
                            return;
                        }

                        this.photos = place.photos.map((p) => ({
                            thumb: p.getUrl({ maxWidth: 400, maxHeight: 300 }),
                            full: p.getUrl({ maxWidth: 1600, maxHeight: 1200 }),
                            credit: (p.html_attributions && p.html_attributions.length)
                                ? p.html_attributions[0].replace(/<[^>]*>/g, '').trim()
                                : '',
                        }));
                        this.message = `${this.photos.length} photo(s) from Google — click one to use it.`;
                    });
                });
            },

            select(photo) {
                this.selectedUrl = photo.full;
                this.$wire.set(`${this.rootPath}.places_photo_url`, photo.full, true);
                this.$wire.set(`${this.rootPath}.places_photo_credit`, photo.credit || '', true);
            },

            clearSelection() {
                this.selectedUrl = '';
                this.$wire.set(`${this.rootPath}.places_photo_url`, '', true);
                this.$wire.set(`${this.rootPath}.places_photo_credit`, '', true);
            },
        };
    };
</script>
@endassets
