@extends('layouts.app')

@section('title', 'Plan a Visit')
@section('description', 'Plan your visit to Downtown Bellefontaine, Ohio - sample itineraries, parking, lodging, and everything you need for a perfect day in the most loveable downtown.')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/home/downtown-bellefontaine-1.jpg" alt="Plan a visit to Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/40 to-primary-900/70"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Plan a Visit</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Everything you need to make the most of a day -- or a whole weekend -- in Ohio's most loveable downtown.
        </p>
    </div>
</section>

{{-- Intro --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">Come See Why We're So Loveable</h2>
        <p class="text-theme-secondary leading-relaxed">
            Bellefontaine sits at the highest point in Ohio, just an hour from Columbus and Dayton. Shopping, scratch-made dining, live entertainment at The Holland Theatre, outdoor adventures on Indian Lake and Mad River Mountain -- it's all here, packed into a walkable downtown. Use the guides below to start planning.
        </p>
    </div>
</section>

{{-- Itineraries --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Sample Itineraries</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">A Few Ways to Spend the Day</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Perfect Saturday --}}
            <div class="bg-theme-primary rounded-2xl border border-theme overflow-hidden">
                <div class="h-44 bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-sun text-5xl text-white"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-theme-primary mb-3">The Perfect Saturday</h3>
                    <ul class="space-y-2 text-theme-secondary text-sm">
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-mug-hot text-accent-500 mt-1"></i><span>Coffee and pastry to start the morning</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-bag-shopping text-accent-500 mt-1"></i><span>Browse the boutiques and antique shops</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-utensils text-accent-500 mt-1"></i><span>Lunch at one of our scratch-made kitchens</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-person-walking text-accent-500 mt-1"></i><span>Historic walking tour around the square</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-wine-glass text-accent-500 mt-1"></i><span>Dinner and a drink to wind down</span></li>
                    </ul>
                </div>
            </div>

            {{-- Family Day --}}
            <div class="bg-theme-primary rounded-2xl border border-theme overflow-hidden">
                <div class="h-44 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-family text-5xl text-white"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-theme-primary mb-3">Family Day Out</h3>
                    <ul class="space-y-2 text-theme-secondary text-sm">
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-train text-primary-500 mt-1"></i><span>Start at the Transportation Museum</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-gamepad-modern text-primary-500 mt-1"></i><span>Toys and games at The Fun Company</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-ice-cream text-primary-500 mt-1"></i><span>Treats at Whit's Frozen Custard</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-road text-primary-500 mt-1"></i><span>Snap a photo on McKinley Street</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-pizza-slice text-primary-500 mt-1"></i><span>Pizza dinner downtown</span></li>
                    </ul>
                </div>
            </div>

            {{-- First Friday --}}
            <div class="bg-theme-primary rounded-2xl border border-theme overflow-hidden">
                <div class="h-44 bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-party-horn text-5xl text-white"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-theme-primary mb-3">First Friday Night</h3>
                    <ul class="space-y-2 text-theme-secondary text-sm">
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-clock text-violet-500 mt-1"></i><span>Arrive early -- the square fills up fast</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-music text-violet-500 mt-1"></i><span>Live music and entertainment</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-wine-glass text-violet-500 mt-1"></i><span>DORA cup in hand, stroll the shops</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-store text-violet-500 mt-1"></i><span>Extended retail and restaurant hours</span></li>
                        <li class="flex gap-2"><i class="fa-duotone fa-light fa-link text-violet-500 mt-1"></i><a href="{{ route('pages.first-fridays') }}" class="hover:underline">Learn more about First Fridays</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Resources Grid --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Visitor Info</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Before You Come</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('pages.stay') }}" class="group block p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-accent-400 to-accent-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-bed-front text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-accent-600 transition-colors mb-1">Where to Stay</h3>
                <p class="text-sm text-theme-tertiary">Hotels, inns, and lakeside lodging</p>
            </a>

            <a href="{{ route('events.index') }}" class="group block p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-calendar-star text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-primary-600 transition-colors mb-1">What's Happening</h3>
                <p class="text-sm text-theme-tertiary">Upcoming events and festivals</p>
            </a>

            <a href="{{ route('pages.historic-walking-tour') }}" class="group block p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-violet-400 to-violet-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-person-walking text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-violet-600 transition-colors mb-1">Walking Tour</h3>
                <p class="text-sm text-theme-tertiary">Stroll our historic square</p>
            </a>

            <a href="{{ route('pages.dora') }}" class="group block p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all text-center">
                <div class="w-14 h-14 mx-auto mb-4 bg-gradient-to-br from-rose-400 to-rose-600 rounded-xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-wine-glass text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-rose-600 transition-colors mb-1">DORA District</h3>
                <p class="text-sm text-theme-tertiary">Rules for our outdoor refreshment area</p>
            </a>
        </div>
    </div>
</section>

{{-- Getting Here --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <span class="font-display text-2xl text-accent-500">Getting Here</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2 mb-4">Easy to Find, Easy to Park</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p><strong class="text-theme-primary">Bellefontaine, Ohio</strong> is about <strong class="text-theme-primary">60 minutes</strong> from Columbus, <strong class="text-theme-primary">75 minutes</strong> from Dayton, and a straight shot up US-33.</p>
                    <p>Free parking surrounds the downtown square -- find a spot, then explore on foot. Most shops, restaurants, and the Holland Theatre are within a few blocks of each other.</p>
                    <p>Looking for the broader Logan County experience? Visit <a href="https://www.experiencelogancounty.com" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">experiencelogancounty.com</a>.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/pages/mckinley-street.jpg" alt="Downtown Bellefontaine square" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">See You Soon</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-primary-700 dark:bg-primary-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-envelope text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Questions? We're Happy to Help.</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Reach out to the Downtown Bellefontaine Partnership for recommendations, group visits, or anything else.</p>
        <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-paper-plane"></i>
            Contact Us
        </a>
    </div>
</section>
@endsection
