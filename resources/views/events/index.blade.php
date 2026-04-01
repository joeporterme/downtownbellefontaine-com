@extends('layouts.app')

@section('title', 'Events')
@section('description', 'Discover upcoming events in Downtown Bellefontaine, Ohio - festivals, community gatherings, First Fridays, and more.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-theme-primary">Upcoming Events</h1>
            @auth
                <a href="{{ route('events.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors">
                    Submit Event
                </a>
            @endauth
        </div>

        @if($events->isEmpty())
            <div class="bg-theme-secondary rounded-lg p-8 text-center">
                <p class="text-theme-secondary">No upcoming events at this time. Check back soon!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event) }}" class="bg-theme-secondary rounded-lg shadow border border-theme overflow-hidden hover:shadow-lg transition-shadow">
                        @if($event->featured_image)
                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-300 dark:text-primary-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="p-5">
                            <div class="flex items-center text-sm text-primary-600 dark:text-primary-400 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $event->event_date->format('l, F j, Y') }}
                                @if($event->start_time)
                                    <span class="mx-2">|</span>
                                    {{ $event->formatted_time }}
                                @endif
                            </div>

                            <h2 class="text-xl font-semibold text-theme-primary mb-2">{{ $event->title }}</h2>

                            @if($event->location_name)
                                <p class="text-theme-tertiary text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $event->location_name }}
                                </p>
                            @endif

                            @if($event->business)
                                <p class="text-theme-tertiary text-sm mt-2">
                                    Hosted by {{ $event->business->name }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
