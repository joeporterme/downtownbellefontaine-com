@extends('layouts.app')

@section('title', 'The Transformation of Downtown Bellefontaine')
@section('description', 'How a fading Main Street became Ohio\'s most loveable downtown — the story of Downtown Bellefontaine\'s revitalization.')

@section('content')
{{-- DRAFT COPY — for team review. Verify specifics (dates, numbers, names) before publishing. --}}
<x-page-hero
    eyebrow="Our Story"
    title="The Transformation of Downtown Bellefontaine"
    subtitle="A Main Street that had all but gone dark — and the people who brought it roaring back to life."
    image="/images/home/downtown-bellefontaine-1.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Revitalization']]" />

{{-- Lead --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6 text-theme-secondary text-lg leading-relaxed">
            <p class="text-2xl text-theme-primary font-medium leading-snug">Not so long ago, downtown Bellefontaine was fading.</p>
            <p>Storefronts sat empty. Windows went dark. The historic buildings that had anchored Main Street for a century were quietly emptying out, one "for lease" sign at a time — the same slow story playing out in small towns all across America.</p>
            <p>But this town wasn't ready to give up on its downtown. And a handful of people believed that the heart of a community is worth fighting for.</p>
        </div>
    </div>
</section>

{{-- Then: historic downtown --}}
@php
    $then = [
        ['1897-courthouse-street-fair', 'Crowds pack the courthouse lawn for an 1897 street fair'],
        ['main-street-streetcar-1903', 'The 100 block of Main Street, ca. 1903, with the interurban trolley'],
        ['miltenberger-house', 'The Miltenberger House hotel'],
        ['north-main-1910', 'West side of North Main Street, ca. 1910'],
        ['grand-opera-house-block', 'The Grand Opera House Block'],
        ['empire-block', 'The Empire Block'],
        ['canby-building', 'The Canby Building'],
        ['main-street-north-postcard', 'Main Street looking north, vintage postcard'],
        ['strand-gala-opening-1950', 'Gala opening at the Strand, 139 S. Main Street, ca. 1950'],
        ['gc-murphy-main-street', 'G.C. Murphy 5 & 10 on Main Street'],
        ['main-street-1950s', 'Main Street in the 1950s'],
        ['parade-1956', 'A 1956 parade down Main Street'],
        ['sears-store', 'The Sears store downtown'],
        ['birds-eye-postcard', 'Bird\'s-eye view of downtown, vintage postcard'],
    ];
@endphp
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Where it all started</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-3">Downtown, then</h2>
            <p class="text-theme-secondary text-lg leading-relaxed">For generations, this was where Logan County came to shop, see a show, and run into everybody they knew. Streetcars, parades, packed theatres and five-and-dimes on every block.</p>
        </div>
        <div class="columns-2 md:columns-3 lg:columns-4 gap-3 md:gap-4">
            @foreach($then as [$file, $caption])
                <a href="/images/revitalization/then/{{ $file }}.jpg" data-lightbox data-lightbox-group="then" data-lightbox-caption="{{ $caption }}"
                   class="group relative block mb-3 md:mb-4 overflow-hidden rounded-xl shadow-sm break-inside-avoid">
                    <img src="/images/revitalization/then/{{ $file }}.jpg" alt="{{ $caption }}" loading="lazy"
                         class="w-full h-auto transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent px-3 pt-8 pb-2 text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                        {{ $caption }}
                    </div>
                </a>
            @endforeach
        </div>
        <p class="text-center text-xs text-theme-tertiary mt-4">Historic images courtesy of the Logan County Historical Society and community collections.</p>
    </div>
</section>

{{-- A bet on Main Street --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">A bet on Main Street</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">Reinvesting, one building at a time</h2>
                <div class="space-y-4 text-theme-secondary text-lg leading-relaxed">
                    <p>Instead of waiting for someone else to fix it, the community bet on itself. Historic buildings were bought back and painstakingly restored — brick by brick, storefront by storefront — and reopened with purpose.</p>
                    <p>Empty upper floors became stylish downtown lofts. Ground floors filled with locally owned shops, restaurants, and gathering places. The goal was simple: give people a reason to come downtown, and a reason to stay.</p>
                </div>
            </div>
            <div class="relative">
                <img src="{{ \App\Support\Media::url('gallery/empire-block-day.jpg') }}" alt="Restored historic storefronts in Downtown Bellefontaine"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] max-h-[440px] lg:max-h-none">
            </div>
        </div>
    </div>
</section>

{{-- Before & After --}}
@php
    $pairs = [
        // [file, title, note, business slug, aspect]
        ['brewfontaine', 'Brewfontaine', 'A 1950s diner, now Ohio\'s #1 beer bar', 'brewfontaine', 'aspect-[4/5]'],
        ['build-flying-pepper', 'BUILD + The Flying Pepper', 'Coworking upstairs, tacos and tequila downstairs', 'the-flying-pepper-cantina', 'aspect-[4/3]'],
        ['rainbow-row', 'Rainbow Row', 'A tired block, painted back to life', null, 'aspect-[4/3]'],
        ['six-hundred-downtown', 'Six Hundred Downtown', 'World-champion pizza and a patio full of people', 'six-hundred-downtown', 'aspect-[4/3]'],
        ['marketplace', 'Main Street Marketplace', 'Restored storefronts, local makers inside', 'main-street-marketplace', 'aspect-[4/3]'],
        ['axe-quacks', 'Axe Quacks', 'Axe throwing in the Opera Block', 'axe-quacks', 'aspect-[4/5]'],
        ['native', 'Native Coffee Co', 'A corner landmark, now the morning coffee stop', 'native-coffee-co', 'aspect-[4/5]'],
        ['antique-mall', 'The Olde Mint Antiques', '135 W. Columbus Ave.', 'the-olde-mint-antiques-llc', 'aspect-[4/5]'],
        ['125-w-columbus', '125 W. Columbus Ave.', 'Now home to Undertone Beauty Bar', 'undertone-beauty-bar', 'aspect-[4/3]'],
        ['anytime-fitness', 'Anytime Fitness + Loco Depot', 'A dark storefront, now open around the clock', 'anytime-fitness', 'aspect-[4/3]'],
        ['just-uneeq', 'Just U\'NeeQ', 'Fresh paint, new windows, new life', 'just-uneeq', 'aspect-[4/5]'],
        ['city-sweets', 'City Sweets and Creamery', 'Ice cream on the corner', 'city-sweets-and-creamery', 'aspect-square'],
        ['hickory-medical', 'Hickory Medical', 'A historic facade, restored', null, 'aspect-[4/5]'],
        ['iron-city', 'Iron City Sports Bar', 'A full exterior makeover', null, 'aspect-[4/3]'],
        ['poppy-seed', 'The Poppy Seed', 'A new storefront on an old block', 'the-poppy-seed', 'aspect-[4/5]'],
    ];
@endphp
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Before and after</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-3">Same buildings. Whole new downtown.</h2>
            <p class="text-theme-secondary text-lg leading-relaxed">Drag the slider on any photo to see what these storefronts looked like before the work began.</p>
        </div>
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6">
            @foreach($pairs as [$file, $title, $note, $slug, $aspect])
                <x-before-after
                    :before="'/images/revitalization/before-after/'.$file.'-before.jpg'"
                    :after="'/images/revitalization/before-after/'.$file.'-after.jpg'"
                    :title="$title" :note="$note" :aspect="$aspect"
                    :url="$slug ? route('businesses.show', $slug) : null" />
            @endforeach
        </div>
    </div>
</section>

{{-- Downtown today --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative order-2 lg:order-1">
                <img src="{{ asset('images/home/welcome-courthouse.jpg') }}" alt="Downtown Bellefontaine today"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] max-h-[440px] lg:max-h-none">
                <p class="absolute bottom-2 right-2 z-10 text-[11px] text-white/90 bg-black/40 px-2 py-0.5 rounded-md tracking-wide">Photo &copy; Mac McGinnis</p>
            </div>
            <div class="order-1 lg:order-2">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Downtown today</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">A downtown worth the drive</h2>
                <div class="space-y-4 text-theme-secondary text-lg leading-relaxed">
                    <p>Today, downtown Bellefontaine is alive again — world-champion pizza and craft beer, boutiques and coffee shops, a restored 1930s theatre, festivals on the square, and lofts where people wake up in the middle of it all.</p>
                    <p>What was nearly lost is now a destination — and a model that towns across the country come to study. We like to call it Ohio's most loveable downtown. Come see why.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- And now the streets are full --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">And now</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-3">The streets are full again</h2>
            <p class="text-theme-secondary text-lg leading-relaxed">Car shows, concerts, parades and Downtown Days bring the crowds back to the same blocks that once sat empty.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            @foreach([
                ['festival-crowd-main-street', 'A festival crowd fills Main Street'],
                ['car-show-main-street', 'The downtown car show'],
                ['christmas-parade-aerial', 'The Christmas parade from above'],
                ['fountain-square-crowd', 'A full house at the fountain square'],
            ] as [$file, $caption])
                <a href="/images/downtown-days/{{ $file }}.jpg" data-lightbox data-lightbox-group="now" data-lightbox-caption="{{ $caption }}"
                   class="group block overflow-hidden rounded-xl aspect-[4/3] shadow-sm">
                    <img src="/images/downtown-days/{{ $file }}.jpg" alt="{{ $caption }}" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('pages.first-fridays') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-semibold hover:underline">
                See what Downtown Days are all about <i class="fa-duotone fa-light fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="font-display text-2xl sm:text-3xl text-accent-500">See it for yourself</span>
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-6">The best chapter is the one you're standing in</h2>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('pages.plan-a-visit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-map-location-dot"></i> Plan a Visit
            </a>
            <a href="{{ route('pages.historic-walking-tour') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-theme-primary border border-theme text-theme-primary font-semibold rounded-lg hover:border-primary-400 transition-colors">
                <i class="fa-duotone fa-light fa-person-walking"></i> Take the Historic Walking Tour
            </a>
        </div>
    </div>
</section>
@endsection
