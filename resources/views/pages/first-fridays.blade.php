@extends('layouts.app')

@section('title', 'Downtown Days')
@section('description', 'Join us for Downtown Days in Downtown Bellefontaine - monthly community events celebrating local shops, food, music, and entertainment.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Downtown Days"
    subtitle="Themed celebrations all year long. Shop, eat, explore, and repeat."
    image="/images/downtown-days/festival-crowd-main-street.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Downtown Days']]" />

{{-- Story Section (condensed from the Partnership's Downtown Days write-up) --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Our story</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">From First Fridays to Downtown Days</h2>
                <div class="space-y-4 text-theme-secondary text-lg leading-relaxed">
                    <p>It started simply: make the first Friday of every month a reason to come downtown, see your neighbors and support local shops. The <strong class="text-theme-primary">Downtown Bellefontaine Partnership</strong> launched First Fridays to do just that, and it worked. Downtown found its rhythm.</p>
                    <p>But not everyone could make a Friday night, and shops wanted events lined up with their busiest days. So the Partnership retired First Fridays and relaunched the idea as <strong class="text-theme-primary">Downtown Days</strong>: themed celebrations that can land on any day of the month, and sometimes fill a whole weekend.</p>
                    <p>The goal hasn't changed. Give people a great reason to come downtown, help them discover places they might have walked right past, and send them home already planning the next visit.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/downtown-days/pineapple-palooza-friends.jpg" alt="Friends with the pineapple mascot at Pineapple Palooza"
                     data-lightbox data-lightbox-group="downtown-days" data-lightbox-caption="Pineapple Palooza"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] cursor-zoom-in">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Shop. Eat. Repeat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What makes them special --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">More than a party</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Why Downtown Days Work</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['fa-store', 'Shop hops & passports', 'Scavenger hunts, passport cards, tastings and giveaways pull you into storefronts. On the Chocolate Walk, you collect treats shop after shop.'],
                ['fa-people-group', 'Something for everyone', 'Some days are made for families, some for date night, some for out-of-town visitors. Think Galentine\'s cocktails, Pineapple Palooza and Hometown Christmas carriage rides.'],
                ['fa-handshake', 'A community effort', 'The City keeps the streets safe and open, the Logan County Chamber spreads the word, and local sponsors fund the fun. The Partnership, a 501(c)(3) nonprofit, ties it all together.'],
                ['fa-chart-line-up', 'Good for Main Street', 'Shops and restaurants often see holiday-level sales on event days, and first-time visitors keep coming back long after the streets are cleared.'],
            ] as [$icon, $heading, $copy])
                <div class="bg-theme-primary rounded-2xl border border-theme p-6">
                    <div class="w-12 h-12 mb-4 bg-gradient-to-br from-accent-400 to-accent-600 rounded-xl flex items-center justify-center">
                        <i class="fa-duotone fa-light {{ $icon }} text-xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-theme-primary mb-2">{{ $heading }}</h3>
                    <p class="text-theme-tertiary text-sm leading-relaxed">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Photo gallery --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="font-display text-2xl text-accent-500">In pictures</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">A Year of Downtown Days</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 auto-rows-[150px] md:auto-rows-[190px] grid-flow-dense gap-3 md:gap-4">
            @foreach([
                ['giant-snow-globe', 'Stepping inside the giant snow globe', 'col-span-2 row-span-2'],
                ['ice-skating-downtown', 'Ice skating downtown', ''],
                ['zipline-courthouse', 'Ziplining past the courthouse', 'row-span-2'],
                ['live-music-four-acre-stage', 'Live music on Main Street', ''],
                ['santa-float-opera-block', 'Santa rolls past the Opera Block', 'col-span-2'],
                ['kids-train-parade', 'The kids\' train in the parade', ''],
                ['easter-bunny-courthouse', 'Photos with the Easter Bunny', ''],
                ['lighted-parade-night', 'The lighted Christmas parade', 'col-span-2'],
                ['courthouse-carolers', 'Carolers on the courthouse steps', ''],
                ['bounce-house-street-party', 'A street party for the kids', ''],
                ['pineapple-palooza-night-stage', 'Pineapple Palooza after dark', 'col-span-2'],
                ['fountain-square-flags', 'The fountain square on a summer night', 'col-span-2'],
            ] as [$file, $caption, $span])
                <a href="/images/downtown-days/{{ $file }}.jpg" data-lightbox data-lightbox-group="downtown-days" data-lightbox-caption="{{ $caption }}"
                   class="group relative block overflow-hidden rounded-xl shadow-sm {{ $span }}">
                    <img src="/images/downtown-days/{{ $file }}.jpg" alt="{{ $caption }}" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-3 pt-8 pb-2 text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">{{ $caption }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- What to Expect --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">What to Expect</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">A Day on the Square</h2>
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

{{-- Upcoming Downtown Days (matches the new name and legacy "First Friday" titles) --}}
@php
    $upcomingFirstFridays = \App\Models\Event::approved()
        ->upcoming()
        ->where(function ($q) {
            $q->where('title', 'like', '%Downtown Days%')
              ->orWhere('title', 'like', '%First Friday%');
        })
        ->orderBy('event_date')
        ->take(3)
        ->get();
@endphp

@if($upcomingFirstFridays->isNotEmpty())
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Coming Up</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Upcoming Downtown Days</h2>
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

{{-- Full lineup --}}
<section class="py-12 bg-accent-50 dark:bg-accent-950/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-theme-primary mb-2">See the full Downtown Days lineup</h2>
        <p class="text-theme-secondary mb-6">Themes, dates, participating shops and sponsor info live on the official Downtown Days site.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="https://downtowndaysbtown.com/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-arrow-up-right-from-square"></i> downtowndaysbtown.com
            </a>
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-theme-primary border border-theme text-theme-primary font-semibold rounded-lg hover:border-primary-400 transition-colors">
                <i class="fa-duotone fa-light fa-calendar-star"></i> All downtown events
            </a>
        </div>
    </div>
</section>

@php
    $faqs = [
        ['q' => 'What is Downtown Days?', 'a' => 'Downtown Days is a year-round series of themed events in Downtown Bellefontaine, Ohio, organized by the Downtown Bellefontaine Partnership. Each one brings shopping specials, food, music and activities to the historic downtown district.'],
        ['q' => 'Is Downtown Days the same as First Fridays?', 'a' => 'Downtown Days grew out of First Fridays. The Partnership retired the First Fridays name and relaunched the series as Downtown Days so events can happen on any day of the month, not just the first Friday.'],
        ['q' => 'When is the next Downtown Day?', 'a' => 'Downtown Days happen throughout the year, with dates set for each theme. Check the upcoming list on this page, the downtown events calendar, or downtowndaysbtown.com for the next one.'],
        ['q' => 'Does it cost anything to attend?', 'a' => 'Most Downtown Days are free to walk around and enjoy. Some activities, such as special walks or tastings, may require a ticket, and those details are listed with each event.'],
        ['q' => 'Can I carry a drink around during Downtown Days?', 'a' => 'Yes, within the DORA. Downtown Bellefontaine has a Designated Outdoor Refreshment Area, so you can buy a drink in an approved DORA cup from a participating business and carry it within the district during DORA hours.'],
        ['q' => 'Where do I park?', 'a' => 'Public parking lots sit all around downtown, plus on-street parking. The Downtown Visitors Guide map shows every lot.'],
    ];
@endphp
<x-faq :items="$faqs" bg="bg-theme-secondary" card="bg-theme-primary" />

@endsection
