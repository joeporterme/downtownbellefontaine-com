@extends('layouts.app')

@section('title', 'Things to Do')
@section('description', 'Discover things to do in Downtown Bellefontaine, Ohio - from The Holland Theatre to Mad River Mountain, Indian Lake, and unique local experiences.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/pages/mckinley-street.jpg" alt="McKinley Street - The shortest street in America" class="w-full h-full object-cover opacity-30">
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Things to Do</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Whether you're looking for shopping, eating, or exploring, Bellefontaine offers you the whole package. Come discover why we're so loveable.
        </p>
    </div>
</section>

{{-- Story Section --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="relative order-2 md:order-1">
                <img src="/images/pages/transportation-museum.jpg" alt="Transportation Museum" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -right-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Adventure Awaits</p>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">A Good Time in a Small Town</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>Attend a show at <strong class="text-theme-primary">The Holland Theatre</strong>, hit the ski slopes at <strong class="text-theme-primary">Mad River Mountain</strong>, or soak up the sunshine on <strong class="text-theme-primary">Indian Lake</strong> -- we've got entertainment for every season.</p>
                    <p>There are hours' worth of a good time in our small town. It's a place tourists come to love and want to share with their friends. Let us give you something to talk about.</p>
                    <p>While you're here, explore everything Logan County has to offer at <a href="http://www.experiencelogancounty.com" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">experiencelogancounty.com</a></p>
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
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Local Attractions & Experiences</h2>
            <p class="text-theme-tertiary max-w-xl mx-auto">From live entertainment to outdoor adventures, here's what's waiting for you in Downtown Bellefontaine.</p>
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
                                <i class="fa-duotone fa-light fa-masks-theater text-4xl text-primary-300 dark:text-primary-700"></i>
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

        <div class="text-center mt-10">
            <a href="{{ route('businesses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-grid-2"></i>
                View All Businesses
            </a>
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="py-16 bg-primary-700 dark:bg-primary-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-calendar-star text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Have an Upcoming Event?</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Share your event with the Downtown Bellefontaine community. Create your free account and start posting events today.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-rocket"></i>
            Get Started
        </a>
    </div>
</section>
@endsection
