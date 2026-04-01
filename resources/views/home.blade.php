@extends('layouts.app')

@section('title', 'Welcome')
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
        background: linear-gradient(135deg, rgba(0, 42, 48, 0.9) 0%, rgba(1, 67, 76, 0.8) 50%, rgba(1, 92, 102, 0.75) 100%);
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

    /* Scrolling animation for image ribbon */
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .animate-scroll {
        animation: scroll 40s linear infinite;
        width: max-content;
    }

    .animate-scroll:hover {
        animation-play-state: paused;
    }
</style>
@endpush

@section('content')
{{-- Hero Section with Video Background --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <video autoplay muted loop playsinline class="hero-video">
        <source src="/images/home/video-loop.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    {{-- Floating Pineapple Decorations --}}
    <img src="/images/home/pineapple.svg" alt="" class="absolute top-20 left-10 w-16 md:w-24 opacity-20 float-animation hidden md:block" aria-hidden="true">
    <img src="/images/home/pineapple.svg" alt="" class="absolute bottom-32 right-10 w-20 md:w-32 opacity-15 float-animation-delayed hidden md:block" aria-hidden="true">

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white py-20">
        <div class="reveal active">
            <span class="font-display text-3xl md:text-4xl text-accent-300 block mb-4">Welcome to</span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 reveal active delay-100">
            Downtown<br>
            <span class="gradient-text">Bellefontaine</span>
        </h1>
        <p class="text-xl md:text-2xl text-white/90 mb-10 max-w-3xl mx-auto reveal active delay-200">
            Discover the heart of Logan County. Where small-town charm meets<br class="hidden md:inline">
            vibrant community spirit in Ohio's highest city.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center reveal active delay-300">
            <a href="{{ route('businesses.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-accent-500 hover:bg-accent-400 text-white font-semibold rounded-full transition-all pulse-glow text-lg">
                <i class="fa-duotone fa-light fa-magnifying-glass mr-2"></i>
                Explore Businesses
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

{{-- Quick Links Section --}}
<section class="py-16 bg-theme-primary relative overflow-hidden">
    <div class="pineapple-bg absolute -right-32 -bottom-32 w-96 h-96"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500 reveal">Discover</span>
            <h2 class="text-3xl md:text-4xl font-bold text-theme-primary mt-2 reveal delay-100">What's Downtown</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <a href="{{ route('pages.things-to-do') }}" class="group p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all card-hover text-center reveal delay-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-accent-400 to-accent-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform">
                    <i class="fa-duotone fa-light fa-masks-theater text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-accent-600 transition-colors">Things to Do</h3>
            </a>

            <a href="{{ route('pages.places-to-shop') }}" class="group p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all card-hover text-center reveal delay-200">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform">
                    <i class="fa-duotone fa-light fa-bag-shopping text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-primary-600 transition-colors">Places to Shop</h3>
            </a>

            <a href="{{ route('pages.food-drinks') }}" class="group p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all card-hover text-center reveal delay-300">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform">
                    <i class="fa-duotone fa-light fa-utensils text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-rose-600 transition-colors">Food & Drinks</h3>
            </a>

            <a href="{{ route('pages.first-fridays') }}" class="group p-6 bg-theme-secondary rounded-2xl border border-theme hover:border-accent-400 transition-all card-hover text-center reveal delay-400">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform">
                    <i class="fa-duotone fa-light fa-party-horn text-2xl text-white"></i>
                </div>
                <h3 class="font-semibold text-theme-primary group-hover:text-violet-600 transition-colors">First Fridays</h3>
            </a>
        </div>
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
            @php
                $featuredBusinesses = \App\Models\Business::approved()
                    ->whereNotNull('featured_image')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();

                if($featuredBusinesses->count() < 6) {
                    $additionalBusinesses = \App\Models\Business::approved()
                        ->whereNotIn('id', $featuredBusinesses->pluck('id'))
                        ->inRandomOrder()
                        ->take(6 - $featuredBusinesses->count())
                        ->get();
                    $featuredBusinesses = $featuredBusinesses->merge($additionalBusinesses);
                }
            @endphp

            @forelse($featuredBusinesses as $index => $business)
                <a href="{{ route('businesses.show', $business) }}" class="group bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index % 3 + 1) * 100 }}">
                    <div class="relative overflow-hidden">
                        @if($business->featured_image)
                            <img src="{{ Storage::url($business->featured_image) }}" alt="{{ $business->name }}" class="w-full h-56 object-cover transform group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-900 flex items-center justify-center">
                                <i class="fa-duotone fa-light fa-store text-5xl text-primary-300 dark:text-primary-600"></i>
                            </div>
                        @endif
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
            @php
                $upcomingEvents = \App\Models\Event::approved()
                    ->upcoming()
                    ->orderBy('event_date')
                    ->take(3)
                    ->get();
            @endphp

            @forelse($upcomingEvents as $index => $event)
                <a href="{{ route('events.show', $event) }}" class="group bg-theme-secondary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index + 1) * 100 }}">
                    <div class="relative">
                        @if($event->featured_image)
                            <img src="{{ Storage::url($event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-500">
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
            @php
                $latestPosts = \App\Models\BlogPost::where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            @forelse($latestPosts as $index => $post)
                <article class="group bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover reveal delay-{{ ($index + 1) * 100 }}">
                    <div class="relative overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-500">
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
                </article>
            @empty
                <div class="col-span-full text-center py-8 text-theme-secondary">
                    <i class="fa-duotone fa-light fa-newspaper text-5xl text-theme-tertiary mb-4"></i>
                    <p>No blog posts yet. Stay tuned for community updates!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Image Gallery Ribbon --}}
<section class="py-8 bg-theme-secondary overflow-hidden">
    <div class="flex gap-4 animate-scroll">
        @for($i = 0; $i < 2; $i++)
        <img src="/images/home/downtown-bellefontaine-1.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        <img src="/images/home/downtown-bellefontaine-2.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        <img src="/images/home/downtown-bellefontaine-3.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        <img src="/images/home/downtown-bellefontaine-1.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        <img src="/images/home/downtown-bellefontaine-2.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        <img src="/images/home/downtown-bellefontaine-3.jpg" alt="Downtown Bellefontaine" class="h-32 md:h-48 w-auto rounded-lg object-cover flex-shrink-0">
        @endfor
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-primary-600 via-primary-700 to-primary-800 relative overflow-hidden">
    {{-- Decorative elements --}}
    <img src="/images/home/pineapple.svg" alt="" class="absolute -left-16 top-1/2 transform -translate-y-1/2 w-48 opacity-10 float-animation" aria-hidden="true">
    <img src="/images/home/pineapple.svg" alt="" class="absolute -right-16 top-1/2 transform -translate-y-1/2 w-48 opacity-10 float-animation-delayed" aria-hidden="true">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="font-display text-3xl text-accent-300 reveal">Join Our Community</span>
        <h2 class="text-3xl md:text-4xl font-bold text-white mt-4 mb-6 reveal delay-100">Own a Business in Downtown Bellefontaine?</h2>
        <p class="text-xl text-white/90 mb-10 reveal delay-200">
            Join our growing directory and get discovered by locals and visitors alike.<br class="hidden md:inline">
            Share your events, connect with customers, and be part of our vibrant community.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center reveal delay-300">
            <a href="/register" class="inline-flex items-center justify-center px-8 py-4 bg-accent-500 hover:bg-accent-400 text-white font-bold rounded-full transition-all text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fa-duotone fa-light fa-user-plus mr-2"></i>
                Register Your Business
            </a>
            <a href="{{ route('pages.contact') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/50 hover:bg-white hover:text-primary-700 text-white font-bold rounded-full transition-all text-lg">
                <i class="fa-duotone fa-light fa-envelope mr-2"></i>
                Contact Us
            </a>
        </div>
    </div>
</section>

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
