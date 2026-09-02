@extends('layouts.app')

@section('title', 'Stay')
@section('description', 'Where to stay in and around Downtown Bellefontaine, Ohio - hotels, inns, and lodging for your visit to the most loveable downtown.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Stay Awhile"
    subtitle="One day isn't enough. Make a weekend of it with a stay in Logan County's most loveable small town."
    image="/images/home/downtown-bellefontaine-2.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Stay']]" />

{{-- Lead --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-2 lg:order-1">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Where to Stay</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-theme-primary mt-2 mb-6 leading-tight">Don't Just Visit.<br>Wake Up Here.</h2>
                <div class="space-y-5 text-theme-secondary text-lg leading-relaxed">
                    <p class="text-xl text-theme-primary font-medium">Here's a little secret the day-trippers miss: downtown Bellefontaine is even better after dark — and the best way to experience it is to stay right in the middle of it.</p>
                    <p>However you stay, one night usually turns into a plan for the next visit. Consider yourself warned.</p>
                </div>
            </div>
            <div class="relative order-1 lg:order-2">
                <img src="/images/pages/stay-resteasy.jpg" alt="Wake up in the heart of Downtown Bellefontaine"
                     data-lightbox data-lightbox-group="stay" data-lightbox-caption="Wake up right in the heart of downtown"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/5] cursor-zoom-in">
                <div class="absolute -bottom-5 -left-5 bg-accent-500 text-white rounded-xl px-5 py-3 shadow-lg hidden sm:block">
                    <span class="font-display text-xl">Rest Easy</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Lofts above the storefronts --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative">
                <img src="/images/pages/stay-lofts.jpg" alt="Historic buildings with lofts above the storefronts"
                     data-lightbox data-lightbox-group="stay" data-lightbox-caption="Historic lofts and flats sit above the downtown storefronts"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] cursor-zoom-in">
            </div>
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Lofts and flats downtown</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">Steps from everything</h2>
                <p class="text-theme-secondary text-lg leading-relaxed mb-6">Above the storefronts and along the brick side streets, historic buildings have been transformed into stylish, fully furnished lofts and flats — exposed brick, soaring ceilings, thoughtfully designed spaces with full kitchens and every modern comfort. Each one has its own personality, and each one is steps from everything downtown has to offer.</p>
                <div class="grid grid-cols-2 gap-3 text-theme-secondary">
                    @foreach([['fa-block-brick','Exposed brick'], ['fa-kitchen-set','Full kitchens'], ['fa-arrows-up-to-line','Soaring ceilings'], ['fa-wifi','Modern comforts']] as [$icon,$label])
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

{{-- Picture it --}}
<section class="py-16 bg-primary-800 dark:bg-primary-950 relative overflow-hidden">
    <div class="pineapple-bg absolute -right-24 -bottom-24 w-80 h-80 opacity-[0.06]"></div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <span class="font-display text-2xl sm:text-3xl text-accent-300">Picture it</span>
        <p class="text-white text-2xl sm:text-3xl font-semibold leading-snug mt-3">
            Dinner and drinks without ever touching your car keys. A nightcap on Main Street. Then a short stroll "home" to a loft above the very street you just explored.
        </p>
        <p class="text-primary-200 text-lg mt-5">In the morning, coffee and fresh-baked pastries are waiting downstairs, and the whole town wakes up around you.</p>
    </div>
</section>

{{-- Basecamp for any trip --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">The perfect basecamp</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">Close to the action, far from ordinary</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            @foreach([
                ['fa-briefcase', 'Work Trips', 'Extended-stay options with real kitchens and real workspace beat a highway hotel room every time.'],
                ['fa-champagne-glasses', 'Girls\' Weekend', 'Shops, dinner, drinks, and a stylish loft to call home base — all within a walk.'],
                ['fa-person-skiing', 'Ski Trips', 'The slopes are just twenty minutes away — stay downtown and warm up in style.'],
                ['fa-water', 'Lake Getaways', 'A summer lake escape with downtown\'s food and nightlife minutes from the water.'],
                ['fa-mountains', 'Local Attractions', 'From Marmon Valley Farm to Ohio Caverns and more, make downtown your home base for a whole region of adventures.'],
            ] as [$icon, $title, $body])
                <div class="bg-theme-secondary rounded-2xl border border-theme p-6 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center mb-4">
                        <i class="fa-duotone fa-light {{ $icon }} text-xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-theme-primary mb-2">{{ $title }}</h3>
                    <p class="text-theme-secondary text-sm leading-relaxed">{{ $body }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-10 flex items-start gap-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-6 max-w-3xl mx-auto">
            <i class="fa-duotone fa-light fa-bed-front text-2xl text-primary-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-theme-primary mb-1">Prefer traditional lodging?</p>
                <p class="text-theme-secondary leading-relaxed">You'll find comfortable, convenient hotel options just minutes from the town center, too.</p>
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
                    <x-business-card-image :business="$business" icon="fa-bed-front" height="h-44" />

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

{{-- Cobblestone Hotel & Suites — about --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-theme-secondary rounded-2xl overflow-hidden border border-theme shadow-sm grid md:grid-cols-5">
            <div class="md:col-span-3 p-8 lg:p-10">
                <span class="text-xs font-semibold uppercase tracking-wide text-accent-600 mb-3 block">Hotel Partner</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-1">Cobblestone Hotel &amp; Suites</h2>
                <p class="text-sm text-theme-tertiary mb-6">Bellefontaine, Ohio</p>
                <p class="text-theme-secondary leading-relaxed mb-8">Just minutes from downtown, the Cobblestone Hotel &amp; Suites offers comfortable, modern rooms and friendly service — a convenient home base for your visit to Downtown Bellefontaine.</p>
                <a href="https://be.synxis.com/?Hotel=47214&amp;Chain=7721" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    Book Your Stay
                    <i class="fa-duotone fa-light fa-arrow-right"></i>
                </a>
            </div>
            <div class="md:col-span-2 bg-primary-600 text-white p-8 lg:p-10 flex flex-col justify-center">
                <i class="fa-duotone fa-light fa-hotel text-4xl mb-6 text-white/90"></i>
                <h3 class="text-xl font-bold mb-3">Cobblestone Hotels</h3>
                <p class="text-primary-100 mb-4 leading-relaxed">A trusted regional brand known for clean, modern, comfortable stays.</p>
                <p class="text-sm font-semibold mb-1">Locations nearby:</p>
                <p class="text-sm text-primary-100">Bellefontaine · Indian Lake · Urbana</p>
            </div>
        </div>
    </div>
</section>

@endsection
