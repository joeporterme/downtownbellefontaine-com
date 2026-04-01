@extends('layouts.app')

@section('title', 'Places to Shop')
@section('description', 'Shop local in Downtown Bellefontaine, Ohio - discover unique boutiques, antiques, toys, and specialty shops in our loveable small town.')

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/pages/shopping-1.jpg" alt="Shopping in Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Places to Shop</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            We're big on shopping small and shopping local. Our specialty shops are a huge part of what makes Bellefontaine the most loveable town in Ohio.
        </p>
    </div>
</section>

{{-- Story Section --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">Your One-Stop Shop for All Things Unique</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>We've got gadgets and gizmos of plenty to discover. Need some new clothes? <strong class="text-theme-primary">The Poppy Seed</strong> has it. Antiques to spruce up your home? <strong class="text-theme-primary">Olde Mint Antique</strong> has it. Toys and games for the kids? <strong class="text-theme-primary">The Fun Company</strong> has got that, too.</p>
                    <p>Whether you're looking for gifts to wow your friends and family or treating yourself to those must-haves you can't leave the store without, Bellefontaine is here for your ultimate shopping experience.</p>
                </div>
            </div>
            <div class="relative">
                <img src="/images/pages/shopping-2.jpg" alt="Unique shops in Downtown Bellefontaine" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -left-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Shop Local</p>
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
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Explore Our Shops</h2>
            <p class="text-theme-tertiary max-w-xl mx-auto">From boutiques to antique stores, discover what makes shopping in Downtown Bellefontaine a one-of-a-kind experience.</p>
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
                                <i class="fa-duotone fa-light fa-store text-4xl text-primary-300 dark:text-primary-700"></i>
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
        <i class="fa-duotone fa-light fa-store text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Own a Shop in Bellefontaine?</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Get your business listed in our directory for free. Reach more customers and join our growing downtown community.</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
            <i class="fa-duotone fa-light fa-rocket"></i>
            List Your Business
        </a>
    </div>
</section>
@endsection
