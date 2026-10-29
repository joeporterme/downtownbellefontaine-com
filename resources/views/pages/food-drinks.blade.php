@extends('layouts.app')

@section('title', 'Food & Drinks')
@section('description', 'Discover the best food and drinks in Downtown Bellefontaine, Ohio - from award-winning pizza at 600 Downtown to craft beer at local breweries.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Food & Drinks"
    subtitle="Around here, we treat everyone like a regular. Customer service is kind of our thing. Pull up a chair and stay a while."
    image="/images/pages/eat-hero.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Food & Drinks']]" />

{{-- Lead --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-2 lg:order-1">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Eat and Drink</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-theme-primary mt-2 mb-6 leading-tight">Come Hungry.<br>That's an Order.</h2>
                <div class="space-y-5 text-theme-secondary text-lg leading-relaxed">
                    <p class="text-xl text-theme-primary font-medium">For a small town, Downtown Bellefontaine has a food scene that surprises people.</p>
                    <p>Around here, local is the standard. Grab your <a href="{{ route('businesses.show', 'native-coffee-co') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">morning coffee</a> from an independent shop, meet friends for lunch, settle in for dinner and stick around for a drink. From <a href="{{ route('businesses.show', 'six-hundred-downtown') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">award-winning pizza</a> and <a href="{{ route('businesses.show', 'the-flying-pepper-cantina') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">tacos</a> to <a href="{{ route('businesses.show', 'kiyomi-sushi-steakhouse') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">sushi</a>, <a href="{{ route('businesses.show', '2gs-bbq') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">barbecue</a>, burgers, <a href="{{ route('businesses.show', 'brewfontaine') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">craft beer</a>, cocktails and plenty of reasons to <a href="#dessert" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">save room for dessert</a>, there's a lot packed into a few walkable blocks.</p>
                    <p>And because these restaurants, bars and coffee shops are locally owned, there's a good chance the person behind the counter, behind the bar or in the kitchen has a story worth hearing.</p>
                    <p class="text-xl font-bold text-accent-600 dark:text-accent-400">Come hungry. You'll have options.</p>
                </div>
            </div>
            <div class="relative order-1 lg:order-2">
                <img src="/images/pages/six-hundred-taste.jpg" alt="World-champion pizza at Six Hundred Downtown"
                     data-lightbox data-lightbox-group="eat" data-lightbox-caption="World-champion brick-oven pizza, made fresh downtown"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/5] max-h-[440px] lg:max-h-none cursor-zoom-in">
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
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">Three tables worth the trip</h2>
            <p class="text-theme-secondary text-lg leading-relaxed">Some places are worth planning a trip around. These three helped put Downtown Bellefontaine on the map, earning statewide and national attention while proving that some of the best food and drinks can be found in a small Ohio town. Consider these your starting point, then stick around and see what else downtown has cooking.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            {{-- Pizza --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <a href="{{ route('businesses.show', 'six-hundred-downtown') }}" class="block overflow-hidden">
                    <img src="/images/pages/six-hundred-pizza.jpg" alt="Brick-oven pizza" loading="lazy" class="h-52 w-full object-cover transition-transform duration-500 hover:scale-105">
                </a>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">World-champion pizza</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">Brick-Oven Pies</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Hand-spun, brick-oven pizza that's earned world championship titles and national television appearances. The dough is made fresh in-house daily and the ingredients sourced with obsessive care — worth the drive from anywhere.</p>
                    <a href="{{ route('businesses.show', 'six-hundred-downtown') }}" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-semibold text-sm hover:underline">Visit Six Hundred Downtown <i class="fa-duotone fa-light fa-arrow-right text-xs"></i></a>
                </div>
            </div>
            {{-- Craft beer --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <a href="{{ route('businesses.show', 'brewfontaine') }}" class="block overflow-hidden">
                    <img src="/images/pages/brewfontaine-taproom.jpg" alt="Inside Brewfontaine, Ohio's #1 beer bar" loading="lazy" class="h-52 w-full object-cover transition-transform duration-500 hover:scale-105">
                </a>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">Ohio's #1 beer bar</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">Craft Beer & Taproom</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A lovingly restored 1950s diner-turned-taproom pours one of the best draft lineups in the state — voted Ohio's #1 beer bar — and the sandwiches hold their own against the taps.</p>
                    <a href="{{ route('businesses.show', 'brewfontaine') }}" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-semibold text-sm hover:underline">Visit Brewfontaine <i class="fa-duotone fa-light fa-arrow-right text-xs"></i></a>
                </div>
            </div>
            {{-- Tacos & tequila --}}
            <div class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm flex flex-col card-hover">
                <a href="{{ route('businesses.show', 'the-flying-pepper-cantina') }}" class="block overflow-hidden">
                    <img src="/images/pages/flying-pepper.jpg" alt="Tacos at Flying Pepper" loading="lazy" class="h-52 w-full object-cover transition-transform duration-500 hover:scale-105">
                </a>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-1">Taco &amp; tequila bar</span>
                    <h3 class="text-xl font-bold text-theme-primary mb-2">Flying Pepper</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">An award-winning taco and tequila bar bringing bold, colorful flavor to downtown — creative street-style tacos, a deep tequila and mezcal list, and a mural-splashed space that's as fun as the menu.</p>
                    <a href="{{ route('businesses.show', 'the-flying-pepper-cantina') }}" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-semibold text-sm hover:underline">Visit The Flying Pepper Cantina <i class="fa-duotone fa-light fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Save room for dessert --}}
<section id="dessert" class="scroll-mt-24 py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative">
                <a href="{{ route('businesses.show', 'whits-frozen-custard') }}" class="block rounded-2xl overflow-hidden shadow-xl">
                    <img src="/images/pages/whits-custard.jpg" alt="Frozen custard at Whit's" loading="lazy"
                         class="w-full object-cover aspect-[4/3] max-h-[440px] lg:max-h-none transition-transform duration-500 hover:scale-105">
                </a>
            </div>
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Save room</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">Dessert is a whole category</h2>
                <p class="text-theme-secondary text-lg leading-relaxed mb-6">Hand-dipped chocolates and truffles, scratch-made donuts, homemade ice cream at <a href="{{ route('businesses.show', 'city-sweets-and-creamery') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">City Sweets</a>, frozen custard at <a href="{{ route('businesses.show', 'whits-frozen-custard') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">Whit's</a>, cookies from <a href="{{ route('businesses.show', 'one-eyed-cookie-lab') }}" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">One-Eyed Cookie Lab</a>, and coffee shops where the latte comes with a side of conversation.</p>
                <div class="grid grid-cols-2 gap-3 text-theme-secondary">
                    @foreach([['fa-candy-bar','Chocolates & Truffles'], ['fa-donut','Donuts'], ['fa-ice-cream','Ice Cream'], ['fa-cup-togo','Frozen Custard'], ['fa-mug-hot','Coffee'], ['fa-cookie-bite','Macarons']] as [$icon,$label])
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
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 max-w-2xl mx-auto">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Find your next favorite</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-3">Where to Eat & Drink</h2>
            <p class="text-theme-secondary text-lg">Coffee before shopping. A quick lunch. Dinner with friends. Drinks after work. Something sweet before heading home.</p>
            <p class="text-theme-tertiary mt-2">Explore the locally owned restaurants, bars, breweries, coffee shops and sweet spots that make Downtown Bellefontaine worth coming hungry for.</p>
        </div>

        {{-- Quick links: every spot, one tap away --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            @foreach($businesses as $business)
                <a href="{{ route('businesses.show', $business) }}" class="px-3.5 py-1.5 rounded-full text-sm font-medium bg-theme-primary border border-theme text-theme-secondary hover:border-primary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $business->name }}</a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($businesses as $business)
                <x-business-card :business="$business" icon="fa-utensils" bg="bg-theme-primary" />
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

{{-- FAQ (FAQPage structured data for search + AI answers) --}}
@php
    $faqs = [
        ['q' => 'Where can I find the best food in Bellefontaine, Ohio?', 'a' => 'Start downtown. Within a few walkable blocks you will find world-champion pizza at Six Hundred Downtown, craft beer at Brewfontaine, tacos and tequila at The Flying Pepper Cantina, sushi at Kiyomi, barbecue at 2Gs BBQ, plus coffee shops, bars and dessert spots, all locally owned.'],
        ['q' => 'Is the pizza at Six Hundred Downtown really world champion?', 'a' => 'Yes. Six Hundred Downtown\'s hand-spun, brick-oven pizza has earned world championship titles and national television appearances, and the dough is made fresh in house every day.'],
        ['q' => 'What is Brewfontaine?', 'a' => 'Brewfontaine is a craft beer bar and restaurant in a restored 1950s diner downtown. It has been voted Ohio\'s #1 beer bar and pairs one of the best draft lists in the state with a full food menu.'],
        ['q' => 'Where can I get dessert downtown?', 'a' => 'Try City Sweets and Creamery for ice cream, Whit\'s Frozen Custard, and One-Eyed Cookie Lab for cookies. Native Coffee Co and Sweet Aromas Coffee are great stops for coffee and a treat.'],
        ['q' => 'Can I walk around downtown with a drink?', 'a' => 'Yes, inside the DORA. Downtown Bellefontaine has a Designated Outdoor Refreshment Area, so drinks bought in an approved DORA cup from a participating business can be carried within the district during DORA hours.'],
        ['q' => 'Are the restaurants downtown locally owned?', 'a' => 'Yes. Downtown\'s restaurants, bars, breweries and coffee shops are independent and locally owned, so there is a good chance the person serving you has a story worth hearing.'],
    ];
@endphp
<x-faq :items="$faqs" bg="bg-theme-primary" card="bg-theme-secondary" />

@endsection
