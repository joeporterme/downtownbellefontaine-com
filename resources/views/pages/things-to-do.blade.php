@extends('layouts.app')

@section('title', 'Things to Do')
@section('description', 'Discover things to do in Downtown Bellefontaine, Ohio - from The Holland Theatre to Mad River Mountain, Indian Lake, and unique local experiences.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Downtown Bellefontaine"
    title="Things to Do"
    subtitle="Whether you're looking for shopping, eating, or exploring, Bellefontaine offers you the whole package. Come discover why we're so loveable."
    image="/images/pages/mckinley-street.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Things to Do']]" />

{{-- Lead --}}
<section class="py-16 md:py-24 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="order-2 lg:order-1">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Things to Do</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-theme-primary mt-2 mb-6 leading-tight">Big Fun. Small Town.<br>No Contest.</h2>
                <div class="space-y-5 text-theme-secondary text-lg leading-relaxed">
                    <p class="text-xl text-theme-primary font-medium">Nobody comes to Bellefontaine to sit still.</p>
                    <p>Four seasons. One small town. Zero excuses to be bored. While you're here, explore everything Logan County has to offer at <a href="http://www.experiencelogancounty.com" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">experiencelogancounty.com</a>.</p>
                </div>
            </div>
            <div class="relative order-1 lg:order-2">
                <img src="/images/pages/transportation-museum.jpg" alt="Things to do in Downtown Bellefontaine"
                     data-lightbox data-lightbox-group="play" data-lightbox-caption="There's a good time around every corner downtown"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/5] cursor-zoom-in">
                <div class="absolute -bottom-5 -left-5 bg-accent-500 text-white rounded-xl px-5 py-3 shadow-lg hidden sm:block">
                    <span class="font-display text-xl">Adventure Awaits</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Downtown entertainment lineup --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <span class="font-display text-2xl sm:text-3xl text-accent-500">Start downtown</span>
            <p class="text-theme-secondary text-lg mt-2 leading-relaxed">The entertainment lineup reads like a city ten times our size — plus public art, photo ops, and a story around every corner.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach([
                ['fa-axe', 'Axe Throwing'],
                ['fa-door-closed', 'Escape Room'],
                ['fa-golf-flag-hole', 'Indoor Mini Golf'],
                ['fa-crosshairs', 'Laser Tag'],
                ['fa-bowling-ball-pin', 'Duckpin Bowling'],
                ['fa-camera-retro', 'Photo Ops'],
                ['fa-landmark-dome', 'History Museum'],
                ['fa-person-walking', 'Historic Walking Tour'],
                ['fa-masks-theater', 'Live Shows'],
            ] as [$icon, $label])
                <div class="bg-theme-primary rounded-xl border border-theme p-5 text-center card-hover">
                    <i class="fa-duotone fa-light {{ $icon }} text-3xl text-primary-500 mb-3"></i>
                    <p class="font-semibold text-theme-primary text-sm">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- The Holland Theatre --}}
<section class="py-16 md:py-20 bg-primary-800 dark:bg-primary-950 text-white relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative">
                <img src="{{ \App\Support\Media::url('gallery/holland-theatre.jpg') }}" alt="The Holland Theatre lit at night"
                     data-lightbox data-lightbox-group="play" data-lightbox-caption="The Holland Theatre — the only atmospheric Dutch-style theater in America"
                     class="rounded-2xl shadow-2xl w-full object-cover aspect-[4/3] cursor-zoom-in">
            </div>
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-300">The crown jewel</span>
                <h2 class="text-3xl sm:text-4xl font-bold mt-2 mb-5 leading-tight">The Holland Theatre</h2>
                <div class="space-y-4 text-primary-100 text-lg leading-relaxed">
                    <p>Opened in 1931 as a grand movie palace, it's the only atmospheric theater in America built in 17th-century Dutch style — stepping inside feels like wandering into a Dutch village at twilight.</p>
                    <p>Lovingly restored and back in action, it hosts live shows and performances all year long. It's not just a venue; it's a time machine.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- History underfoot --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">History buff?</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1 mb-4">You're standing on it</h2>
                <div class="space-y-4 text-theme-secondary text-lg leading-relaxed">
                    <p>Bellefontaine is home to <strong class="text-theme-primary">America's first concrete street</strong>, poured in the 1890s and still there beside the courthouse — arguably the most walkable piece of history in Ohio.</p>
                    <p>A self-guided walking tour connects the downtown's beautifully restored architecture, and the county history center, housed in a stunning mansion nearby, digs even deeper.</p>
                </div>
                <a href="{{ route('pages.historic-walking-tour') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-person-walking"></i>
                    Take the Walking Tour
                </a>
            </div>
            <div class="relative">
                <img src="{{ \App\Support\Media::url('gallery/empire-block-day.jpg') }}" alt="Court Avenue — America's first concrete street"
                     data-lightbox data-lightbox-group="play" data-lightbox-caption="Court Avenue — America's oldest concrete street, poured in the 1890s"
                     class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3] cursor-zoom-in">
            </div>
        </div>
    </div>
</section>

{{-- Nearby Attractions & Farmer's Market --}}
{{-- TODO: verify external attraction URLs; add real photos when available (currently icon cards). --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-accent-500 font-display text-lg mb-2">Beyond the Sidewalks</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-3">Explore the Area</h2>
            <p class="text-theme-tertiary max-w-2xl mx-auto">From a downtown farmers market to Ohio's largest ski resort, Logan County is packed with adventures just minutes from downtown.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Logan County Farmer's Market (downtown, seasonal) --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-basket-shopping text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Logan County Farmer's Market</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">Downtown · Seasonal</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Fresh produce, baked goods, and local makers set up right downtown through the growing season — a weekly tradition worth planning your visit around.</p>
                    <a href="{{ route('businesses.show', 'the-logan-county-farmers-market') }}" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Learn more <i class="fa-duotone fa-light fa-arrow-right"></i></a>
                </div>
            </div>

            {{-- Mad River Mountain --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-person-skiing text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Mad River Mountain</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~15 min · Zanesfield</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Ohio's largest ski resort has welcomed skiers, snowboarders, and snow tubers for more than 60 years — the perfect winter day trip from downtown.</p>
                    <a href="https://www.skimadriver.com" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Visit site <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i></a>
                </div>
            </div>

            {{-- Indian Lake --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-sailboat text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Indian Lake</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~20 min · Russells Point</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">With over 30 miles of shoreline, Indian Lake is a summer favorite for boating, fishing, and waterfront dining.</p>
                    <a href="https://ohiodnr.gov/go-and-do/plan-a-visit/find-a-property/indian-lake-state-park" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Visit site <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i></a>
                </div>
            </div>

            {{-- Ohio Caverns --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-gem text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Ohio Caverns</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~20 min · West Liberty</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Discovered in 1897, Ohio's largest cave system dazzles with vivid crystal formations along guided underground tours.</p>
                    <a href="https://www.ohiocaverns.com" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Visit site <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i></a>
                </div>
            </div>

            {{-- Marmon Valley Farm --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-accent-500 to-accent-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-horse text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Marmon Valley Farm</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~15 min · Zanesfield</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Horseback riding trails, cozy cabins, wagon rides, and family-friendly experiences make this a beloved country getaway.</p>
                    <a href="https://www.marmonvalley.com" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Visit site <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i></a>
                </div>
            </div>

            {{-- Piatt Castles --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-chess-rook text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">The Piatt Castles</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~15 min · West Liberty</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Tour Mac-A-Cheek and Mac-O-Chee — 19th-century homes full of towers, hand-carved woodwork, and stained glass.</p>
                    <a href="https://www.piattcastles.org" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-medium text-sm hover:underline">Visit site <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-xs"></i></a>
                </div>
            </div>

            {{-- Campbell Hill --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-mountain text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Campbell Hill</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~5 min · Bellefontaine</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">The highest point in Ohio at 1,549 feet — a quick drive from downtown for the classic "top of the state" photo.</p>
                </div>
            </div>

            {{-- Simon Kenton Trail --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-success-400 to-success-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-person-biking text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Simon Kenton Trail</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">In &amp; around town</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A paved rail-trail for biking, walking, and running that threads right through Bellefontaine and out into the countryside.</p>
                </div>
            </div>

            {{-- Mary Rutan Park --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-success-400 to-success-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-tree text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Mary Rutan Park</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~5 min · Bellefontaine</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A local favorite for playgrounds, ball fields, shelters, and summer concerts — easy green space minutes from downtown.</p>
                </div>
            </div>

            {{-- YMCA Camp Willson --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-campground text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">YMCA Camp Willson</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~15 min · Bellefontaine</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Overnight and day camps, horseback riding, and outdoor adventure on hundreds of acres of Logan County countryside.</p>
                </div>
            </div>

            {{-- Camp Myeerah --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-success-400 to-success-600 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-campfire text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Camp Myeerah</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~15 min · Zanesfield</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">A wooded retreat for youth camps, group getaways, and outdoor learning tucked into the hills east of town.</p>
                </div>
            </div>

            {{-- Golf: Liberty Hills & Cherokee Hills --}}
            <div class="bg-theme-secondary rounded-xl shadow border border-theme overflow-hidden flex flex-col">
                <div class="h-28 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-golf-ball-tee text-4xl text-white/90"></i>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-theme-primary mb-1">Liberty Hills &amp; Cherokee Hills Golf</h3>
                    <p class="text-xs text-accent-600 font-semibold uppercase tracking-wide mb-2">~10 min · Logan County</p>
                    <p class="text-theme-secondary text-sm leading-relaxed flex-grow">Two scenic public courses just outside town — rolling fairways and honest small-town green fees for a relaxed round.</p>
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
                    <x-business-card-image :business="$business" icon="fa-masks-theater" height="h-44" />

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
