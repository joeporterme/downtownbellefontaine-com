@extends('layouts.app')

@section('title', $business->name)
@section('description', $business->description ? Str::limit(strip_tags($business->description), 160) : 'Learn more about ' . $business->name . ' in Downtown Bellefontaine, Ohio.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Link --}}
        <div class="mb-6">
            <a href="{{ route('businesses.index') }}" class="text-primary-600 hover:text-primary-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Directory
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                {{-- Featured Image --}}
                @if($business->featured_image)
                    <img src="{{ Storage::url($business->featured_image) }}" alt="{{ $business->name }}" class="w-full h-64 md:h-96 object-cover rounded-lg shadow mb-6">
                @endif

                {{-- Header --}}
                <div class="bg-theme-secondary rounded-lg shadow border border-theme p-6 mb-6">
                    <div class="flex items-start gap-4">
                        @if($business->logo)
                            <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }} logo" class="w-20 h-20 object-contain rounded">
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
                    <h2 class="text-lg font-semibold text-theme-primary mb-4">Contact Information</h2>

                    <div class="space-y-4">
                        {{-- Location --}}
                        @if($business->locations->first())
                            @php $location = $business->locations->first(); @endphp
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-primary-600 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-theme-secondary">{{ $location->address }}</p>
                                    <p class="text-theme-secondary">{{ $location->city }}, {{ $location->state }} {{ $location->zip }}</p>
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

                    {{-- Social Links --}}
                    @if($business->facebook || $business->instagram)
                        <div class="mt-6 pt-6 border-t border-theme">
                            <h3 class="text-sm font-medium text-theme-tertiary mb-3">Follow Us</h3>
                            <div class="flex gap-3">
                                @if($business->facebook)
                                    <a href="{{ $business->facebook }}" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-blue-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($business->instagram)
                                    <a href="{{ $business->instagram }}" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-pink-600 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Map --}}
                    @if($business->locations->first() && $business->locations->first()->latitude)
                        @php $location = $business->locations->first(); @endphp
                        <div class="mt-6 pt-6 border-t border-theme">
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($location->address . ', ' . $location->city . ', ' . $location->state . ' ' . $location->zip) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                Get Directions
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
