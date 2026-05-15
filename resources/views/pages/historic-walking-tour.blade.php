@extends('layouts.app')

@section('title', 'Historic Walking Tour')
@section('description', "Explore Downtown Bellefontaine's historic walking tour - architectural styles from the 1850s to today, told through bronze plaques on storefronts across the square.")

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    <div class="absolute inset-0">
        <img src="/images/pages/mckinley-street.jpg" alt="McKinley Street, the historic short street in Downtown Bellefontaine" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/40 to-primary-900/70"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <p class="text-accent-400 font-display text-lg sm:text-xl mb-3">Downtown Bellefontaine</p>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">Historic Walking Tour</h1>
        <p class="text-primary-200 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Bronze plaques, brick storefronts, and a story on nearly every corner. Take it at your own pace.
        </p>
    </div>
</section>

{{-- Story --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="relative order-2 md:order-1">
                <img src="/images/pages/transportation-museum.jpg" alt="Bellefontaine Transportation Museum building" class="rounded-xl shadow-lg w-full object-cover aspect-[4/3]">
                <div class="absolute -bottom-4 -left-4 bg-accent-500 text-white rounded-lg px-5 py-3 shadow-lg hidden sm:block">
                    <p class="font-display text-lg">Look Up. Look Around.</p>
                </div>
            </div>
            <div class="order-1 md:order-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">Preserving Our Story</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>Bellefontaine believes in preserving our city's rich history and sharing it with others. Here, the story of our small town is displayed through proud, historic structures.</p>
                    <p>Spanning architectural styles from the <strong class="text-theme-primary">1850s through brand-new construction</strong>, our buildings tell the tales of our people, our dedication to growth, and our vision for the future.</p>
                    <p>As you walk through our downtown, look for the bronze plaques displayed on the building fronts. They'll introduce you to a small piece of Bellefontaine's history -- and you'll meet our present-day merchants along the way.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- What to Look For --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">As You Walk</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">What to Look For</h2>
            <p class="text-theme-secondary mt-3 max-w-2xl mx-auto">Three things to keep an eye out for as you explore the historic square.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-accent-400 to-accent-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-medal text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-theme-primary mb-2">Bronze Plaques</h3>
                <p class="text-theme-secondary text-sm leading-relaxed">Mounted on building fronts around the square -- each one tells the story of who built it, what it housed, and how it shaped downtown.</p>
            </div>

            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-building-columns text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-theme-primary mb-2">Architectural Eras</h3>
                <p class="text-theme-secondary text-sm leading-relaxed">From mid-1800s Italianate facades to early 1900s Romanesque and modern restorations -- the square is a layered timeline of styles.</p>
            </div>

            <div class="bg-theme-primary rounded-2xl border border-theme p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-road text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-theme-primary mb-2">McKinley Street</h3>
                <p class="text-theme-secondary text-sm leading-relaxed">Reportedly America's shortest street and once the country's first concrete-paved road. Don't miss the photo op.</p>
            </div>
        </div>
    </div>
</section>

{{-- Notable Stops --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Don't Miss</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">A Few Notable Stops</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-theme-secondary rounded-2xl border border-theme overflow-hidden">
                <img src="/images/pages/mckinley-street.jpg" alt="McKinley Street" class="w-full h-48 object-cover">
                <div class="p-5">
                    <h3 class="font-bold text-theme-primary mb-2">McKinley Street</h3>
                    <p class="text-theme-secondary text-sm">A 30-foot block that earned its place in American road-paving history.</p>
                </div>
            </div>

            <div class="bg-theme-secondary rounded-2xl border border-theme overflow-hidden">
                <img src="/images/pages/transportation-museum.jpg" alt="Transportation Museum" class="w-full h-48 object-cover">
                <div class="p-5">
                    <h3 class="font-bold text-theme-primary mb-2">Transportation Museum</h3>
                    <p class="text-theme-secondary text-sm">A look at how rail and road shaped Bellefontaine and Logan County.</p>
                </div>
            </div>

            <div class="bg-theme-secondary rounded-2xl border border-theme overflow-hidden">
                <div class="w-full h-48 bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                    <i class="fa-duotone fa-light fa-masks-theater text-6xl text-white/80"></i>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-theme-primary mb-2">The Holland Theatre</h3>
                    <p class="text-theme-secondary text-sm">A rare atmospheric theater with a restored Dutch village interior -- still hosting live shows today.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-primary-700 dark:bg-primary-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <i class="fa-duotone fa-light fa-person-walking text-4xl text-accent-400 mb-4"></i>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to Start Walking?</h2>
        <p class="text-primary-200 mb-8 max-w-lg mx-auto">Use the downtown map to find the square, or get in touch with the Partnership for a printed walking-tour guide.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('pages.map') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-map-location-dot"></i>
                Open the Downtown Map
            </a>
            <a href="{{ route('pages.contact') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-transparent border-2 border-white/40 hover:bg-white/10 text-white font-semibold rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-envelope"></i>
                Request a Guide
            </a>
        </div>
    </div>
</section>
@endsection
