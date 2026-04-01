<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Downtown Bellefontaine') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Discover Downtown Bellefontaine, Ohio - your destination for local businesses, events, and community.')">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Prevent flash of wrong theme --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    @stack('head')
    @stack('styles')
</head>
<body class="bg-theme-secondary text-theme-primary min-h-screen flex flex-col">
    {{-- Header --}}
    <header class="bg-theme-primary shadow-sm border-b border-theme sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Downtown Bellefontaine" class="h-10 w-auto block dark:hidden">
                    <img src="{{ asset('images/logo-white.svg') }}" alt="Downtown Bellefontaine" class="h-10 w-auto hidden dark:block">
                </a>

                {{-- Navigation --}}
                <nav class="hidden lg:flex items-center space-x-5">
                    <a href="{{ route('events.index') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm">
                        <i class="fa-duotone fa-light fa-calendar-star text-primary-500"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('pages.things-to-do') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm">
                        <i class="fa-duotone fa-light fa-masks-theater text-primary-500"></i>
                        <span>Things to Do</span>
                    </a>
                    <a href="{{ route('pages.places-to-shop') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm">
                        <i class="fa-duotone fa-light fa-bag-shopping text-primary-500"></i>
                        <span>Shop</span>
                    </a>
                    <a href="{{ route('pages.food-drinks') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm">
                        <i class="fa-duotone fa-light fa-utensils text-primary-500"></i>
                        <span>Food & Drinks</span>
                    </a>
                    {{-- More Dropdown --}}
                    <div class="relative" data-dropdown>
                        <button data-dropdown-toggle class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm">
                            <i class="fa-duotone fa-light fa-bars text-primary-500"></i>
                            <span>More</span>
                            <i class="fa-duotone fa-light fa-chevron-down text-[10px] transition-transform" data-dropdown-chevron></i>
                        </button>
                        <div data-dropdown-menu class="absolute top-full right-0 pt-2 w-52 z-50 hidden opacity-0 transition-all duration-150">
                        <div class="bg-theme-primary border border-theme rounded-lg shadow-lg py-1">
                            <a href="{{ route('pages.first-fridays') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-theme-secondary hover:text-primary-500 hover:bg-theme-secondary transition-colors text-sm">
                                <i class="fa-duotone fa-light fa-party-horn text-primary-500 w-4 text-center"></i>
                                <span>First Fridays</span>
                            </a>
                            <a href="{{ route('pages.meeting-spaces') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-theme-secondary hover:text-primary-500 hover:bg-theme-secondary transition-colors text-sm">
                                <i class="fa-duotone fa-light fa-users text-primary-500 w-4 text-center"></i>
                                <span>Meeting Spaces</span>
                            </a>
                            <a href="{{ route('pages.dora') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-theme-secondary hover:text-primary-500 hover:bg-theme-secondary transition-colors text-sm">
                                <i class="fa-duotone fa-light fa-wine-glass text-primary-500 w-4 text-center"></i>
                                <span>DORA</span>
                            </a>
                        </div>
                        </div>
                    </div>
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
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 transition-colors text-sm">
                            <i class="fa-duotone fa-light fa-right-to-bracket"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <i class="fa-duotone fa-light fa-store"></i>
                            <span>List Your Business</span>
                        </a>
                    @endauth

                    {{-- Mobile menu button --}}
                    <button type="button" class="lg:hidden p-2 rounded-lg hover:bg-theme-tertiary" data-mobile-menu-toggle>
                        <i class="fa-duotone fa-light fa-bars text-xl text-theme-secondary"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div class="hidden lg:hidden" data-mobile-menu>
            <div class="px-4 pt-2 pb-4 space-y-1 border-t border-theme">
                <a href="{{ url('/') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-house w-5 text-center text-primary-500"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-calendar-star w-5 text-center text-primary-500"></i>
                    <span>Events</span>
                </a>
                <a href="{{ route('pages.things-to-do') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-masks-theater w-5 text-center text-primary-500"></i>
                    <span>Things to Do</span>
                </a>
                <a href="{{ route('pages.places-to-shop') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-bag-shopping w-5 text-center text-primary-500"></i>
                    <span>Shop</span>
                </a>
                <a href="{{ route('pages.food-drinks') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-utensils w-5 text-center text-primary-500"></i>
                    <span>Food & Drinks</span>
                </a>
                <a href="{{ route('pages.first-fridays') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-party-horn w-5 text-center text-primary-500"></i>
                    <span>First Fridays</span>
                </a>
                <a href="{{ route('pages.meeting-spaces') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-users w-5 text-center text-primary-500"></i>
                    <span>Meeting Spaces</span>
                </a>
                <a href="{{ route('pages.dora') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-wine-glass w-5 text-center text-primary-500"></i>
                    <span>DORA</span>
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
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                            <i class="fa-duotone fa-light fa-right-to-bracket w-5 text-center text-primary-500"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                            <i class="fa-duotone fa-light fa-store w-5 text-center text-primary-500"></i>
                            <span>List Your Business</span>
                        </a>
                    @endauth
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
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-primary-800 dark:bg-primary-950 text-white mt-auto">
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
                    {{-- Social Links --}}
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/DowntownBellefontaine" target="_blank" rel="noopener" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fa-brands fa-facebook-f text-lg"></i>
                        </a>
                        <a href="https://www.instagram.com/downtownbellefontaine" target="_blank" rel="noopener" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fa-brands fa-instagram text-lg"></i>
                        </a>
                        <a href="https://twitter.com/dtbellefontaine" target="_blank" rel="noopener" class="w-10 h-10 bg-primary-700 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fa-brands fa-x-twitter text-lg"></i>
                        </a>
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
                            <a href="{{ route('events.index') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-calendar-star w-4 text-primary-400"></i>
                                Events
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.things-to-do') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-masks-theater w-4 text-primary-400"></i>
                                Things to Do
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.places-to-shop') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-bag-shopping w-4 text-primary-400"></i>
                                Places to Shop
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.food-drinks') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-utensils w-4 text-primary-400"></i>
                                Food & Drinks
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pages.historic-walking-tour') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-person-walking w-4 text-primary-400"></i>
                                Historic Walking Tour
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
                            <a href="{{ route('login') }}" class="hover:text-white transition-colors flex items-center gap-2">
                                <i class="fa-duotone fa-light fa-store w-4 text-primary-400"></i>
                                Business Portal
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

    {{-- Mobile menu toggle + dropdown script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu
            const toggleBtn = document.querySelector('[data-mobile-menu-toggle]');
            const menu = document.querySelector('[data-mobile-menu]');
            if (toggleBtn && menu) {
                toggleBtn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
            }

            // More dropdown
            document.querySelectorAll('[data-dropdown]').forEach(function(dropdown) {
                const toggle = dropdown.querySelector('[data-dropdown-toggle]');
                const menu = dropdown.querySelector('[data-dropdown-menu]');
                const chevron = dropdown.querySelector('[data-dropdown-chevron]');
                if (!toggle || !menu) return;

                function open() {
                    menu.classList.remove('hidden');
                    requestAnimationFrame(function() { menu.classList.remove('opacity-0'); });
                    if (chevron) chevron.classList.add('rotate-180');
                }

                function close() {
                    menu.classList.add('opacity-0');
                    if (chevron) chevron.classList.remove('rotate-180');
                    setTimeout(function() { menu.classList.add('hidden'); }, 150);
                }

                function isOpen() { return !menu.classList.contains('hidden'); }

                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isOpen() ? close() : open();
                });

                dropdown.addEventListener('mouseenter', open);
                dropdown.addEventListener('mouseleave', close);

                document.addEventListener('click', function(e) {
                    if (!dropdown.contains(e.target)) close();
                });
            });
        });
    </script>
</body>
</html>
