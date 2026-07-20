@extends('layouts.app')

@section('title', 'Food & Drinks')
@section('description', 'Discover the best food and drinks in Downtown Bellefontaine, Ohio - from award-winning pizza at 600 Downtown to craft beer at local breweries.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Food & Drinks"
    subtitle="Around here, we treat everyone like a regular. Customer service is kind of our thing. Pull up a chair and stay a while."
    image="/images/pages/bella-vino.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Food & Drinks']]" />

{{-- Lead --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-2 lg:order-1">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Eat & Drink</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-theme-primary mt-2 mb-6 leading-tight">Come Hungry.<br>That's an Order.</h2>
                <div class="space-y-5 text-theme-secondary text-lg leading-relaxed">
                    <p class="text-xl text-theme-primary font-medium">For a town this size, Bellefontaine punches absurdly above its weight at the table — and we're not shy about saying it.</p>
                    <p>Every kitchen downtown is locally owned. Every meal supports a neighbor. And every table comes with the kind of hospitality you can't franchise.</p>
                </div>
            </div>
            <div class="relative order-1 lg:order-2">
                <img src="/images/pages/six-hundred-pizza.jpg" alt="World-champion brick-oven pizza downtown"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="World-champion brick-oven pizza, made fresh downtown"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/5] cursor-zoom-in">
                <div class="absolute -bottom-5 -left-5 bg-accent-500 text-white rounded-xl px-5 py-3 shadow-lg hidden sm:block">
                    <span class="font-display text-xl">Taste the Love</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- The big three --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Start here</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">Three tables worth the trip</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            {{-- Pizza --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <img src="/images/pages/six-hundred-pizza.jpg" alt="Brick-oven pizza"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="Award-winning brick-oven pizza"
                     class="h-52 w-full object-cover cursor-zoom-in">
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">World-champion pizza</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">Brick-Oven Pies</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Hand-spun, brick-oven pizza that's earned world championship titles and national television appearances. The dough is made fresh in-house daily and the ingredients sourced with obsessive care — worth the drive from anywhere.</p>
                    <p class="text-theme-tertiary text-xs mt-3 italic">Six Hundred Downtown</p>
                </div>
            </div>
            {{-- Craft beer --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <img src="/images/pages/brewfontaine-taproom.jpg" alt="Inside Brewfontaine, Ohio's #1 beer bar"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="Inside Brewfontaine — Ohio's #1 beer bar"
                     class="h-52 w-full object-cover cursor-zoom-in">
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">Ohio's #1 beer bar</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">Craft Beer & Taproom</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A lovingly restored 1950s diner-turned-taproom pours one of the best draft lineups in the state — voted Ohio's #1 beer bar — and the sandwiches hold their own against the taps.</p>
                    <p class="text-theme-tertiary text-xs mt-3 italic">Brewfontaine</p>
                </div>
            </div>
            {{-- Fine dining --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <img src="/images/pages/bella-vino.jpg" alt="Elegant downtown dining"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="Elevated dining in a restored downtown space"
                     class="h-52 w-full object-cover cursor-zoom-in">
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">Elevated dining</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">White-Tablecloth Warmth</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A former newsstand reborn as an elegant dinner spot where big-city dining meets small-town warmth — complete with a bar built from salvaged glass and walls lined with vintage local newspapers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- And that's just the beginning --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="font-display text-2xl sm:text-3xl text-accent-500">And that's just the beginning</span>
        <p class="text-theme-secondary text-lg mt-2 mb-8 max-w-3xl mx-auto leading-relaxed">Slow-smoked barbecue. Classic diner burgers and milkshakes made the old-fashioned way. Colorful Mexican cantina fare with curated tequila flights. Fresh-caught fish. A brunch spot so delightfully weird you'll be talking about it for weeks.</p>
        <div class="flex flex-wrap justify-center gap-3">
            @foreach(['Barbecue', 'Diner Classics', 'Mexican Cantina', 'Fresh Fish', 'Brunch', 'Tequila Flights'] as $chip)
                <span class="px-4 py-2 rounded-full bg-theme-secondary border border-theme text-theme-secondary text-sm font-medium">{{ $chip }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- Save room for dessert --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative">
                <img src="/images/pages/whits-custard.jpg" alt="Frozen custard and sweet treats downtown"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="Save room — dessert is a whole category here"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] cursor-zoom-in">
            </div>
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Save room</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">Dessert is a whole category</h2>
                <p class="text-theme-secondary text-lg leading-relaxed mb-6">Scratch-made donuts, homemade ice cream in rotating flavors, frozen custard, Parisian-style macarons, and coffee shops where the latte comes with a side of conversation.</p>
                <div class="grid grid-cols-2 gap-3 text-theme-secondary">
                    @foreach([['fa-donut','Donuts'], ['fa-ice-cream','Ice Cream'], ['fa-cup-togo','Coffee'], ['fa-cookie-bite','Macarons']] as [$icon,$label])
                        <div class="flex items-center gap-2.5">
                            <i class="fa-duotone fa-light {{ $icon }} text-primary-500"></i>
                            <span class="text-sm font-medium">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Businesses Grid --}}
@if($businesses->isNotEmpty())
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Where to Eat & Drink</h2>
            <p class="text-theme-tertiary max-w-xl mx-auto">From craft breweries to frozen custard, explore the restaurants and eateries that make Downtown Bellefontaine delicious.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <x-business-card-image :business="$business" icon="fa-utensils" height="h-44" />

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

@endsection
