@extends('layouts.app')

@section('title', $event->title)
@section('description', Str::limit(strip_tags($event->description), 160))

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('events.index') }}" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Events
        </a>

        @if($event->featured_image)
            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-64 md:h-96 object-cover rounded-lg mb-6">
        @endif

        <div class="bg-theme-secondary rounded-lg shadow border border-theme p-6 md:p-8">
            <div class="flex items-center text-primary-600 dark:text-primary-400 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="font-medium">{{ $event->event_date->format('l, F j, Y') }}</span>
                @if($event->start_time)
                    <span class="mx-2">|</span>
                    <span>{{ $event->formatted_time }}</span>
                @endif
            </div>

            <h1 class="text-3xl font-bold text-theme-primary mb-6">{{ $event->title }}</h1>

            @if($event->description)
                <div class="prose prose-lg max-w-none text-theme-secondary mb-6">
                    {!! $event->description !!}
                </div>
            @endif

            <div class="border-t border-theme pt-6 space-y-4">
                @if($event->location_name || $event->address)
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-theme-tertiary mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            @if($event->location_name)
                                <p class="font-medium text-theme-primary">{{ $event->location_name }}</p>
                            @endif
                            @if($event->full_address)
                                <p class="text-theme-secondary">{{ $event->full_address }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($event->business)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-theme-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <p class="text-theme-secondary">Hosted by <span class="font-medium text-theme-primary">{{ $event->business->name }}</span></p>
                        </div>
                    </div>
                @endif

                @if($event->more_info_url)
                    <div class="pt-4">
                        <a href="{{ $event->more_info_url }}" target="_blank" class="inline-flex items-center bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors">
                            More Information
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($event->latitude && $event->longitude)
            <div class="mt-6 bg-theme-secondary rounded-lg shadow border border-theme overflow-hidden">
                <div id="map" class="h-64 w-full"></div>
            </div>

            @push('scripts')
            <script>
                function initMap() {
                    const location = { lat: {{ $event->latitude }}, lng: {{ $event->longitude }} };
                    const map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 15,
                        center: location,
                    });
                    new google.maps.Marker({
                        position: location,
                        map: map,
                        title: '{{ $event->location_name ?? $event->title }}'
                    });
                }
            </script>
            <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap"></script>
            @endpush
        @endif
    </div>
</div>
@endsection
