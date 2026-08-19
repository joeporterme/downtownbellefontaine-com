@extends('layouts.app')

@section('title', 'Meeting Spaces')
@section('description', 'Find the perfect meeting space in Downtown Bellefontaine - The Syndicate, Bella Vino, The Maxwell, Build Cowork, Putt and Play, and more.')

@php
    $venues = [
        [
            'name' => 'The Syndicate',
            'url' => 'https://syndicatedowntown.com/bookus/',
            'tagline' => 'Fine dining + event center',
            'description' => "One of Logan County's finest caterers and event centers. The Syndicate hosts larger gatherings with their fine dining and venue space.",
            'image' => null,
            'gradient' => 'from-accent-400 to-accent-600',
            'icon' => 'fa-utensils',
        ],
        [
            'name' => 'Bella Vino',
            'url' => 'http://bellavinoevents.com/',
            'tagline' => 'Old-world charm + curated wine',
            'description' => 'A truly unique destination hidden away in the heart of downtown -- old-world atmosphere with curated wine selections and catering options for your special event.',
            'image' => '/images/pages/bella-vino.jpg',
            'gradient' => 'from-rose-400 to-rose-600',
            'icon' => 'fa-wine-glass',
        ],
        [
            'name' => 'The Maxwell',
            'url' => 'https://themaxwellevents.com/',
            'tagline' => 'Premier event space',
            'description' => "A premier event space in the heart of Bellefontaine -- perfect for weddings, corporate events, and social gatherings. A stunning setting for life's most important moments.",
            'image' => null,
            'gradient' => 'from-primary-400 to-primary-600',
            'icon' => 'fa-champagne-glasses',
        ],
        [
            'name' => 'Build Cowork + Space',
            'url' => 'https://buildcowork.com/',
            'tagline' => 'Coworking + 4 conference rooms',
            'description' => 'Designed to inspire and connect small businesses and solopreneurs. Four styles of conference rooms with the highest-quality A/V and tech -- rent by the hour or reserve a room for the day.',
            'image' => null,
            'gradient' => 'from-sky-400 to-sky-600',
            'icon' => 'fa-laptop',
        ],
        [
            'name' => 'Putt and Play Golf Center',
            'url' => 'https://www.puttplaygolfcenter.com/',
            'tagline' => 'Work, then play',
            'description' => 'The perfect balance of work and play. Hold your meeting, then enjoy laser tag, mini golf, golf simulation, and VR rooms. Also a great spot for special birthday parties.',
            'image' => null,
            'gradient' => 'from-success-400 to-success-600',
            'icon' => 'fa-flag',
        ],
        [
            'name' => 'BUILD Exchange Room',
            'url' => 'https://buildcowork.com/',
            'tagline' => 'Flexible meeting room',
            'description' => 'A versatile downtown room for workshops, trainings, and team gatherings -- flexible seating, presentation-ready A/V, and a walkable location in the heart of downtown.',
            'image' => null,
            'gradient' => 'from-sky-400 to-sky-600',
            'icon' => 'fa-people-group',
        ],
        [
            'name' => 'Axe Quacks',
            'url' => null,
            'tagline' => 'Parties + group events',
            'description' => 'A lively spot for private parties, team-building, and group events downtown -- axe throwing and games make for a memorable, hands-on gathering.',
            'image' => null,
            'gradient' => 'from-primary-400 to-primary-600',
            'icon' => 'fa-axe',
        ],
    ];
@endphp

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Meeting Spaces"
    subtitle="Weddings, board meetings, birthday parties, all-day workshops -- downtown has a room for it."
    image="/images/pages/bella-vino.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Meeting Spaces']]" />

{{-- Intro --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">We've Got the Venue</h2>
        <p class="text-theme-secondary leading-relaxed">
            You've got business to take care of and we've got the venues to take care of you. Downtown Bellefontaine offers a variety of event spaces for whatever your event needs may be -- from meetings and corporate events to social gatherings and birthday parties.
        </p>
    </div>
</section>

{{-- Venues Grid --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Find a Fit</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Spaces You Can Book</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($venues as $venue)
                <div class="bg-theme-primary rounded-2xl border border-theme overflow-hidden hover:shadow-lg transition-shadow flex flex-col">
                    <div class="relative">
                        @if($venue['image'])
                            <img src="{{ $venue['image'] }}" alt="{{ $venue['name'] }}" class="w-full h-44 object-cover">
                        @else
                            <div class="w-full h-44 bg-gradient-to-br {{ $venue['gradient'] }} flex items-center justify-center">
                                <i class="fa-duotone fa-light {{ $venue['icon'] }} text-5xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <p class="text-xs uppercase tracking-wider font-semibold text-accent-600 mb-2">{{ $venue['tagline'] }}</p>
                        <h3 class="text-xl font-bold text-theme-primary mb-2">{{ $venue['name'] }}</h3>
                        <p class="text-theme-secondary text-sm leading-relaxed flex-1">{{ $venue['description'] }}</p>
                        @if(!empty($venue['url']))
                            <a href="{{ $venue['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 mt-4 text-accent-600 hover:text-accent-700 font-semibold text-sm">
                                Visit website
                                <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Helpful info --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-theme-secondary rounded-2xl border border-theme p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-accent-100 dark:bg-accent-900/40 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-people-group text-xl text-accent-600"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-1">Groups of All Sizes</h3>
                <p class="text-theme-tertiary text-sm">From intimate boardrooms to large ballroom events, there's a fit downtown.</p>
            </div>
            <div class="bg-theme-secondary rounded-2xl border border-theme p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-utensils text-xl text-primary-600"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-1">Catering Available</h3>
                <p class="text-theme-tertiary text-sm">Most venues offer in-house catering or partner with downtown restaurants.</p>
            </div>
            <div class="bg-theme-secondary rounded-2xl border border-theme p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-success-100 dark:bg-success-900/40 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-square-parking text-xl text-success-600"></i>
                </div>
                <h3 class="font-semibold text-theme-primary mb-1">Easy Parking</h3>
                <p class="text-theme-tertiary text-sm">Free parking lots and on-street spots within a short walk of every venue.</p>
            </div>
        </div>
    </div>
</section>

@endsection
