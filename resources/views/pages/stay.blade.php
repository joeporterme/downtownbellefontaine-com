@extends('layouts.app')

@section('title', 'Stay')
@section('description', 'Where to stay in and around Downtown Bellefontaine, Ohio - hotels, inns, and lodging for your visit to the most loveable downtown.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/home/downtown-bellefontaine-2.jpg" alt="Stay in Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/40 to-primary-900/70"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Stay Awhile</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            One day isn't enough. Make a weekend of it with a stay in Logan County's most loveable small town.
        </p>
    </div>
</section>

{{-- Story Section --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">Wake Up Downtown</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>Between shopping, dining, live entertainment, and outdoor adventure on Indian Lake and at Mad River Mountain, Bellefontaine packs a lot into a small footprint. Spend the night and you'll have time to enjoy every bit of it.</p>
                    <p>From boutique stays right downtown to lakeside getaways just a few minutes out, there's a place to land that fits whatever brought you here.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/home/downtown-bellefontaine-3.jpg" alt="Downtown Bellefontaine after dark" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Rest Easy</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Businesses Grid --}}
@if($businesses->isNotEmpty())
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Where to Stay</h2>
            <p class="text-theme-tertiary max-w-xl mx-auto">Hotels, inns, and short-term stays in and around Downtown Bellefontaine.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="bg-theme-primary rounded-xl shadow border border-theme overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <div class="overflow-hidden">
                        @if($business->featured_image)
                            <img src="{{ Storage::url($business->featured_image) }}" alt="{{ $business->name }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif($business->logo)
                            <div class="w-full h-44 bg-gray-100 dark:bg-gray-800 flex items-center justify-center p-6">
                                <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="w-full h-44 bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-bed-front text-4xl text-primary-300 dark:text-primary-700"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-theme-primary mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $business->name }}</h3>

                        @if($business->address)
                            <p class="text-theme-tertiary text-sm flex items-center gap-1.5">
                                <i class="fa-duotone fa-light fa-location-dot text-primary-500 flex-shrink-0"></i>
                                <span class="truncate">{{ $business->address }}</span>
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@else
{{-- Empty-state placeholder when no lodging is approved yet --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-bed-front text-5xl text-primary-400 mb-4"></i>
        <h2 class="text-2xl font-bold text-theme-primary mb-3">Lodging coming soon</h2>
        <p class="text-theme-secondary">We're putting the finishing touches on our lodging directory. In the meantime, reach out to the Downtown Bellefontaine Partnership for recommendations.</p>
        <a href="{{ route('pages.contact') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-envelope"></i>
            Contact Us
        </a>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-16 bg-primary-700 dark:bg-primary-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-map-location-dot text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Planning Your Visit?</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Build the perfect Bellefontaine getaway with our sample itineraries and visitor info.</p>
        <a href="{{ route('pages.plan-a-visit') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-compass"></i>
            Plan a Visit
        </a>
    </div>
</section>
@endsection
