@extends('layouts.app')

@section('title', 'Map')
@section('description', 'Interactive map of Downtown Bellefontaine, Ohio - find local shops, restaurants, lodging, parking, and everything around the historic square.')

@push('head')
<style>
    /* Sidebar scroll area on desktop */
    @media (min-width: 1024px) {
        .map-sidebar {
            max-height: calc(100vh - 12rem);
            overflow-y: auto;
        }
    }
    .map-card.is-active {
        outline: 2px solid var(--color-accent-500, #f3773d);
        outline-offset: 2px;
    }

    /* Google InfoWindow chrome overrides — kill default top padding and
       float the close button into the corner so our content controls
       its own spacing. */
    .gm-style-iw.gm-style-iw-c {
        padding: 0 !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        max-width: 280px !important;
    }
    .gm-style-iw-d {
        overflow: hidden !important;
        padding: 0 !important;
        max-height: none !important;
    }
    .gm-style-iw-chr {
        position: absolute !important;
        top: 4px !important;
        right: 4px !important;
        height: auto !important;
        margin: 0 !important;
        z-index: 2;
    }
    .gm-style-iw-ch {
        padding: 0 !important;
        height: 0 !important;
    }
    .gm-ui-hover-effect {
        width: 24px !important;
        height: 24px !important;
    }
    .gm-ui-hover-effect > span {
        margin: 4px !important;
    }
</style>
@endpush

@section('content')
{{-- Page header --}}
<section class="bg-theme-primary border-b border-theme">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-2">
            <div>
                <span class="font-display text-xl text-accent-500">Find Your Way</span>
                <h1 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">Downtown Map</h1>
            </div>
            <p class="text-theme-secondary text-sm">
                <span data-visible-count>{{ count($businesses) }}</span> of {{ count($businesses) }} pins shown
            </p>
        </div>
    </div>
</section>

{{-- Category filter --}}
<section class="bg-theme-secondary border-b border-theme">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap gap-2" data-category-filters>
            <button type="button" data-category="all" aria-pressed="true"
                class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors bg-primary-600 text-white">
                All
            </button>
            @foreach($categories as $category)
                <button type="button" data-category="{{ $category->id }}" aria-pressed="false"
                    class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors bg-theme-primary text-theme-secondary border border-theme hover:border-primary-400">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- Map + sidebar --}}
<section class="bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Map (first on mobile, right side on desktop) --}}
            <div class="lg:col-span-2 lg:order-2">
                <div id="map" class="w-full h-[55vh] lg:h-[calc(100vh-12rem)] rounded-2xl bg-theme-secondary border border-theme overflow-hidden">
                    <div class="h-full w-full flex items-center justify-center text-theme-tertiary">
                        <div class="text-center">
                            <i class="fa-duotone fa-light fa-map text-4xl mb-2"></i>
                            <p class="text-sm">Loading map…</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar (below on mobile, left side on desktop) --}}
            <aside class="lg:col-span-1 lg:order-1">
                <div class="map-sidebar space-y-3 pr-1" data-business-list>
                    {{-- Cards rendered by JS --}}
                </div>
                <div class="hidden text-center py-12 text-theme-tertiary" data-empty-state>
                    <i class="fa-duotone fa-light fa-map-pin-slash text-4xl mb-3"></i>
                    <p>No businesses match your filters.</p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const MAP_BUSINESSES = @json($businesses);
    const MAP_CENTER = { lat: 40.3614, lng: -83.7596 };

    const PIN_BUSINESS = {
        path: 'M 0,0 C -2,-4 -10,-10 -10,-20 a 10,10 0 1 1 20,0 c 0,10 -8,16 -10,20 z',
        fillColor: '#f3773d',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2,
        scale: 1,
        anchor: { x: 0, y: 0 },
        labelOrigin: { x: 0, y: -22 },
    };

    const PIN_PARKING = {
        path: 'M 0,0 C -2,-4 -10,-10 -10,-20 a 10,10 0 1 1 20,0 c 0,10 -8,16 -10,20 z',
        fillColor: '#0ea5e9',
        fillOpacity: 1,
        strokeColor: '#ffffff',
        strokeWeight: 2,
        scale: 1,
        anchor: { x: 0, y: 0 },
        labelOrigin: { x: 0, y: -22 },
    };

    let map;
    let infoWindow;
    let markersById = {};
    let activeCategories = new Set(['all']);
    let activeBusinessId = null;

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s).replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    function getFiltered() {
        if (activeCategories.has('all')) return MAP_BUSINESSES;
        return MAP_BUSINESSES.filter(b =>
            b.category_ids.some(id => activeCategories.has(String(id)))
        );
    }

    function initMap() {
        // Anchors must be google.maps.Point — convert now that the API is loaded
        PIN_BUSINESS.anchor = new google.maps.Point(0, 0);
        PIN_BUSINESS.labelOrigin = new google.maps.Point(0, -22);
        PIN_PARKING.anchor = new google.maps.Point(0, 0);
        PIN_PARKING.labelOrigin = new google.maps.Point(0, -22);

        map = new google.maps.Map(document.getElementById('map'), {
            center: MAP_CENTER,
            zoom: 16,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            styles: [
                { featureType: 'poi.business', stylers: [{ visibility: 'off' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] },
            ],
        });
        infoWindow = new google.maps.InfoWindow();

        MAP_BUSINESSES.forEach(b => {
            const marker = new google.maps.Marker({
                position: { lat: b.latitude, lng: b.longitude },
                map,
                title: b.name,
                icon: b.is_parking ? PIN_PARKING : PIN_BUSINESS,
                label: b.is_parking
                    ? { text: 'P', color: '#ffffff', fontSize: '11px', fontWeight: '700' }
                    : null,
            });
            marker.addListener('click', () => focusBusiness(b.id, { fromMarker: true }));
            markersById[b.id] = marker;
        });

        renderAll();
    }

    function renderAll() {
        const filtered = getFiltered();
        const visibleIds = new Set(filtered.map(b => b.id));

        // Toggle marker visibility
        MAP_BUSINESSES.forEach(b => {
            const m = markersById[b.id];
            if (!m) return;
            m.setMap(visibleIds.has(b.id) ? map : null);
        });

        // Fit bounds to visible markers (or keep center if zero/one)
        if (filtered.length > 1) {
            const bounds = new google.maps.LatLngBounds();
            filtered.forEach(b => bounds.extend({ lat: b.latitude, lng: b.longitude }));
            map.fitBounds(bounds, 60);
        } else if (filtered.length === 1) {
            map.setCenter({ lat: filtered[0].latitude, lng: filtered[0].longitude });
            map.setZoom(17);
        }

        // Sidebar
        const list = document.querySelector('[data-business-list]');
        const empty = document.querySelector('[data-empty-state]');
        if (filtered.length === 0) {
            list.innerHTML = '';
            empty.classList.remove('hidden');
        } else {
            empty.classList.add('hidden');
            list.innerHTML = filtered.map(b => cardHtml(b)).join('');
            list.querySelectorAll('[data-business-card]').forEach(el => {
                el.addEventListener('click', e => {
                    if (e.target.closest('a')) return;
                    focusBusiness(parseInt(el.dataset.businessCard, 10));
                });
            });
        }

        // Counter
        const counter = document.querySelector('[data-visible-count]');
        if (counter) counter.textContent = filtered.length;
    }

    function cardHtml(b) {
        const imgBlock = b.image
            ? `<img src="${escapeHtml(b.image)}" alt="${escapeHtml(b.name)}" class="w-full h-32 object-cover">`
            : `<div class="w-full h-32 bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                   <i class="fa-duotone fa-light ${b.is_parking ? 'fa-square-parking' : 'fa-store'} text-3xl text-primary-300 dark:text-primary-700"></i>
               </div>`;

        const categoryBadge = b.category_name
            ? `<span class="inline-block px-2 py-0.5 text-[10px] uppercase tracking-wider font-semibold rounded ${
                b.is_parking
                    ? 'bg-sky-100 dark:bg-sky-900/40 text-sky-700 dark:text-sky-300'
                    : 'bg-accent-100 dark:bg-accent-900/40 text-accent-700 dark:text-accent-300'
              }">${escapeHtml(b.category_name)}</span>`
            : '';

        const addressLine = b.address
            ? `<p class="text-theme-tertiary text-sm flex items-start gap-1.5 mt-1">
                   <i class="fa-duotone fa-light fa-location-dot text-primary-500 mt-0.5 flex-shrink-0"></i>
                   <span>${escapeHtml(b.address)}${b.city ? ', ' + escapeHtml(b.city) : ''}${b.state ? ', ' + escapeHtml(b.state) : ''}</span>
               </p>`
            : '';

        const phoneLine = b.phone
            ? `<a href="tel:${escapeHtml(b.phone)}" class="text-primary-600 dark:text-primary-400 text-sm font-medium hover:underline flex items-center gap-1.5 mt-1">
                   <i class="fa-duotone fa-light fa-phone text-primary-500"></i>
                   ${escapeHtml(b.phone)}
               </a>`
            : '';

        const links = [];
        if (b.website) links.push(`<a href="${escapeHtml(b.website)}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-primary-500 hover:border-primary-400 transition-colors" title="Website"><i class="fa-duotone fa-light fa-globe"></i></a>`);
        if (b.facebook_url) links.push(`<a href="${escapeHtml(b.facebook_url)}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-blue-600 hover:border-blue-400 transition-colors" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>`);
        if (b.instagram_url) links.push(`<a href="${escapeHtml(b.instagram_url)}" target="_blank" rel="noopener" class="w-8 h-8 rounded-full bg-theme-primary border border-theme flex items-center justify-center text-theme-secondary hover:text-pink-600 hover:border-pink-400 transition-colors" title="Instagram"><i class="fa-brands fa-instagram"></i></a>`);
        const linksRow = links.length
            ? `<div class="flex items-center gap-2 mt-3">${links.join('')}</div>`
            : '';

        return `
            <div data-business-card="${b.id}" class="map-card bg-theme-secondary rounded-xl border border-theme overflow-hidden cursor-pointer hover:border-accent-400 transition-colors">
                ${imgBlock}
                <div class="p-4">
                    ${categoryBadge}
                    <h3 class="text-base font-bold text-theme-primary mt-2 leading-tight">
                        <a href="${escapeHtml(b.url)}" class="hover:text-accent-600 transition-colors">${escapeHtml(b.name)}</a>
                    </h3>
                    ${addressLine}
                    ${phoneLine}
                    ${linksRow}
                </div>
            </div>
        `;
    }

    function focusBusiness(id, opts = {}) {
        const b = MAP_BUSINESSES.find(x => x.id === id);
        const marker = markersById[id];
        if (!b || !marker) return;

        activeBusinessId = id;

        // Highlight card + scroll into view
        document.querySelectorAll('.map-card').forEach(el => el.classList.remove('is-active'));
        const card = document.querySelector(`[data-business-card="${id}"]`);
        if (card) {
            card.classList.add('is-active');
            if (!opts.fromCard) {
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Center map + open info window
        if (!opts.fromMarker) {
            map.panTo(marker.getPosition());
            if (map.getZoom() < 17) map.setZoom(17);
        }
        infoWindow.setContent(`
            <div style="padding:14px 32px 14px 16px;min-width:180px;font-family:Montserrat,system-ui,sans-serif;line-height:1.35;">
                <div style="font-weight:700;font-size:15px;color:#0a1f22;margin-bottom:4px;">${escapeHtml(b.name)}</div>
                ${b.address ? `<div style="font-size:13px;color:#4b5563;margin-bottom:8px;">${escapeHtml(b.address)}</div>` : ''}
                <a href="${escapeHtml(b.url)}" style="font-size:13px;color:#e25a1f;font-weight:600;text-decoration:none;">View details →</a>
            </div>
        `);
        infoWindow.open({ anchor: marker, map });
    }

    // Filter pill clicks
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-category]').forEach(btn => {
            btn.addEventListener('click', () => {
                const cat = btn.dataset.category;
                if (cat === 'all') {
                    activeCategories = new Set(['all']);
                } else {
                    activeCategories.delete('all');
                    if (activeCategories.has(cat)) {
                        activeCategories.delete(cat);
                        if (activeCategories.size === 0) activeCategories.add('all');
                    } else {
                        activeCategories.add(cat);
                    }
                }
                updateFilterUI();
                if (map) renderAll();
            });
        });
    });

    function updateFilterUI() {
        document.querySelectorAll('[data-category]').forEach(btn => {
            const cat = btn.dataset.category;
            const active = activeCategories.has(cat);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            btn.classList.toggle('bg-primary-600', active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('bg-theme-primary', !active);
            btn.classList.toggle('text-theme-secondary', !active);
            btn.classList.toggle('border', !active);
            btn.classList.toggle('border-theme', !active);
        });
    }
</script>
<x-google-maps-script callback="initMap" />
@endpush
