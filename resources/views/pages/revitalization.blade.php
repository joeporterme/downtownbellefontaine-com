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

{{-- A bet on Main Street --}}
<section class="py-16 bg-theme-secondary">
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

{{-- Downtown today --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative order-2 lg:order-1">
                <img src="{{ asset('images/home/welcome-courthouse.jpg') }}" alt="Downtown Bellefontaine today"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] max-h-[440px] lg:max-h-none">
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

{{-- CTA --}}
<section class="py-16 bg-theme-secondary">
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
