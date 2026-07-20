@extends('layouts.app')

@section('title', 'First Fridays')
@section('description', 'Join us for First Fridays in Downtown Bellefontaine - monthly community events celebrating local shops, food, music, and entertainment.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="First Fridays"
    subtitle="Once a month, the whole square comes alive. Shop, eat, explore -- and repeat."
    image="/images/pages/first-fridays.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'First Fridays']]" />

{{-- Story Section --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">A Standing Date with Downtown</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>You gotta love a good Friday. And the <strong class="text-theme-primary">Downtown Bellefontaine Partnership</strong> -- a nonprofit organization -- has committed to make every first Friday of the month one for the books.</p>
                    <p>Their mission: make Bellefontaine the place to be by improving the quality and quantity of commerce, unifying public and private sectors, and promoting historic preservation to enrich the cultural life of our community.</p>
                    <p>How do they do it? By hosting monthly downtown events that encourage people to come and support local shops in Logan County.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/pages/first-fridays.jpg" alt="First Fridays evening on the square" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Shop. Eat. Repeat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What to Expect --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">What to Expect</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">A Night on the Square</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-accent-400 to-accent-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-music text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-2">Live Music</h3>
                <p class="text-theme-tertiary text-sm">Local artists, street performers, and pop-up performances around the square.</p>
            </div>

            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-clock text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-2">Extended Hours</h3>
                <p class="text-theme-tertiary text-sm">Shops and restaurants stay open later so you can take your time exploring.</p>
            </div>

            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-wine-glass text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-2">DORA Cups</h3>
                <p class="text-theme-tertiary text-sm">Grab a DORA-approved drink from a participating spot and stroll the district.</p>
            </div>

            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-family text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-2">Family Friendly</h3>
                <p class="text-theme-tertiary text-sm">Bring the kids -- there's almost always something happening for the whole family.</p>
            </div>
        </div>
    </div>
</section>

{{-- Upcoming First Fridays --}}
@php
    $upcomingFirstFridays = \App\Models\Event::approved()
        ->upcoming()
        ->where('title', 'like', '%First Friday%')
        ->orderBy('event_date')
        ->take(3)
        ->get();
@endphp

@if($upcomingFirstFridays->isNotEmpty())
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Coming Up</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Upcoming First Fridays</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($upcomingFirstFridays as $event)
                <a href="{{ route('events.show', $event) }}" class="block bg-theme-secondary rounded-2xl border border-theme overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        @if($event->featured_image)
                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-44 object-cover">
                        @else
                            <div class="w-full h-44 bg-gradient-to-br from-violet-400 to-accent-500 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-party-horn text-5xl text-white"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 bg-white dark:bg-gray-800 rounded-lg shadow px-3 py-2 text-center min-w-[60px]">
                            <span class="block text-xs font-bold text-accent-600 uppercase">{{ $event->event_date->format('M') }}</span>
                            <span class="block text-xl font-bold text-theme-primary leading-tight">{{ $event->event_date->format('d') }}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-theme-primary">{{ $event->title }}</h3>
                        @if($event->start_time)
                            <p class="text-theme-tertiary text-sm mt-1 flex items-center gap-1.5">
                                <i class="fa-duotone fa-light fa-clock text-accent-500"></i>
                                {{ $event->formatted_time }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
