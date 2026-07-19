<?php
    // Lives inside the locations Repeater item; sibling Hidden fields (latitude,
    // longitude, streetview_*) live at $itemPath.<field>.
    $itemPath = $getStatePath();
?>

{{-- Scoped styles: Filament's CSS bundle doesn't scan this file for Tailwind
     utilities, so all visuals live in .dtb-sv-* rules. --}}
<style>
    .dtb-sv { grid-column: 1 / -1; }
    .dtb-sv-label { display: block; font-size: 0.875rem; font-weight: 500; line-height: 1.5rem; color: rgb(3 7 18); }
    .dark .dtb-sv-label { color: rgb(255 255 255); }
    .dtb-sv-hint { font-size: 0.75rem; color: rgb(107 114 128); margin-top: 0.25rem; margin-bottom: 0.5rem; }
    .dtb-sv-canvas {
        width: 100%;
        height: 340px;
        border-radius: 0.5rem;
        overflow: hidden;
        background: rgb(243 244 246);
        box-shadow: inset 0 0 0 1px rgba(3, 7, 18, 0.1);
    }
    .dark .dtb-sv-canvas { background: rgba(255,255,255,0.05); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2); }
    .dtb-sv-note {
        display: flex; align-items: center; justify-content: center;
        height: 340px; text-align: center; padding: 1.5rem;
        border-radius: 0.5rem; background: rgb(249 250 251);
        border: 1px dashed rgb(209 213 219); color: rgb(107 114 128); font-size: 0.875rem;
    }
    .dark .dtb-sv-note { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.15); color: rgb(156 163 175); }
    .dtb-sv-status { margin-top: 0.5rem; font-size: 0.75rem; color: rgb(107 114 128); }
</style>

<div
    x-data="streetviewPicker({ statePath: @js($itemPath) })"
    x-init="init()"
    class="dtb-sv"
>
    <label class="dtb-sv-label">Street View</label>
    <p class="dtb-sv-hint">
        Drag to look around and scroll to zoom until the storefront is framed nicely &mdash; we save this exact view. Use the <strong>date control</strong> (top-left of the panorama) to switch to newer imagery. If Google's imagery is out of date, upload a "Listing photo" above to override it entirely.
    </p>

    {{-- Panorama --}}
    <div x-show="state === 'ready' || state === 'loading'" x-ref="pano" class="dtb-sv-canvas"></div>

    {{-- States --}}
    <div x-show="state === 'no-location'" x-cloak class="dtb-sv-note">
        Pick the business address above first &mdash; then Street View will load here.
    </div>
    <div x-show="state === 'no-imagery'" x-cloak class="dtb-sv-note">
        Google has no Street View imagery at this address. The listing will use the uploaded image or a placeholder.
    </div>

    <p x-show="state === 'ready'" x-cloak class="dtb-sv-status">
        View saved. A fresh snapshot is generated when you save this business.
    </p>
</div>

@assets
<script>
    window.streetviewPicker = function ({ statePath }) {
        return {
            statePath,
            state: 'loading',
            pano: null,
            panoId: '',
            heading: '',
            pitch: '',
            zoom: '',
            lat: '',
            lng: '',
            _timer: null,

            init() {
                const item = this.$wire.get(this.statePath) || {};
                this.lat = item.latitude || '';
                this.lng = item.longitude || '';
                this.panoId = item.streetview_pano_id || '';
                this.heading = item.streetview_heading ?? '';
                this.pitch = item.streetview_pitch ?? '';
                this.zoom = item.streetview_zoom ?? '';

                if (!this.lat || !this.lng) {
                    this.state = 'no-location';
                    return;
                }

                this.attach();
            },

            attach(retries = 20) {
                if (!window.google || !google.maps) {
                    if (retries <= 0) { this.state = 'no-imagery'; return; }
                    setTimeout(() => this.attach(retries - 1), 250);
                    return;
                }

                const svService = new google.maps.StreetViewService();
                const request = this.panoId
                    ? { pano: this.panoId }
                    : { location: { lat: parseFloat(this.lat), lng: parseFloat(this.lng) }, radius: 60, source: google.maps.StreetViewSource.OUTDOOR };

                svService.getPanorama(request, (data, status) => {
                    if (status !== 'OK' || !data || !data.location) {
                        this.state = 'no-imagery';
                        return;
                    }

                    this.state = 'ready';
                    this.panoId = data.location.pano;

                    this.pano = new google.maps.StreetViewPanorama(this.$refs.pano, {
                        pano: this.panoId,
                        pov: {
                            heading: this.heading !== '' ? parseFloat(this.heading) : 0,
                            pitch: this.pitch !== '' ? parseFloat(this.pitch) : 0,
                        },
                        zoom: this.zoom !== '' ? parseFloat(this.zoom) : 1,
                        addressControl: false,
                        showRoadLabels: false,
                        motionTracking: false,
                        motionTrackingControl: false,
                        fullscreenControl: false,
                        // Let the admin switch to newer imagery / other captures and
                        // move around; whichever pano they land on gets saved.
                        imageDateControl: true,
                        linksControl: true,
                        clickToGo: true,
                        panControl: true,
                        zoomControl: true,
                    });

                    // Persist the resolved pano id (+ current framing) right away.
                    this.capture();

                    this.pano.addListener('pov_changed', () => this.debouncedCapture());
                    this.pano.addListener('zoom_changed', () => this.debouncedCapture());
                    this.pano.addListener('pano_changed', () => this.debouncedCapture());
                });
            },

            debouncedCapture() {
                clearTimeout(this._timer);
                this._timer = setTimeout(() => this.capture(), 350);
            },

            capture() {
                if (!this.pano) return;
                const pov = this.pano.getPov() || {};
                this.heading = typeof pov.heading === 'number' ? pov.heading : this.heading;
                this.pitch = typeof pov.pitch === 'number' ? pov.pitch : this.pitch;
                this.zoom = this.pano.getZoom();
                this.panoId = this.pano.getPano();
                this.pushToWire();
            },

            pushToWire() {
                this.$wire.set(`${this.statePath}.streetview_pano_id`, this.panoId ?? '', true);
                this.$wire.set(`${this.statePath}.streetview_heading`, this.heading ?? '', true);
                this.$wire.set(`${this.statePath}.streetview_pitch`, this.pitch ?? '', true);
                this.$wire.set(`${this.statePath}.streetview_zoom`, this.zoom ?? '', true);
            },
        };
    };
</script>
@endassets
