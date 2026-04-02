<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Business Portal') - {{ config('app.name') }}</title>

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
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.svg') }}" alt="Downtown Bellefontaine" class="h-10 w-auto block dark:hidden">
                        <img src="{{ asset('images/logo-white.svg') }}" alt="Downtown Bellefontaine" class="h-10 w-auto hidden dark:block">
                    </a>
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300">
                        Business Portal
                    </span>
                </div>

                {{-- Portal Navigation --}}
                <nav class="hidden md:flex items-center space-x-5">
                    <a href="{{ route('business.dashboard') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm {{ request()->routeIs('business.dashboard') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fa-duotone fa-light fa-gauge-high text-primary-500"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('business.events.index') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm {{ request()->routeIs('business.events.*', 'events.create', 'events.edit') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fa-duotone fa-light fa-calendar-star text-primary-500"></i>
                        <span>Events</span>
                    </a>
                    <a href="{{ route('business.create') }}" class="flex items-center gap-1.5 text-theme-secondary hover:text-primary-500 dark:hover:text-primary-400 transition-colors font-medium text-sm {{ request()->routeIs('business.create') ? 'text-primary-600 dark:text-primary-400' : '' }}">
                        <i class="fa-duotone fa-light fa-store text-primary-500"></i>
                        <span>Add Business</span>
                    </a>
                </nav>

                {{-- Right side --}}
                <div class="flex items-center space-x-4">
                    <x-theme-toggle />

                    <div class="hidden sm:flex items-center gap-3">
                        <span class="text-sm text-theme-secondary">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-theme-tertiary hover:text-primary-500 transition-colors">
                                <i class="fa-duotone fa-light fa-right-from-bracket"></i>
                                Logout
                            </button>
                        </form>
                    </div>

                    {{-- Mobile menu button --}}
                    <button type="button" class="md:hidden p-2 rounded-lg hover:bg-theme-tertiary" data-portal-menu-toggle>
                        <i class="fa-duotone fa-light fa-bars text-xl text-theme-secondary"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div class="hidden md:hidden" data-portal-menu>
            <div class="px-4 pt-2 pb-4 space-y-1 border-t border-theme">
                <a href="{{ route('business.dashboard') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-gauge-high w-5 text-center text-primary-500"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('business.events.index') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-calendar-star w-5 text-center text-primary-500"></i>
                    <span>Events</span>
                </a>
                <a href="{{ route('business.create') }}" class="flex items-center gap-3 py-2.5 text-theme-secondary hover:text-primary-500">
                    <i class="fa-duotone fa-light fa-store w-5 text-center text-primary-500"></i>
                    <span>Add Business</span>
                </a>
                <div class="border-t border-theme pt-2 mt-2">
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-sm text-theme-secondary">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-theme-tertiary hover:text-primary-500">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-success-100 dark:bg-success-900 border border-success-400 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fa-duotone fa-light fa-circle-check text-xl"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-danger-100 dark:bg-danger-900 border border-danger-400 dark:border-danger-700 text-danger-700 dark:text-danger-300 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="fa-duotone fa-light fa-circle-exclamation text-xl"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow max-w-7xl w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-primary-800 dark:bg-primary-950 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-primary-300 text-sm">
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">
                        <img src="{{ asset('images/logo-white.svg') }}" alt="Downtown Bellefontaine" class="h-8 w-auto">
                    </a>
                    <span>&copy; {{ date('Y') }} Downtown Bellefontaine</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Visit Site</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-white transition-colors">Contact</a>
                    <a href="{{ route('pages.privacy-policy') }}" class="hover:text-white transition-colors">Privacy</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    {{-- Mobile menu toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('[data-portal-menu-toggle]');
            const menu = document.querySelector('[data-portal-menu]');
            if (toggleBtn && menu) {
                toggleBtn.addEventListener('click', function() {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
