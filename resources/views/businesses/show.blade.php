@extends('layouts.app')

@section('title', $business->name)
@section('description', $business->description ? Str::limit(strip_tags($business->description), 160) : 'Learn more about ' . $business->name . ' in Downtown Bellefontaine, Ohio.')
@if($business->listingImageUrl)
    @section('og_image', \App\Support\Media::url($business->featured_image))
@endif

@push('head')
@php
    $loc = $business->primaryLocation ?? $business->locations->first();
    $ld = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $business->name,
        'url' => url()->current(),
        'description' => $business->description ? Str::limit(strip_tags($business->description), 300) : null,
        'image' => $business->listingImageUrl,
        'telephone' => $loc->phone ?? $business->phone ?? null,
        'address' => $loc ? array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $loc->address,
            'addressLocality' => $loc->city ?? 'Bellefontaine',
            'addressRegion' => $loc->state ?? 'OH',
            'postalCode' => $loc->zip,
            'addressCountry' => 'US',
        ]) : null,
        'geo' => ($loc && $loc->latitude && $loc->longitude) ? [
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $loc->latitude,
            'longitude' => (float) $loc->longitude,
        ] : null,
    ]);
@endphp
<script type="application/ld+json">
{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Link (+ admin edit shortcut) --}}
        <div class="mb-6 flex items-center justify-between gap-4">
            <a href="{{ route('businesses.index') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Directory
            </a>

            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ url('/admin/businesses/'.$business->id.'/edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-pen-to-square"></i>
                    Edit in Admin
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                {{-- Top image: the business's featured / curated photo. Street View
                     lives in the sidebar, so it is not used here. --}}
                @php
                    // Top hero = the featured photo (falling back to a legacy business-level
                    // override). The per-location override belongs to the sidebar, not here.
                    $topImage = $business->featured_image
                        ? \App\Support\Media::url($business->featured_image)
                        : ($business->listing_image ? \App\Support\Media::url($business->listing_image) : null);
                    $topCredit = (! $business->featured_image && $business->listing_image) ? $business->listing_image_credit : null;
                @endphp
                @if($topImage)
                    <figure class="mb-6">
                        <img src="{{ $topImage }}" alt="{{ $business->name }}" class="w-full h-64 md:h-96 object-cover rounded-lg shadow">
                        @if($topCredit)
                            <figcaption class="mt-1 text-xs text-theme-tertiary">Photo: {{ $topCredit }} · via Google</figcaption>
                        @endif
                    </figure>
                @endif

                {{-- Header --}}
                <div class="bg-theme-secondary rounded-lg shadow border border-theme p-6 mb-6">
                    <div class="flex items-start gap-4">
                        @if($business->avatarUrl)
                            <img src="{{ $business->avatarUrl }}" alt="{{ $business->name }} logo" class="w-20 h-20 object-cover rounded-full bg-white dark:bg-gray-900 ring-2 ring-white dark:ring-gray-900 shadow">

                        @endif
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-theme-primary">{{ $business->name }}</h1>

                            @if($business->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($business->categories as $category)
                                        <a href="{{ route('businesses.category', $category) }}" class="text-sm px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($business->description && $business->description !== '*')
                        <div class="mt-6 prose prose-primary dark:prose-invert max-w-none text-theme-secondary">
                            {!! nl2br(e($business->description)) !!}
                        </div>
                    @endif
                </div>

                {{-- Upcoming Events --}}
                @if($business->events->isNotEmpty())
                    <div class="bg-theme-secondary rounded-lg shadow border border-theme p-6">
                        <h2 class="text-xl font-semibold text-theme-primary mb-4">Upcoming Events</h2>
                        <div class="space-y-4">
                            @foreach($business->events as $event)
                                <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    @if($event->featured_image)
                                        <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-20 h-14 object-cover rounded">
                                    @else
                                        <div class="w-20 h-14 bg-primary-100 dark:bg-primary-900 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm text-primary-600 dark:text-primary-400">{{ $event->event_date->format('M j, Y') }}</p>
                                        <p class="font-medium text-theme-primary">{{ $event->title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-theme-secondary rounded-lg shadow border border-theme p-6 sticky top-6">
                    @php
                        $primaryLoc = $business->primaryLocation ?? $business->locations->first();
                        // A per-location override (Google photo / upload) replaces Street View here.
                        $locOverride = $primaryLoc?->listing_image;
                        $streetview = $primaryLoc?->streetview_image;
                        $sidebarImage = null; $sidebarIsSV = false; $sidebarLabel = null;
                        if ($locOverride) {
                            $sidebarImage = \App\Support\Media::url($locOverride);
                            $sidebarLabel = $primaryLoc?->listing_image_credit ? 'Photo · via Google' : null;
                        } elseif ($streetview) {
                            $sidebarImage = \App\Support\Media::url($streetview);
                            $sidebarIsSV = true;
                            $sidebarLabel = 'Street View';
                        }
                        $hasMap = $primaryLoc && $primaryLoc->latitude;
                    @endphp

                    {{-- Street View / override photo --}}
                    @if($sidebarImage)
                        <figure class="mb-4">
                            <img src="{{ $sidebarImage }}" alt="{{ $business->name }}" class="w-full h-44 object-cover rounded-lg border border-theme {{ $sidebarIsSV ? 'streetview-img' : '' }}">
                            @if($sidebarLabel)
                                <figcaption class="mt-1 text-xs text-theme-tertiary">{{ $sidebarLabel }}</figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Map --}}
                    @if($hasMap)
                        <div id="business-map"
                             data-lat="{{ $primaryLoc->latitude }}"
                             data-lng="{{ $primaryLoc->longitude }}"
                             data-title="{{ $business->name }}"
                             class="w-full h-56 rounded-lg overflow-hidden border border-theme mb-3 bg-gray-100 dark:bg-gray-800"></div>
                    @endif

                    {{-- Get Directions --}}
                    @if($primaryLoc && ($hasMap || $primaryLoc->address))
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($primaryLoc->address . ', ' . $primaryLoc->city . ', ' . $primaryLoc->state . ' ' . $primaryLoc->zip) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors mb-6">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Get Directions
                        </a>
                    @endif

                    {{-- Info --}}
                    <h2 class="text-lg font-semibold text-theme-primary mb-4 {{ ($sidebarImage || $hasMap) ? 'pt-6 border-t border-theme' : '' }}">Contact Information</h2>

                    <div class="space-y-4">
                        {{-- Location --}}
                        @if($primaryLoc)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-primary-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-theme-secondary">{{ $primaryLoc->address }}</p>
                                    <p class="text-theme-secondary">{{ $primaryLoc->city }}, {{ $primaryLoc->state }} {{ $primaryLoc->zip }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Phone --}}
                        @if($business->phone)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <a href="tel:{{ $business->phone }}" class="text-theme-secondary hover:text-primary-600">{{ $business->phone }}</a>
                            </div>
                        @endif

                        {{-- Social icons --}}
                        @php
                            $socials = array_filter([
                                'facebook' => $business->facebook_url,
                                'instagram' => $business->instagram_url,
                                'x-twitter' => $business->x_url,
                                'tiktok' => $business->tiktok_url,
                                'snapchat' => $business->snapchat_url,
                            ]);
                        @endphp
                        @if(count($socials))
                            <div class="flex flex-wrap items-center gap-2 pl-8">
                                @foreach($socials as $icon => $url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $icon === 'x-twitter' ? 'X' : ucfirst($icon) }}"
                                       class="w-9 h-9 rounded-full bg-primary-50 dark:bg-primary-900/40 text-primary-600 dark:text-primary-300 hover:bg-primary-600 hover:text-white flex items-center justify-center transition-colors">
                                        <i class="fa-brands fa-{{ $icon }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Email --}}
                        @if($business->email)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <a href="mailto:{{ $business->email }}" class="text-theme-secondary hover:text-primary-600 break-all">{{ $business->email }}</a>
                            </div>
                        @endif

                        {{-- Website --}}
                        @if($business->website)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary-600 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                                <a href="{{ $business->website }}" target="_blank" rel="noopener noreferrer" class="text-theme-secondary hover:text-primary-600 truncate">
                                    {{ parse_url($business->website, PHP_URL_HOST) }}
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Map init --}}
                    @if($hasMap)
                        @push('scripts')
                        <script>
                            function initBusinessMap() {
                                const el = document.getElementById('business-map');
                                if (!el || !window.google || !google.maps) return;
                                const pos = { lat: parseFloat(el.dataset.lat), lng: parseFloat(el.dataset.lng) };
                                if (isNaN(pos.lat) || isNaN(pos.lng)) return;

                                const map = new google.maps.Map(el, {
                                    center: pos,
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

                                new google.maps.Marker({
                                    position: pos,
                                    map,
                                    title: el.dataset.title,
                                    icon: {
                                        path: 'M 0,0 C -2,-4 -10,-10 -10,-20 a 10,10 0 1 1 20,0 c 0,10 -8,16 -10,20 z',
                                        fillColor: '#f3773d',
                                        fillOpacity: 1,
                                        strokeColor: '#ffffff',
                                        strokeWeight: 2,
                                        scale: 1,
                                        anchor: new google.maps.Point(0, 0),
                                    },
                                });
                            }
                        </script>
                        <x-google-maps-script callback="initBusinessMap" />
                        @endpush
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
