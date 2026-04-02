@extends('business.layout')

@section('title', 'Dashboard')

@section('content')
{{-- Welcome Header --}}
<div class="mb-8">
    <h1 class="text-3xl font-bold text-theme-primary">Welcome back, {{ auth()->user()->name }}</h1>
    <p class="text-theme-tertiary mt-1">Manage your businesses and events from your portal.</p>
</div>

{{-- Quick Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-theme-primary rounded-xl border border-theme p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                <i class="fa-duotone fa-light fa-store text-primary-600 dark:text-primary-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-theme-primary">{{ $businesses->count() }}</p>
                <p class="text-xs text-theme-tertiary">Businesses</p>
            </div>
        </div>
    </div>
    <div class="bg-theme-primary rounded-xl border border-theme p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-success-100 dark:bg-success-900 flex items-center justify-center">
                <i class="fa-duotone fa-light fa-circle-check text-success-600 dark:text-success-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-theme-primary">{{ $businesses->where('status', 'approved')->count() }}</p>
                <p class="text-xs text-theme-tertiary">Approved</p>
            </div>
        </div>
    </div>
    <div class="bg-theme-primary rounded-xl border border-theme p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-accent-100 dark:bg-accent-900 flex items-center justify-center">
                <i class="fa-duotone fa-light fa-calendar-star text-accent-600 dark:text-accent-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-theme-primary">{{ $events->count() }}</p>
                <p class="text-xs text-theme-tertiary">Events</p>
            </div>
        </div>
    </div>
    <div class="bg-theme-primary rounded-xl border border-theme p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-warning-100 dark:bg-warning-900 flex items-center justify-center">
                <i class="fa-duotone fa-light fa-clock text-warning-600 dark:text-warning-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-theme-primary">{{ $businesses->where('status', 'pending')->count() + $events->where('status', 'pending')->count() }}</p>
                <p class="text-xs text-theme-tertiary">Pending</p>
            </div>
        </div>
    </div>
</div>

{{-- My Businesses --}}
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-theme-primary flex items-center gap-2">
            <i class="fa-duotone fa-light fa-store text-primary-500"></i>
            My Businesses
        </h2>
        <a href="{{ route('business.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-duotone fa-light fa-plus"></i>
            Add Business
        </a>
    </div>

    @if($businesses->isEmpty())
        <div class="bg-theme-primary rounded-xl border border-theme p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                <i class="fa-duotone fa-light fa-store text-3xl text-primary-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-theme-primary mb-2">No businesses yet</h3>
            <p class="text-theme-tertiary mb-4">Register your first business to get started.</p>
            <a href="{{ route('business.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-rocket"></i>
                Register Your Business
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($businesses as $business)
                <div class="bg-theme-primary rounded-xl border border-theme overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Business Image --}}
                    <div class="relative">
                        @if($business->featured_image)
                            <img src="{{ Storage::url($business->featured_image) }}" alt="{{ $business->name }}" class="w-full h-36 object-cover">
                        @else
                            <div class="w-full h-36 bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-store text-4xl text-primary-300 dark:text-primary-700"></i>
                            </div>
                        @endif
                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @switch($business->status)
                                @case('approved')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-success-500 text-white shadow">Approved</span>
                                    @break
                                @case('pending')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-warning-500 text-white shadow">Pending</span>
                                    @break
                                @case('rejected')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-danger-500 text-white shadow">Rejected</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-500 text-white shadow">Inactive</span>
                            @endswitch
                        </div>
                    </div>

                    {{-- Business Info --}}
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-theme-primary mb-1">{{ $business->name }}</h3>
                        @if($business->address)
                            <p class="text-sm text-theme-tertiary flex items-center gap-1.5 mb-3">
                                <i class="fa-duotone fa-light fa-location-dot text-primary-500"></i>
                                {{ $business->address }}, {{ $business->city }}
                            </p>
                        @endif

                        {{-- Categories --}}
                        @if($business->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($business->categories->take(3) as $cat)
                                    <span class="text-xs px-2 py-0.5 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-2">
                            <a href="{{ route('business.edit', $business) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fa-duotone fa-light fa-pencil"></i>
                                Edit
                            </a>
                            @if($business->status === 'approved')
                                <a href="{{ route('businesses.show', $business) }}" class="inline-flex items-center justify-center px-3 py-2 border border-theme text-theme-secondary hover:bg-theme-secondary text-sm rounded-lg transition-colors" target="_blank">
                                    <i class="fa-duotone fa-light fa-arrow-up-right-from-square"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- My Events --}}
<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-theme-primary flex items-center gap-2">
            <i class="fa-duotone fa-light fa-calendar-star text-accent-500"></i>
            My Events
        </h2>
        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-accent-500 hover:bg-accent-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fa-duotone fa-light fa-plus"></i>
            Submit Event
        </a>
    </div>

    @if($events->isEmpty())
        <div class="bg-theme-primary rounded-xl border border-theme p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-100 dark:bg-accent-900 mb-4">
                <i class="fa-duotone fa-light fa-calendar-plus text-3xl text-accent-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-theme-primary mb-2">No events yet</h3>
            <p class="text-theme-tertiary mb-4">Share your upcoming events with the community.</p>
            <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-500 hover:bg-accent-600 text-white font-medium rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-calendar-plus"></i>
                Submit Your First Event
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($events as $event)
                <div class="bg-theme-primary rounded-xl border border-theme overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        @if($event->featured_image)
                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-36 object-cover">
                        @else
                            <div class="w-full h-36 bg-accent-100 dark:bg-accent-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-calendar-star text-4xl text-accent-300 dark:text-accent-700"></i>
                            </div>
                        @endif

                        {{-- Date Badge --}}
                        <div class="absolute top-3 left-3 bg-white dark:bg-gray-800 rounded-lg shadow px-2.5 py-1.5 text-center min-w-[50px]">
                            <span class="block text-xs font-bold text-accent-600 uppercase">{{ $event->event_date->format('M') }}</span>
                            <span class="block text-lg font-bold text-theme-primary leading-tight">{{ $event->event_date->format('d') }}</span>
                        </div>

                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            @if($event->status === 'approved')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-success-500 text-white shadow">Approved</span>
                            @elseif($event->status === 'pending')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-warning-500 text-white shadow">Pending</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-danger-500 text-white shadow">Rejected</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-theme-primary mb-1">{{ $event->title }}</h3>
                        <p class="text-sm text-theme-tertiary flex items-center gap-1.5 mb-3">
                            <i class="fa-duotone fa-light fa-clock text-accent-500"></i>
                            {{ $event->event_date->format('l, M j') }}
                            @if($event->start_time) at {{ $event->formatted_time }} @endif
                        </p>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('events.edit', $event) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fa-duotone fa-light fa-pencil"></i>
                                Edit
                            </a>
                            @if($event->status === 'approved')
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center justify-center px-3 py-2 border border-theme text-theme-secondary hover:bg-theme-secondary text-sm rounded-lg transition-colors" target="_blank">
                                    <i class="fa-duotone fa-light fa-arrow-up-right-from-square"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
