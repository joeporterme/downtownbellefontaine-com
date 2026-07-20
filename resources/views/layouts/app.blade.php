<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $settings = $siteSettings ?? null;
        $page = $currentPage ?? null;
        $siteName = $settings->site_name ?? config('app.name', 'Downtown Bellefontaine');
        $defaultDesc = $settings->default_meta_description
            ?? 'Discover Downtown Bellefontaine, Ohio — local shops, restaurants, events, and things to do in the heart of Logan County.';
        $metaTitle = $page?->seo_title ?: ($page?->title ?: trim($__env->yieldContent('title', 'Downtown Bellefontaine, Ohio')));
        $metaDescription = $page?->seo_description ?: trim($__env->yieldContent('description', $defaultDesc));
        // Priority: the page's own image (business/post/event featured, or a CMS
        // page's og/hero) → the site-wide default share image.
        $ogImageRaw = trim((string) $__env->yieldContent('og_image'))
            ?: ($page?->og_image ?: ($page?->hero_image ?: ($settings->default_og_image ?: '/images/og-default.jpg')));
        $ogImage = \App\Support\Media::absoluteUrl($ogImageRaw);
        $ogType = trim($__env->yieldContent('og_type', 'website')) ?: 'website';
        $socialLinks = array_values(array_filter([
            $settings->facebook_url ?? null,
            $settings->instagram_url ?? null,
            $settings->x_url ?? null,
            $settings->tiktok_url ?? null,
            $settings->youtube_url ?? null,
        ]));
    @endphp

    {{-- Primary meta --}}
    <title>{{ $metaTitle }} - {{ $siteName }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#01757f">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Organization + site search structured data --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => $siteName,
                'url' => url('/'),
                'logo' => asset('images/logo.svg'),
                'sameAs' => $socialLinks,
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => $siteName,
                'publisher' => ['@id' => url('/') . '#organization'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/businesses') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Analytics (from Site Settings) --}}
    @if(!empty($settings?->google_analytics_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $settings->google_analytics_id }}');
        </script>
    @endif

    {{-- Prevent flash of wrong theme (light / dark / system, default system) --}}
    <script>
        (function() {
            const pref = localStorage.getItem('theme') || 'system';
            const dark = pref === 'dark' || (pref === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>

    @stack('head')
    @stack('styles')
</head>
<body class="bg-theme-secondary text-theme-primary min-h-screen flex flex-col">
    {{-- Skip to content (keyboard / screen-reader users) --}}
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-[100] focus:top-3 focus:left-3 focus:px-4 focus:py-2 focus:rounded-lg focus:bg-primary-600 focus:text-white focus:shadow-lg">
        Skip to main content
    </a>

    {{-- Header --}}
    <header class="bg-theme-primary shadow-sm border-b border-theme sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Downtown Bellefontaine" class="h-12 w-auto block dark:hidden">
                    <img src="{{ asset('images/logo-white.svg') }}" alt="Downtown Bellefontaine" class="h-12 w-auto hidden dark:block">
                </a>

                {{-- Navigation --}}
                <nav class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('pages.places-to-shop') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-bag-shopping text-primary-500"></i>
                        <span>Shop</span>
                    </a>
                    <a href="{{ route('pages.food-drinks') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-utensils text-primary-500"></i>
                        <span>Eat</span>
                    </a>
                    <a href="{{ route('pages.stay') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-bed-front text-primary-500"></i>
                        <span>Stay</span>
                    </a>
                    <a href="{{ route('pages.things-to-do') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-masks-theater text-primary-500"></i>
                        <span>Play</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-calendar-star text-primary-500"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('blog.index') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]">
                        <i class="fa-duotone fa-light fa-newspaper text-primary-500"></i>
                        <span>Blog</span>
                    </a>
                    {{-- Plan a Visit dropdown (itineraries + secondary pages) --}}
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-[15px]" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-duotone fa-light fa-compass text-primary-500"></i>
                            <span>Plan a Visit</span>
                            <i class="fa-duotone fa-light fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div class="absolute right-0 top-full pt-3 w-64 hidden group-hover:block group-focus-within:block z-50">
                            <div class="bg-theme-primary border border-theme rounded-xl shadow-lg overflow-hidden py-2">
                                <a href="{{ route('pages.plan-a-visit') }}" class="flex items-start gap-3 px-4 py-2.5 text-sm text-theme-secondary hover:bg-theme-tertiary hover:text-primary-500 transition-colors">
                                    <i class="fa-duotone fa-light fa-compass w-4 mt-0.5 text-primary-500"></i>
                                    <span><span class="font-medium block">Plan a Visit</span><span class="text-xs text-theme-tertiary">Itineraries &amp; day agendas</span></span>
                                </a>
                                <a href="{{ route('pages.historic-walking-tour') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-theme-secondary hover:bg-theme-tertiary hover:text-primary-500 transition-colors">
                                    <i class="fa-duotone fa-light fa-person-walking w-4 text-primary-500"></i>
                                    <span>Historic Walking Tour</span>
                                </a>
                                <a href="{{ route('pages.first-fridays') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-theme-secondary hover:bg-theme-tertiary hover:text-primary-500 transition-colors">
                                    <i class="fa-duotone fa-light fa-calendar-star w-4 text-primary-500"></i>
                                    <span>First Fridays</span>
                                </a>
                                <a href="{{ route('pages.dora') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-theme-secondary hover:bg-theme-tertiary hover:text-primary-500 transition-colors">
                                    <i class="fa-duotone fa-light fa-martini-glass-citrus w-4 text-primary-500"></i>
                                    <span>DORA District</span>
                                </a>
                                <a href="{{ route('pages.meeting-spaces') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-theme-secondary hover:bg-theme-tertiary hover:text-primary-500 transition-colors">
                                    <i class="fa-duotone fa-light fa-handshake w-4 text-primary-500"></i>
                                    <span>Meeting Spaces</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('pages.map') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-accent-500 hover:bg-accent-600 text-white transition-colors font-semibold text-[15px]">
                        <i class="fa-duotone fa-light fa-map-location-dot"></i>
                        <span>Map</span>
                    </a>
                </nav>

                {{-- Right side --}}
                <div class="flex items-center space-x-4">
                    <x-theme-toggle />

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="hidden sm:flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 transition-colors text-sm">
                                <i class="fa-duotone fa-light fa-gauge-high"></i>
                                <span>Admin</span>
                            </a>
                        @elseif(auth()->user()->isBusinessOwner())
                            <a href="{{ route('business.dashboard') }}" class="hidden sm:flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 transition-colors text-sm">
                                <i class="fa-duotone fa-light fa-store"></i>
                                <span>My Business</span>
                            </a>
                        @endif
                    @endauth
                    {{-- "List Your Business" now lives only in the footer. --}}

                    {{-- Mobile menu button --}}
                    <button type="button" class="lg:hidden p-2 rounded-lg hover:bg-theme-tertiary" data-mobile-menu-toggle aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
                        <i class="fa-duotone fa-light fa-bars text-xl text-theme-secondary" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div class="hidden lg:hidden" id="mobile-menu" data-mobile-menu>
            <div class="px-4 pt-2 pb-4 space-y-1 border-t border-theme">
                <a href="{{ url('/') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-house w-5 text-center text-primary-500"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('pages.places-to-shop') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-bag-shopping w-5 text-center text-primary-500"></i>
                    <span>Shop</span>
                </a>
                <a href="{{ route('pages.food-drinks') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-utensils w-5 text-center text-primary-500"></i>
                    <span>Eat</span>
                </a>
                <a href="{{ route('pages.stay') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-bed-front w-5 text-center text-primary-500"></i>
                    <span>Stay</span>
                </a>
                <a href="{{ route('pages.things-to-do') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-masks-theater w-5 text-center text-primary-500"></i>
                    <span>Play</span>
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-calendar-star w-5 text-center text-primary-500"></i>
                    <span>Events</span>
                </a>
                <a href="{{ route('blog.index') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-newspaper w-5 text-center text-primary-500"></i>
                    <span>Blog</span>
                </a>
                <a href="{{ route('pages.plan-a-visit') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-compass w-5 text-center text-primary-500"></i>
                    <span>Plan a Visit</span>
                </a>
                <div class="pl-8 space-y-1">
                    <a href="{{ route('pages.historic-walking-tour') }}" class="flex items-center gap-3 py-2 text-sm text-theme-tertiary hover:text-primary-500">
                        <i class="fa-duotone fa-light fa-person-walking w-4 text-center text-primary-400"></i>
                        <span>Historic Walking Tour</span>
                    </a>
                    <a href="{{ route('pages.first-fridays') }}" class="flex items-center gap-3 py-2 text-sm text-theme-tertiary hover:text-primary-500">
                        <i class="fa-duotone fa-light fa-calendar-star w-4 text-center text-primary-400"></i>
                        <span>First Fridays</span>
                    </a>
                    <a href="{{ route('pages.dora') }}" class="flex items-center gap-3 py-2 text-sm text-theme-tertiary hover:text-primary-500">
                        <i class="fa-duotone fa-light fa-martini-glass-citrus w-4 text-center text-primary-400"></i>
                        <span>DORA District</span>
                    </a>
                    <a href="{{ route('pages.meeting-spaces') }}" class="flex items-center gap-3 py-2 text-sm text-theme-tertiary hover:text-primary-500">
                        <i class="fa-duotone fa-light fa-handshake w-4 text-center text-primary-400"></i>
                        <span>Meeting Spaces</span>
                    </a>
                </div>
                <a href="{{ route('pages.map') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-map-location-dot w-5 text-center text-primary-500"></i>
                    <span>Map</span>
                </a>
                <div class="border-t border-theme pt-2 mt-2">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                                <i class="fa-duotone fa-light fa-gauge-high w-5 text-center text-primary-500"></i>
                                <span>Admin Dashboard</span>
                            </a>
                        @elseif(auth()->user()->isBusinessOwner())
                            <a href="{{ route('business.dashboard') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                                <i class="fa-duotone fa-light fa-store w-5 text-center text-primary-500"></i>
                                <span>My Business</span>
                            </a>
                        @endif
                    @endauth
                    {{-- "List Your Business" now lives only in the footer. --}}
                </div>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-success-100 dark:bg-success-900 border border-success-400 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded flex items-center gap-3">
                <i class="fa-duotone fa-light fa-circle-check text-xl"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-danger-100 dark:bg-danger-900 border border-danger-400 dark:border-danger-700 text-danger-700 dark:text-danger-300 px-4 py-3 rounded flex items-center gap-3">
                <i class="fa-duotone fa-light fa-circle-exclamation text-xl"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main id="main" tabindex="-1" class="flex-grow">
        @yield('content')
    </main>

    {{-- Sitewide photo ribbon (curated gallery) --}}
    <x-photo-ribbon />

    {{-- Footer --}}
    <footer class="bg-primary-800 dark:bg-primary-950 text-white mt-auto">
        {{-- Top band: full-width newsletter signup --}}
        <div class="border-b border-primary-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-2 gap-6 md:gap-12 items-center">
                <div>
                    <h3 class="font-display text-2xl sm:text-3xl text-accent-300 mb-1">Stay in the loop</h3>
                    <p class="text-primary-200 text-sm">Downtown events, new openings, and happenings — straight to your inbox.</p>
                </div>
                {{-- TODO: wire action to the newsletter platform (Mailchimp / Constant Contact) once confirmed --}}
                <form action="#" method="post" class="flex flex-col sm:flex-row gap-3 w-full">
                    @csrf
                    <input type="email" name="email" required placeholder="you@email.com" aria-label="Email address" class="flex-grow px-4 py-3 rounded-lg text-primary-900 bg-white/95 border border-primary-600 focus:outline-none focus:ring-2 focus:ring-accent-400">
                    <button type="submit" class="px-6 py-3 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-lg transition-colors whitespace-nowrap">
                        <i class="fa-duotone fa-light fa-paper-plane mr-1.5"></i>Subscribe
                    </button>
                </form>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- About --}}
                <div class="col-span-1 md:col-span-2">
                    <a href="{{ url('/') }}" class="inline-block mb-4">
                        <img src="{{ asset('images/logo-white.svg') }}" alt="Downtown Bellefontaine" class="h-12 w-auto">
                    </a>
                    <p class="text-primary-200 mb-6">
                        Discover the heart of Logan County. Downtown Bellefontaine offers unique shopping,
                        dining, and entertainment experiences in a historic small-town setting.
                    </p>
                    {{-- Social Links (managed in Admin → Site Settings) --}}
                    <div class="flex items-center gap-4">
                        @php $s = $siteSettings ?? null; @endphp
                        @if($s?->facebook_url)
                            <a href="{{ $s->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <i class="fa-brands fa-facebook-f text-lg"></i>
                            </a>
                        @endif
                        @if($s?->instagram_url)
                            <a href="{{ $s->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <i class="fa-brands fa-instagram text-lg"></i>
                            </a>
                        @endif
                        @if($s?->x_url)
                            <a href="{{ $s->x_url }}" target="_blank" rel="noopener" aria-label="X (Twitter)" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <i class="fa-brands fa-x-twitter text-lg"></i>
                            </a>
                        @endif
                        @if($s?->tiktok_url)
                            <a href="{{ $s->tiktok_url }}" target="_blank" rel="noopener" aria-label="TikTok" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <i class="fa-brands fa-tiktok text-lg"></i>
                            </a>
                        @endif
                        @if($s?->youtube_url)
                            <a href="{{ $s->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <i class="fa-brands fa-youtube text-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Explore --}}
                <div>
                    <h4 class="font-semibold mb-4 flex items-center gap-2">
                        <i class="fa-duotone fa-light fa-compass text-accent-400"></i>
                        Explore
                    </h4>
                    <ul class="space-y-2 text-primary-200">
                        <li>
                            <a href="{{ route('pages.places-to-shop') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-bag-shopping w-4 text-primary-400"></i>
                                Shop
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.food-drinks') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-utensils w-4 text-primary-400"></i>
                                Eat
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.stay') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-bed-front w-4 text-primary-400"></i>
                                Stay
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.things-to-do') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-masks-theater w-4 text-primary-400"></i>
                                Play
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('events.index') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-calendar-star w-4 text-primary-400"></i>
                                Events
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('blog.index') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-newspaper w-4 text-primary-400"></i>
                                Blog
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.plan-a-visit') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-compass w-4 text-primary-400"></i>
                                Plan a Visit
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.map') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-map-location-dot w-4 text-primary-400"></i>
                                Interactive Map
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Information --}}
                <div>
                    <h4 class="font-semibold mb-4 flex items-center gap-2">
                        <i class="fa-duotone fa-light fa-circle-info text-accent-400"></i>
                        Information
                    </h4>
                    <ul class="space-y-2 text-primary-200">
                        <li>
                            <a href="{{ route('pages.media') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-newspaper w-4 text-primary-400"></i>
                                Media & Press
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.contact') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-envelope w-4 text-primary-400"></i>
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.privacy-policy') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-shield-check w-4 text-primary-400"></i>
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-store w-4 text-primary-400"></i>
                                List Your Business
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-right-to-bracket w-4 text-primary-400"></i>
                                Business Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-primary-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-primary-300 text-sm">
                <p>&copy; {{ date('Y') }} Downtown Bellefontaine. All rights reserved.</p>
                <p class="flex items-center gap-2">
                    <i class="fa-duotone fa-light fa-heart text-accent-400"></i>
                    Made with love in Bellefontaine, Ohio
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    {{-- Mobile menu toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('[data-mobile-menu-toggle]');
            const menu = document.querySelector('[data-mobile-menu]');
            if (toggleBtn && menu) {
                toggleBtn.addEventListener('click', function() {
                    const isOpen = menu.classList.toggle('hidden') === false;
                    toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    toggleBtn.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
                });
            }
        });
    </script>

    {{-- Sitewide photo lightbox --}}
    <x-lightbox />
</body>
</html>
