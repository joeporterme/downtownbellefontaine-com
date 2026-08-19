@extends('layouts.app')

@section('title', 'Downtown Bellefontaine, Ohio — Shop, Dine, Stay & Explore')
@section('description', 'Discover Downtown Bellefontaine, Ohio - your destination for local businesses, community events, and small-town charm in the heart of Logan County.')

@push('styles')
<style>
    /* Video hero styles */
    .hero-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Neutral charcoal scrim (less teal cast) with stronger top/bottom
           anchoring so headline + CTAs stay legible over the video. */
        background: linear-gradient(180deg, rgba(15, 18, 22, 0.55) 0%, rgba(15, 18, 22, 0.32) 45%, rgba(10, 12, 16, 0.72) 100%);
        z-index: 1;
    }

    /* Floating pineapple animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(3deg); }
    }

    .float-animation {
        animation: float 6s ease-in-out infinite;
    }

    .float-animation-delayed {
        animation: float 6s ease-in-out infinite;
        animation-delay: -3s;
    }

    /* Reveal animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .reveal {
        opacity: 0;
    }

    .reveal.active {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .reveal-left.active {
        animation: fadeInLeft 0.8s ease-out forwards;
    }

    .reveal-right.active {
        animation: fadeInRight 0.8s ease-out forwards;
    }

    .reveal-scale.active {
        animation: scaleIn 0.8s ease-out forwards;
    }

    /* Stagger delays */
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }

    /* Card hover effects */
    .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    /* Pineapple decorative elements */
    .pineapple-bg {
        background-image: url('/images/home/pineapple.svg');
        background-repeat: no-repeat;
        background-size: contain;
        opacity: 0.05;
    }

    /* Gradient text using accent colors (Atomic Tangerine #f3773d) */
    .gradient-text {
        background: linear-gradient(135deg, #f59260 0%, #f3773d 50%, #e25a1f 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Pulse animation for CTA using accent-500 (#f3773d = rgb 243, 119, 61) */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(243, 119, 61, 0.4); }
        50% { box-shadow: 0 0 40px rgba(243, 119, 61, 0.6); }
    }

    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    /* Ribbon animation now lives globally in resources/css/app.css (the ribbon
       is sitewide via components/photo-ribbon). */

    /* Explore Downtown — photographic bento mosaic */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 165px;
        gap: 1rem;
    }
    .bento-feature {
        grid-column: span 2;
        grid-row: span 2;
    }
    @media (min-width: 1024px) {
        .bento-grid {
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 250px;
        }
    }
    .bento-tile {
        position: relative;
        display: block;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
    }
    .bento-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .bento-tile:hover .bento-img,
    .bento-tile:focus-visible .bento-img {
        transform: scale(1.07);
    }
    .bento-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 25%, rgba(0,0,0,0.28) 55%, rgba(0,0,0,0.72) 100%);
        transition: background 0.4s ease;
    }
    .bento-tile:hover .bento-scrim {
        background: linear-gradient(180deg, rgba(1,117,127,0.10) 15%, rgba(0,0,0,0.45) 60%, rgba(0,0,0,0.80) 100%);
    }
    .bento-content {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.1rem 1.25rem;
    }
    .bento-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #fff;
        font-weight: 700;
        font-size: 1.35rem;
        line-height: 1.1;
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .bento-tile:hover .bento-label {
        transform: translateY(-4px);
    }
    .bento-sub {
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.85rem;
        margin-top: 0.15rem;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: opacity 0.4s ease, max-height 0.4s ease;
    }
    .bento-tile:hover .bento-sub,
    .bento-tile:focus-visible .bento-sub {
        opacity: 1;
        max-height: 2.5rem;
    }
    .bento-arrow {
        transition: transform 0.3s ease;
    }
    .bento-tile:hover .bento-arrow {
        transform: translateX(4px);
    }

    /* Respect users who prefer reduced motion: neutralize all decorative
       animations and the auto-scrolling ribbon, and reveal hidden content. */
    @media (prefers-reduced-motion: reduce) {
        .float-animation,
        .float-animation-delayed,
        .pulse-glow,
        .animate-scroll,
        .animate-bounce,
        .reveal,
        .reveal.active,
        .reveal-left.active,
        .reveal-right.active,
        .reveal-scale.active {
            animation: none !important;
        }

        /* Keep ribbon images visible (no transform) instead of scrolled off */
        .animate-scroll {
            transform: none !important;
        }

        /* Ensure reveal content is fully shown without its entrance animation */
        .reveal {
            opacity: 1 !important;
        }

        /* Stop the autoplaying hero video from looping motion */
        .hero-video {
            animation: none !important;
        }
    }
</style>
@endpush

@section('content')
{{-- Hero Section with Video Background --}}
<section class="relative min-h-[640px] md:min-h-[720px] lg:min-h-[75vh] flex items-center justify-center overflow-hidden">
    <video autoplay muted loop playsinline preload="metadata" poster="{{ asset('images/home/downtown-bellefontaine-1.jpg') }}" class="hero-video">
        <source src="/images/home/video-loop.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    {{-- Floating Pineapple Decorations (wrapper tilts, inner image floats) --}}
    <div class="absolute top-16 left-24 rotate-12 hidden md:block" aria-hidden="true">
        <img src="/images/home/pineapple.svg" alt="" class="w-28 md:w-40 opacity-30 float-animation">
    </div>
    <div class="absolute bottom-28 right-24 -rotate-12 hidden md:block" aria-hidden="true">
        <img src="/images/home/pineapple.svg" alt="" class="w-32 md:w-48 opacity-25 float-animation-delayed">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white py-20">
        <div class="reveal active">
            <span class="font-display text-3xl md:text-4xl text-accent-300 block mb-4">Welcome to</span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 reveal active delay-100">
            Downtown<br>
            <span class="gradient-text">Bellefontaine</span>
        </h1>
        <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto reveal active delay-200">
            Discover the heart of Logan County. Where <span class="whitespace-nowrap">small-town charm meets</span> vibrant community spirit in Ohio's highest city.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center reveal active delay-300">
            <a href="#explore-downtown" class="inline-flex items-center justify-center px-8 py-4 bg-accent-500 hover:bg-accent-400 text-white font-semibold rounded-full transition-all pulse-glow text-lg">
                <i class="fa-duotone fa-light fa-compass mr-2"></i>
                Explore Downtown
            </a>
            <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/50 hover:bg-white hover:text-primary-800 font-semibold rounded-full transition-all text-lg">
                <i class="fa-duotone fa-light fa-calendar-star mr-2"></i>
                Upcoming Events
            </a>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 reveal active delay-500">
            <div class="animate-bounce">
                <i class="fa-duotone fa-light fa-chevrons-down text-3xl text-white/60"></i>
            </div>
        </div>
    </div>
</section>

{{-- Welcome / Story Section --}}
<section class="py-16 md:py-20 bg-theme-secondary relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid md:grid-cols-2 gap-10 lg:gap-14 items-center">
            {{-- Image --}}
            <div class="relative order-2 md:order-1 reveal-left active">
                <img src="{{ asset('images/home/welcome-courthouse.jpg') }}" alt="The Logan County Courthouse and pineapple fountain at sunset in Downtown Bellefontaine" class="rounded-2xl shadow-xl w-full aspect-square object-cover" loading="lazy">
                <div class="absolute -bottom-5 -right-5 bg-accent-500 text-white rounded-xl px-5 py-3 shadow-lg hidden sm:block">
                    <span class="font-display text-lg">Ohio's most loveable downtown</span>
                </div>
            </div>

            {{-- Copy --}}
            <div class="order-1 md:order-2 reveal-right active">
                <span class="font-display text-2xl text-accent-500">Welcome to</span>
                <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-1 mb-5">Downtown Bellefontaine</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed">
                    <p>Tucked into the heart of Ohio, Downtown Bellefontaine is a charming small-town destination filled with historic character, locally owned shops, and unforgettable food and drinks. Whether you're wandering our brick-lined streets, catching a show at the historic Holland Theatre, or sipping coffee on a sunny corner, downtown invites you to slow down, explore, and stay awhile.</p>
                </div>

                {{-- Landmark highlights --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-7">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-accent-100 dark:bg-accent-900/40 flex items-center justify-center flex-shrink-0">
                            <i class="fa-duotone fa-light fa-droplet text-accent-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-theme-primary text-sm">The Fountain</p>
                            <p class="text-theme-tertiary text-xs">Bellefontaine — French for "beautiful fountain"</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                            <i class="fa-duotone fa-light fa-mountains text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-theme-primary text-sm">Campbell Hill</p>
                            <p class="text-theme-tertiary text-xs">Ohio's highest point — 1,549 feet</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center flex-shrink-0">
                            <i class="fa-duotone fa-light fa-road text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-theme-primary text-sm">America's First Concrete Street</p>
                            <p class="text-theme-tertiary text-xs">Court Avenue, poured in the 1890s</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-accent-100 dark:bg-accent-900/40 flex items-center justify-center flex-shrink-0">
                            <i class="fa-duotone fa-light fa-ruler-horizontal text-accent-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-theme-primary text-sm">America's Shortest Street</p>
                            <p class="text-theme-tertiary text-xs">McKinley Street — about 15 feet long</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Explore Downtown — photographic bento mosaic --}}
<section id="explore-downtown" class="scroll-mt-24 py-16 md:py-20 bg-theme-primary relative overflow-hidden">
    <div class="pineapple-bg absolute -right-32 -bottom-32 w-96 h-96"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-10">
            <span class="font-display text-2xl text-accent-500 reveal">Discover</span>
            <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-2 reveal delay-100">Explore Downtown</h2>
            <p class="text-theme-secondary mt-3 max-w-2xl mx-auto reveal delay-200">Shop, eat, stay, and play your way through Ohio's most loveable downtown.</p>
        </div>

        <div class="bento-grid reveal-scale active">
            {{-- Feature tile → interactive map --}}
            <a href="{{ route('pages.map') }}" class="bento-tile bento-feature" aria-label="Explore the interactive downtown map">
                <img src="{{ asset('images/home/explore-feature.jpg') }}" alt="Aerial view of Downtown Bellefontaine at dusk" class="bento-img" loading="lazy">
                <div class="bento-scrim"></div>
                <div class="bento-content">
                    <span class="font-display text-2xl md:text-3xl text-accent-300">Find your way around</span>
                    <span class="bento-label !text-2xl md:!text-4xl">Downtown Bellefontaine</span>
                    <p class="text-white/85 text-sm md:text-base mt-2 max-w-md hidden sm:block">Browse the interactive map to discover shops, restaurants, stays, and things to do.</p>
                    <span class="inline-flex items-center gap-2 mt-3 text-white font-semibold">
                        <i class="fa-duotone fa-light fa-map-location-dot text-accent-300"></i>
                        Explore the Map
                        <i class="fa-duotone fa-light fa-arrow-right bento-arrow"></i>
                    </span>
                </div>
            </a>

            {{-- Shop --}}
            <a href="{{ route('pages.places-to-shop') }}" class="bento-tile">
                <img src="{{ asset('images/home/cat-shop.jpg') }}" alt="Shopping in Downtown Bellefontaine" class="bento-img" loading="lazy">
                <div class="bento-scrim"></div>
                <div class="bento-content">
                    <span class="bento-label"><i class="fa-duotone fa-light fa-bag-shopping text-accent-300"></i> Shop</span>
                    <span class="bento-sub">Local boutiques &amp; makers</span>
                </div>
            </a>

            {{-- Eat --}}
            <a href="{{ route('pages.food-drinks') }}" class="bento-tile">
                <img src="{{ asset('images/home/cat-eat.jpg') }}" alt="Dining in Downtown Bellefontaine" class="bento-img" loading="lazy">
                <div class="bento-scrim"></div>
                <div class="bento-content">
                    <span class="bento-label"><i class="fa-duotone fa-light fa-utensils text-accent-300"></i> Eat</span>
                    <span class="bento-sub">Restaurants, cafés &amp; treats</span>
                </div>
            </a>

            {{-- Stay --}}
            <a href="{{ route('pages.stay') }}" class="bento-tile">
                <img src="{{ asset('images/home/cat-stay.jpg') }}" alt="Places to stay in Downtown Bellefontaine" class="bento-img" loading="lazy">
                <div class="bento-scrim"></div>
                <div class="bento-content">
                    <span class="bento-label"><i class="fa-duotone fa-light fa-bed-front text-accent-300"></i> Stay</span>
                    <span class="bento-sub">Lofts, hotels &amp; suites</span>
                </div>
            </a>

            {{-- Play --}}
            <a href="{{ route('pages.things-to-do') }}" class="bento-tile">
                <img src="{{ asset('images/home/cat-play.jpg') }}" alt="Things to do in Downtown Bellefontaine" class="bento-img" loading="lazy">
                <div class="bento-scrim"></div>
                <div class="bento-content">
                    <span class="bento-label"><i class="fa-duotone fa-light fa-masks-theater text-accent-300"></i> Play</span>
                    <span class="bento-sub">Theatre, museums &amp; more</span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- Upcoming Events Section --}}
<section class="py-20 bg-theme-primary relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
            <div>
                <span class="font-display text-2xl text-accent-500 reveal">What's Happening</span>
                <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-2 reveal delay-100">Upcoming Events</h2>
                <p class="text-theme-secondary mt-2 reveal delay-200">Join us for community gatherings and celebrations</p>
            </div>
            <a href="{{ route('events.index') }}" class="mt-4 md:mt-0 inline-flex items-center text-accent-600 hover:text-accent-700 font-semibold reveal delay-300">
                View All Events
                <i class="fa-duotone fa-light fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($upcomingEvents as $index => $event)
                <a href="{{ route('events.show', $event) }}" class="group bg-theme-secondary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index + 1) * 100 }}">
                    <div class="relative">
                        @if($event->featured_image)
                            <img src="{{ \App\Support\Media::url($event->featured_image) }}" alt="{{ $event->title }}" loading="lazy" decoding="async" width="400" height="192" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-800 dark:to-violet-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-calendar-star text-5xl text-violet-300 dark:text-violet-600"></i>
                            </div>
                        @endif

                        {{-- Date Badge --}}
                        <div class="absolute top-4 left-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 text-center min-w-[70px]">
                            <span class="block text-sm font-bold text-accent-600 uppercase">{{ $event->event_date->format('M') }}</span>
                            <span class="block text-2xl font-bold text-theme-primary">{{ $event->event_date->format('d') }}</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-theme-primary group-hover:text-accent-600 transition-colors mb-2">{{ $event->title }}</h3>

                        <div class="flex items-center text-theme-tertiary text-sm mb-2">
                            <i class="fa-duotone fa-light fa-clock mr-2 text-accent-500"></i>
                            {{ $event->event_date->format('l') }}
                            @if($event->start_time)
                                at {{ $event->formatted_time }}
                            @endif
                        </div>

                        @if($event->location_name)
                            <div class="flex items-center text-theme-tertiary text-sm">
                                <i class="fa-duotone fa-light fa-location-dot mr-2 text-accent-500"></i>
                                {{ $event->location_name }}
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-theme-secondary rounded-2xl p-12 text-center">
                    <i class="fa-duotone fa-light fa-calendar-xmark text-5xl text-theme-tertiary mb-4"></i>
                    <p class="text-theme-secondary text-lg">No upcoming events at this time.</p>
                    <p class="text-theme-tertiary mt-2">Check back soon for community gatherings!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Latest from the Blog Section --}}
<section class="py-20 bg-gradient-to-b from-primary-50 to-white dark:from-primary-950 dark:to-gray-900 relative overflow-hidden">
    <div class="pineapple-bg absolute -right-32 top-0 w-80 h-80 transform -rotate-12"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500 reveal">Stay Connected</span>
            <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-2 reveal delay-100">Latest News & Stories</h2>
            <p class="text-theme-secondary mt-2 reveal delay-200">What's happening in our community</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($latestPosts as $index => $post)
                <a href="{{ route('blog.show', $post) }}" class="group block bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index + 1) * 100 }}">
                    <div class="relative overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ \App\Support\Media::url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async" width="400" height="192" class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-newspaper text-4xl text-accent-300 dark:text-accent-600"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center text-sm text-theme-tertiary mb-3">
                            <i class="fa-duotone fa-light fa-calendar mr-2"></i>
                            {{ $post->published_at->format('M d, Y') }}
                        </div>

                        <h3 class="text-lg font-bold text-theme-primary group-hover:text-accent-600 transition-colors mb-2 line-clamp-2">{{ $post->title }}</h3>

                        @if($post->seo_description)
                            <p class="text-theme-secondary text-sm line-clamp-2">{{ $post->seo_description }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-8 text-theme-secondary">
                    <i class="fa-duotone fa-light fa-newspaper text-5xl text-theme-tertiary mb-4"></i>
                    <p>No blog posts yet. Stay tuned for community updates!</p>
                </div>
            @endforelse
        </div>

        @if($latestPosts->isNotEmpty())
            <div class="text-center mt-12">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-newspaper"></i> Read the Blog
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Featured Businesses Section --}}
<section class="py-20 bg-gradient-to-b from-theme-secondary to-theme-primary relative overflow-hidden">
    <div class="pineapple-bg absolute -left-48 top-20 w-96 h-96 transform rotate-12"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
            <div>
                <span class="font-display text-2xl text-accent-500 reveal">Shop Local</span>
                <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-2 reveal delay-100">Featured Businesses</h2>
                <p class="text-theme-secondary mt-2 reveal delay-200">Discover what makes our downtown special</p>
            </div>
            <a href="{{ route('businesses.index') }}" class="mt-4 md:mt-0 inline-flex items-center text-accent-600 hover:text-accent-700 font-semibold reveal delay-300">
                View All
                <i class="fa-duotone fa-light fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredBusinesses as $index => $business)
                <a href="{{ route('businesses.show', $business) }}" class="group bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index % 3 + 1) * 100 }}">
                    <div class="relative overflow-hidden">
                        <x-business-card-image :business="$business" icon="fa-store" height="h-56" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2 mb-3">
                            @foreach($business->categories->take(2) as $category)
                                <span class="px-3 py-1 text-xs bg-accent-100 dark:bg-accent-900/30 text-accent-700 dark:text-accent-300 rounded-full font-medium">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                        <h3 class="text-xl font-bold text-theme-primary group-hover:text-accent-600 transition-colors">{{ $business->name }}</h3>
                        @if($business->locations->first())
                            <p class="text-theme-tertiary text-sm mt-2 flex items-center">
                                <i class="fa-duotone fa-light fa-location-dot mr-2 text-accent-500"></i>
                                {{ $business->locations->first()->address }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-theme-secondary">
                    <i class="fa-duotone fa-light fa-store-slash text-6xl text-theme-tertiary mb-4"></i>
                    <p>No businesses listed yet. Be the first to register!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Image gallery ribbon is now sitewide (components/photo-ribbon in the layout). --}}

{{-- Pineapple Heritage Note --}}
<section class="py-12 bg-accent-50 dark:bg-accent-950/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <img src="/images/home/pineapple-icon.png" alt="Pineapple - Symbol of Welcome" class="w-20 h-20 md:w-24 md:h-24 object-contain">
            <div>
                <h3 class="font-display text-xl text-accent-600 mb-2">The Pineapple Tradition</h3>
                <p class="text-theme-secondary">
                    The pineapple has been a symbol of hospitality and welcome in Downtown Bellefontaine for generations.
                    This historic icon represents our community's warm embrace of visitors and neighbors alike.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Intersection Observer for reveal animations
    document.addEventListener('DOMContentLoaded', function() {
        const reveals = document.querySelectorAll('.reveal');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        reveals.forEach(reveal => {
            observer.observe(reveal);
        });
    });
</script>
@endpush
