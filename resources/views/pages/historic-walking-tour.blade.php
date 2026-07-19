@extends('layouts.app')

@section('title', 'Historic Walking Tour')
@section('description', "Explore Downtown Bellefontaine's self-guided historic walking tour - 14 stops, bronze plaques, and the stories behind our historic storefronts, all on one interactive map.")

@php
    $stops = $tour['stops'];
    $mapStops = collect($stops)->map(fn ($s) => ['order' => $s['order'], 'lat' => $s['lat'], 'lng' => $s['lng'], 'title' => $s['title']])->values();
@endphp

@push('styles')
<style>
    .tour-chip { display:inline-flex; align-items:center; gap:.5rem; padding:.4rem .9rem; border-radius:9999px; background:rgba(255,255,255,.12); color:#fff; font-size:.85rem; font-weight:600; backdrop-filter:blur(4px); }
    /* Sepia photo — vintage frame */
    .tour-photo { border:6px solid #fff; box-shadow:0 20px 40px rgba(0,0,0,.18); }
    .dark .tour-photo { border-color:#1f2937; }
    /* Number badge */
    .tour-num { width:3rem; height:3rem; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.25rem; color:#fff; background:linear-gradient(135deg,#f59260,#e25a1f); box-shadow:0 8px 20px rgba(243,119,61,.35); }
    /* Map numbered stop buttons */
    .tour-goto.is-active { background:#f3773d; color:#fff; border-color:#f3773d; }
    /* Sticky progress bar */
    .tour-progress-fill { transition:width .3s ease; }
    /* Plaque thumb hover */
    .tour-plaque { transition:transform .3s ease, box-shadow .3s ease; }
    .tour-plaque:hover { transform:translateY(-3px); box-shadow:0 16px 32px rgba(0,0,0,.2); }
    #tour-map-canvas { width:100%; height:420px; border-radius:1rem; }
    @media (min-width:1024px){ #tour-map-canvas{ height:520px; } }
</style>
@endpush

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Historic Walking Tour"
    subtitle="Bronze plaques, brick storefronts, and a story on nearly every corner. Fourteen stops, one easy loop — take it at your own pace."
    image="/images/pages/mckinley-street.jpg">
    <div class="mt-7 flex flex-wrap justify-center gap-3">
        <span class="tour-chip"><i class="fa-duotone fa-light fa-location-dot text-accent-300"></i> 14 stops</span>
        <span class="tour-chip"><i class="fa-duotone fa-light fa-person-walking text-accent-300"></i> Self-guided</span>
        <span class="tour-chip"><i class="fa-duotone fa-light fa-route text-accent-300"></i> ~1 mile loop</span>
    </div>
    <div class="mt-7 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="#tour-map" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-full transition-all pulse-glow">
            <i class="fa-duotone fa-light fa-play"></i> Start the Tour
        </a>
        <a href="{{ $tour['map_pdf'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white/10 border-2 border-white/50 hover:bg-white hover:text-primary-800 text-white font-semibold rounded-full transition-all">
            <i class="fa-duotone fa-light fa-file-pdf"></i> Download Map (PDF)
        </a>
    </div>
</x-page-hero>

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Historic Walking Tour']]" />

{{-- Intro --}}
<section class="py-14 md:py-16 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="font-display text-2xl sm:text-3xl text-accent-500">Preserving our story</span>
        <div class="mt-4 space-y-4 text-theme-secondary text-lg leading-relaxed">
            @foreach(explode("\n\n", $tour['intro']['body']) as $para)
                <p>{{ $para }}</p>
            @endforeach
        </div>
    </div>
</section>

{{-- Interactive map --}}
<section id="tour-map" class="py-14 bg-theme-secondary scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">The Route</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">Tap a pin to jump to a stop</h2>
        </div>
        <div id="tour-map-canvas" class="shadow-lg bg-theme-tertiary"></div>
        {{-- Numbered quick-jump grid --}}
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5">
            @foreach($stops as $s)
                <button type="button" data-goto="{{ $s['order'] }}"
                        class="tour-goto flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-theme-primary border border-theme text-left hover:border-accent-400 transition-colors">
                    <span class="flex-shrink-0 w-7 h-7 rounded-full bg-accent-100 dark:bg-accent-900/40 text-accent-600 dark:text-accent-300 font-bold text-sm flex items-center justify-center">{{ $s['order'] }}</span>
                    <span class="text-sm font-medium text-theme-primary truncate">{{ $s['title'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
</section>

{{-- Sticky progress bar --}}
<div id="tour-progress" class="sticky top-20 z-30 bg-theme-primary border-y border-theme shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center gap-3">
        <div class="hidden sm:block flex-shrink-0">
            <span class="text-xs uppercase tracking-wide text-theme-tertiary font-semibold">Stop</span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <span id="tp-num" class="text-accent-600 dark:text-accent-400 font-bold">1</span>
            <span class="text-theme-tertiary text-sm">/ {{ count($stops) }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="h-1.5 rounded-full bg-theme-tertiary overflow-hidden">
                <div id="tp-fill" class="tour-progress-fill h-full bg-accent-500" style="width:0%"></div>
            </div>
            <p id="tp-title" class="text-sm font-medium text-theme-primary truncate mt-1">{{ $stops[0]['title'] }}</p>
        </div>
        <label class="sr-only" for="tp-jump">Jump to stop</label>
        <select id="tp-jump" class="hidden md:block flex-shrink-0 text-sm rounded-lg border border-theme bg-theme-secondary text-theme-secondary px-2 py-1.5 max-w-[10rem]">
            @foreach($stops as $s)
                <option value="{{ $s['order'] }}">{{ $s['order'] }}. {{ $s['title'] }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <button type="button" id="tp-prev" aria-label="Previous stop" class="w-9 h-9 rounded-full bg-theme-secondary border border-theme text-theme-secondary hover:text-primary-500 hover:border-primary-400 transition-colors flex items-center justify-center">
                <i class="fa-duotone fa-light fa-arrow-up"></i>
            </button>
            <button type="button" id="tp-next" aria-label="Next stop" class="w-9 h-9 rounded-full bg-theme-secondary border border-theme text-theme-secondary hover:text-primary-500 hover:border-primary-400 transition-colors flex items-center justify-center">
                <i class="fa-duotone fa-light fa-arrow-down"></i>
            </button>
        </div>
    </div>
</div>

{{-- Stops --}}
<div class="bg-theme-primary">
    @foreach($stops as $i => $s)
        <section id="stop-{{ $s['order'] }}" data-stop="{{ $s['order'] }}" class="tour-stop py-14 md:py-20 scroll-mt-32 {{ $i % 2 ? 'bg-theme-secondary' : 'bg-theme-primary' }}">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    {{-- Imagery --}}
                    <div class="reveal-scale active {{ $i % 2 ? 'lg:order-2' : '' }}">
                        <a href="{{ $s['photo'] }}" data-lightbox data-lightbox-group="tour" data-lightbox-caption="{{ $s['title'] }} — {{ $s['address'] }}"
                           class="block rounded-2xl overflow-hidden tour-photo cursor-zoom-in">
                            <img src="{{ $s['photo'] }}" alt="Historic photo of {{ $s['title'] }}" loading="lazy" class="w-full aspect-[4/3] object-cover">
                        </a>
                        {{-- Plaque --}}
                        <div class="mt-5 flex items-center gap-4">
                            <a href="{{ $s['plaque'] }}" data-lightbox data-lightbox-group="tour" data-lightbox-caption="Bronze plaque — {{ $s['title'] }}"
                               class="tour-plaque flex-shrink-0 block w-24 h-24 rounded-lg overflow-hidden border border-theme bg-theme-primary cursor-zoom-in">
                                <img src="{{ $s['plaque'] }}" alt="Bronze plaque for {{ $s['title'] }}" loading="lazy" class="w-full h-full object-cover">
                            </a>
                            <div class="text-sm text-theme-tertiary">
                                <p class="font-semibold text-theme-secondary flex items-center gap-1.5"><i class="fa-duotone fa-light fa-medal text-accent-500"></i> The bronze plaque</p>
                                <p>Tap to read the full plaque up close.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Story --}}
                    <div class="reveal-{{ $i % 2 ? 'left' : 'right' }} active {{ $i % 2 ? 'lg:order-1' : '' }}">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="tour-num flex-shrink-0">{{ $s['order'] }}</span>
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary leading-tight">{{ $s['title'] }}</h2>
                                <p class="text-theme-tertiary text-sm flex items-center gap-1.5 mt-1"><i class="fa-duotone fa-light fa-location-dot text-primary-500"></i>{{ $s['address'] }}</p>
                            </div>
                        </div>
                        <p class="text-theme-secondary text-lg leading-relaxed">{{ $s['body'] }}</p>

                        @if(!empty($s['sponsors']))
                            <div class="mt-6 pt-5 border-t border-theme">
                                <p class="text-xs uppercase tracking-wide text-theme-tertiary font-semibold mb-3">Plaque sponsored by</p>
                                <div class="flex flex-wrap items-center gap-5">
                                    @foreach($s['sponsors'] as $sp)
                                        <a href="{{ $sp['link'] }}" target="_blank" rel="noopener" class="group flex items-center gap-3" title="{{ $sp['name'] }}">
                                            <span class="h-12 w-24 rounded-lg bg-white flex items-center justify-center p-1.5 shadow-sm">
                                                <img src="{{ $sp['logo'] }}" alt="{{ $sp['name'] }}" class="max-h-full max-w-full object-contain">
                                            </span>
                                            <span class="text-sm font-medium text-theme-secondary group-hover:text-primary-500 transition-colors">{{ $sp['name'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endforeach
</div>

{{-- Footer CTAs --}}
<section class="py-16 bg-primary-800 dark:bg-primary-950 text-white relative overflow-hidden">
    <div class="pineapple-bg absolute -right-24 -bottom-24 w-80 h-80 opacity-[0.06]"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <span class="font-display text-2xl sm:text-3xl text-accent-300">That's the tour</span>
        <h2 class="text-2xl sm:text-3xl font-bold mt-1 mb-4">Take it downtown</h2>
        <p class="text-primary-200 mb-8 max-w-xl mx-auto">Grab the printed guide for the road, put your business or organization on a plaque, or explore the rest of downtown on our interactive map.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ $tour['map_pdf'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-file-pdf"></i> Printed Guide
            </a>
            <a href="{{ $tour['sponsor_form'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 border-2 border-white/40 hover:bg-white/20 text-white font-semibold rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-medal"></i> Sponsor a Plaque
            </a>
            <a href="{{ route('pages.map') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white/10 border-2 border-white/40 hover:bg-white/20 text-white font-semibold rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-map-location-dot"></i> Downtown Map
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    window.TOUR_STOPS = @json($mapStops);
</script>
<script>
(function () {
    const stops = window.TOUR_STOPS || [];
    if (!stops.length) return;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let map = null;
    const markers = {};
    let activeOrder = 1;

    // Smooth-scroll to a stop, accounting for the sticky header + progress bar.
    function gotoStop(order) {
        const el = document.getElementById('stop-' + order);
        if (!el) return;
        const offset = 140;
        const y = el.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: y, behavior: reduceMotion ? 'auto' : 'smooth' });
    }

    function setActive(order) {
        if (order === activeOrder) return;
        activeOrder = order;
        const stop = stops.find(s => s.order === order);
        document.getElementById('tp-num').textContent = order;
        document.getElementById('tp-title').textContent = stop ? stop.title : '';
        document.getElementById('tp-fill').style.width = ((order / stops.length) * 100) + '%';
        const jump = document.getElementById('tp-jump');
        if (jump) jump.value = order;
        document.querySelectorAll('[data-goto]').forEach(b => {
            b.classList.toggle('is-active', Number(b.dataset.goto) === order);
        });
        Object.entries(markers).forEach(([o, m]) => {
            m.setIcon(pinIcon(Number(o) === order));
            m.setZIndex(Number(o) === order ? 999 : Number(o));
        });
    }

    function pinIcon(active) {
        return {
            path: 0, // google.maps.SymbolPath.CIRCLE, set numerically pre-load-safe
            scale: active ? 16 : 13,
            fillColor: active ? '#01757f' : '#f3773d',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 2,
        };
    }

    // --- Map ---
    window.initTourMap = function () {
        map = new google.maps.Map(document.getElementById('tour-map-canvas'), {
            center: { lat: 40.36055, lng: -83.76055 },
            zoom: 16,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            styles: [
                { featureType: 'poi.business', stylers: [{ visibility: 'off' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] },
            ],
        });

        stops.forEach(s => {
            const marker = new google.maps.Marker({
                position: { lat: s.lat, lng: s.lng },
                map,
                title: s.order + '. ' + s.title,
                label: { text: String(s.order), color: '#ffffff', fontSize: '12px', fontWeight: '700' },
                icon: { ...pinIcon(false), path: google.maps.SymbolPath.CIRCLE, labelOrigin: new google.maps.Point(0, 0) },
                zIndex: s.order,
            });
            marker.addListener('click', () => gotoStop(s.order));
            markers[s.order] = marker;
        });
        setActive(1);
    };

    // --- Controls ---
    document.addEventListener('click', (e) => {
        const goto = e.target.closest('[data-goto]');
        if (goto) { e.preventDefault(); gotoStop(Number(goto.dataset.goto)); }
    });
    document.getElementById('tp-prev')?.addEventListener('click', () => gotoStop(Math.max(1, activeOrder - 1)));
    document.getElementById('tp-next')?.addEventListener('click', () => gotoStop(Math.min(stops.length, activeOrder + 1)));
    document.getElementById('tp-jump')?.addEventListener('change', (e) => gotoStop(Number(e.target.value)));

    // --- Scroll-spy ---
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) setActive(Number(entry.target.dataset.stop));
        });
    }, { rootMargin: '-45% 0px -45% 0px', threshold: 0 });
    document.querySelectorAll('.tour-stop').forEach(el => observer.observe(el));
})();
</script>
<x-google-maps-script callback="initTourMap" />
@endpush
