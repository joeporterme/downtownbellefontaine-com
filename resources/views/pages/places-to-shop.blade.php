@extends('layouts.app')

@section('title', 'Places to Shop')
@section('description', 'Shop local in Downtown Bellefontaine, Ohio - discover unique boutiques, antiques, toys, and specialty shops in our loveable small town.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Places to Shop"
    subtitle="We're big on shopping small and shopping local. Our specialty shops are a huge part of what makes Bellefontaine the most loveable town in Ohio."
    image="/images/pages/shopping-1.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Shop']]" />

{{-- Feature article --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-2 lg:order-1">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Shopping</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-theme-primary mt-2 mb-6 leading-tight">Shop Small.<br>Leave Full.</h2>
                <div class="space-y-5 text-theme-secondary text-lg leading-relaxed">
                    <p class="text-xl text-theme-primary font-medium">Some towns have a mall. We have a Main Street — and we'd take that trade every single time.</p>
                    <p>Downtown Bellefontaine is the kind of place where shopping still feels like an experience, not an errand. Park once, wander for hours. Historic brick buildings that sat empty a generation ago are now packed shoulder-to-shoulder with locally owned boutiques, and every door you push open belongs to a real person — usually the one standing behind the counter, ready to tell you the story of how their shop came to be.</p>
                </div>
            </div>
            <div class="relative order-1 lg:order-2">
                <img src="/images/pages/shopping-2.jpg" alt="Inside a Downtown Bellefontaine boutique"
                     data-lightbox data-lightbox-group="shop" data-lightbox-caption="Locally owned boutiques line Downtown Bellefontaine"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/5] cursor-zoom-in">
                <div class="absolute -bottom-5 -left-5 bg-accent-500 text-white rounded-xl px-5 py-3 shadow-lg hidden sm:block">
                    <span class="font-display text-xl">Shop Local</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What you'll find --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Aisle after aisle of one-of-a-kind</span>
            <p class="text-theme-secondary text-lg mt-2 leading-relaxed">You'll find on-trend fashion for women and kids, graphic tees that let you wear your Ohio pride, handcrafted bath and body goods, gourmet chocolates, curated gifts you won't see anywhere else, and antique shops stacked with treasures that reward the patient hunter. There's even a retail craft beer shop where you can browse the shelves with a pint in hand — because this is that kind of town.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach([
                ['fa-shirt', 'Fashion'],
                ['fa-gift', 'Gifts'],
                ['fa-pump-soap', 'Bath & Body'],
                ['fa-candy-bar', 'Chocolates'],
                ['fa-treasure-chest', 'Antiques'],
                ['fa-beer-mug', 'Craft Beer'],
            ] as [$icon, $label])
                <div class="bg-theme-primary rounded-xl border border-theme p-5 text-center card-hover">
                    <i class="fa-duotone fa-light {{ $icon }} text-3xl text-primary-500 mb-3"></i>
                    <p class="font-semibold text-theme-primary text-sm">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pull quote + pro tip --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <blockquote class="text-center">
            <p class="font-display text-3xl sm:text-4xl text-theme-primary leading-snug">"Our shop owners aren't competitors — they're collaborators."</p>
            <p class="text-theme-secondary mt-4 max-w-2xl mx-auto leading-relaxed">They'll happily send you two doors down to a neighbor's store if that's where the perfect find is waiting. That small-town generosity is baked into every block. So skip the crowded outlet centers and the big-box parking lots — grab a coffee, stroll America's most lovable downtown, and fill your bags with things that actually have a story behind them.</p>
        </blockquote>

        <div class="mt-10 flex items-start gap-4 bg-accent-50 dark:bg-accent-900/20 border border-accent-200 dark:border-accent-800 rounded-2xl p-6">
            <i class="fa-duotone fa-light fa-lightbulb text-2xl text-accent-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-theme-primary mb-1">Pro tip</p>
                <p class="text-theme-secondary leading-relaxed">Many shops keep small-town hours, and some are closed Mondays. Check ahead, then make a day of it.</p>
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
                    <x-business-card-image :business="$business" icon="fa-store" height="h-44" />

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
