<style>
    .dtb-fp { grid-column: 1 / -1; }
    .dtb-fp-label { display: block; font-size: 0.875rem; font-weight: 500; line-height: 1.5rem; color: rgb(3 7 18); }
    .dark .dtb-fp-label { color: rgb(255 255 255); }
    .dtb-fp-hint { font-size: 0.75rem; color: rgb(107 114 128); margin-top: 0.25rem; margin-bottom: 0.5rem; }
    .dtb-fp-btn {
        display: inline-flex; align-items: center; gap: 0.375rem;
        border-radius: 0.5rem; padding: 0.5rem 0.875rem;
        font-size: 0.8125rem; font-weight: 600; cursor: pointer;
        background: rgb(1 117 127); color: #fff; border: 0;
    }
    .dtb-fp-btn:hover { background: rgb(1 100 108); }
    .dtb-fp-btn-secondary { background: transparent; color: rgb(107 114 128); box-shadow: inset 0 0 0 1px rgba(3,7,18,0.15); }
    .dark .dtb-fp-btn-secondary { color: rgb(209 213 219); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2); }
    .dtb-fp-grid { margin-top: 0.75rem; display: grid; gap: 0.5rem; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); }
    .dtb-fp-thumb {
        position: relative; aspect-ratio: 4 / 3; border-radius: 0.5rem;
        overflow: hidden; cursor: pointer; border: 2px solid transparent;
        background: rgb(243 244 246); padding: 0;
    }
    .dtb-fp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .dtb-fp-thumb:hover { border-color: rgb(1 117 127); }
    .dtb-fp-thumb.is-selected { border-color: rgb(1 117 127); box-shadow: 0 0 0 2px rgb(1 117 127); }
    .dtb-fp-thumb.is-selected::after {
        content: '✓'; position: absolute; top: 4px; right: 4px;
        background: rgb(1 117 127); color: #fff; width: 20px; height: 20px;
        border-radius: 9999px; font-size: 12px; display: flex; align-items: center; justify-content: center;
    }
    .dtb-fp-status { margin-top: 0.5rem; font-size: 0.75rem; color: rgb(107 114 128); }
    .dtb-fp-tip {
        margin: 0.25rem 0 0.5rem; padding: 0.5rem 0.75rem; border-radius: 0.5rem;
        background: rgb(255 251 235); border: 1px solid rgb(253 230 138);
        font-size: 0.8125rem; color: rgb(146 64 14);
    }
    .dark .dtb-fp-tip { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.3); color: rgb(252 211 77); }
    .dtb-fp-note {
        margin-top: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.5rem;
        background: rgb(240 253 244); border: 1px solid rgb(187 247 208); font-size: 0.8125rem; color: rgb(22 101 52);
    }
    .dark .dtb-fp-note { background: rgba(34,197,94,0.08); border-color: rgba(34,197,94,0.25); color: rgb(134 239 172); }
    .dtb-fp-selected-wrap { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.75rem; }
    .dtb-fp-selected-img { width: 120px; height: 80px; object-fit: cover; border-radius: 0.5rem; }
</style>

<div x-data="placesFeaturedPicker()" x-init="init()" class="dtb-fp">
    <label class="dtb-fp-label">Or pick the featured image from Google</label>
    <p class="dtb-fp-hint">
        Pull photos from this business&rsquo;s Google listing and use one as the big featured image at the top of the page. On save it&rsquo;s downloaded and stored in our app.
    </p>
    <p class="dtb-fp-tip">
        <strong>Note:</strong> add a location with an address under &ldquo;Locations&rdquo; and save first &mdash; Google photo matching works best when a location is applied to the business.
    </p>

    <button type="button" class="dtb-fp-btn" @click="loadPhotos()" x-show="!loading" x-bind:disabled="loading">
        <span x-text="photos.length ? 'Reload Google photos' : 'Load Google photos'"></span>
    </button>
    <span x-show="loading" class="dtb-fp-status">Loading photos from Google&hellip;</span>

    {{-- The image currently used as the featured image --}}
    <template x-if="currentImage && !selectedUrl">
        <div class="dtb-fp-selected-wrap">
            <img class="dtb-fp-selected-img" :src="currentImage" alt="Current featured image">
            <div class="dtb-fp-note" style="margin-top:0">&#10003; This is the current featured image.</div>
        </div>
    </template>

    <template x-if="selectedUrl">
        <div class="dtb-fp-selected-wrap">
            <img class="dtb-fp-selected-img" :src="selectedUrl" alt="Selected Google photo">
            <div>
                <div class="dtb-fp-note" style="margin-top:0">This Google photo will be saved as the featured image when you save the business.</div>
                <button type="button" class="dtb-fp-btn dtb-fp-btn-secondary" style="margin-top:0.5rem" @click="clearSelection()">Clear selection</button>
            </div>
        </div>
    </template>

    <div class="dtb-fp-grid" x-show="photos.length">
        <template x-for="(photo, i) in photos" :key="i">
            <button type="button" class="dtb-fp-thumb" :class="{ 'is-selected': photo.full === selectedUrl }" @click="select(photo)">
                <img :src="photo.thumb" loading="lazy" alt="">
            </button>
        </template>
    </div>

    <p x-show="message" x-cloak class="dtb-fp-status" x-text="message"></p>
</div>

@assets
<script>
    window.placesFeaturedPicker = function () {
        return {
            root: 'data',
            loading: false,
            photos: [],
            selectedUrl: '',
            currentImage: '',
            message: '',

            init() {
                this.selectedUrl = this.$wire.get(`${this.root}.featured_places_url`) || '';
                this.currentImage = this.imageUrl(this.$wire.get(`${this.root}.featured_image`));
            },

            imageUrl(path) {
                if (!path) return '';
                if (/^https?:\/\//.test(path) || path.startsWith('/')) return path;
                return '/storage/' + path;
            },

            // Best-effort search context: business name + the first location's address/coords.
            context() {
                const name = this.$wire.get(`${this.root}.name`) || '';
                const locs = this.$wire.get(`${this.root}.locations`) || {};
                const first = (locs && typeof locs === 'object') ? (Object.values(locs)[0] || {}) : {};
                return {
                    query: [name, first.address, first.city || 'Bellefontaine', first.state || 'OH'].filter(Boolean).join(', '),
                    lat: parseFloat(first.latitude),
                    lng: parseFloat(first.longitude),
                    hasName: !!name,
                };
            },

            async loadPhotos() {
                if (!window.google || !google.maps || !google.maps.places || !google.maps.places.Place) {
                    this.message = 'Google Maps is still loading — try again in a moment.';
                    return;
                }
                const ctx = this.context();
                if (!ctx.hasName) { this.message = 'Enter the business name first, then load photos.'; return; }

                this.loading = true; this.message = ''; this.photos = [];
                try {
                    const request = { textQuery: ctx.query, fields: ['id', 'displayName', 'photos', 'location'], maxResultCount: 1 };
                    if (!isNaN(ctx.lat) && !isNaN(ctx.lng)) {
                        request.locationBias = { center: { lat: ctx.lat, lng: ctx.lng }, radius: 200 };
                    }
                    const { places } = await google.maps.places.Place.searchByText(request);
                    this.loading = false;

                    if (!places || !places.length) { this.message = 'No matching Google place found.'; return; }
                    const photos = places[0].photos || [];
                    if (!photos.length) { this.message = 'This Google listing has no photos to choose from.'; return; }

                    this.photos = photos.map((p) => ({
                        thumb: p.getURI({ maxWidth: 400, maxHeight: 300 }),
                        full: p.getURI({ maxWidth: 1600, maxHeight: 1200 }),
                    }));
                    this.message = `${this.photos.length} photo(s) from Google — click one to use it.`;
                } catch (e) {
                    this.loading = false;
                    this.message = 'Could not load Google photos (' + (e && e.message ? e.message : e) + '). The Places API (New) may need to be enabled.';
                    console.error('[places-featured] searchByText failed', e);
                }
            },

            select(photo) {
                this.selectedUrl = photo.full;
                this.$wire.set(`${this.root}.featured_places_url`, photo.full, true);
            },

            clearSelection() {
                this.selectedUrl = '';
                this.$wire.set(`${this.root}.featured_places_url`, '', true);
            },
        };
    };
</script>
@endassets
