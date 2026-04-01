<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Business Portal') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Prevent flash of wrong theme --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="bg-theme-secondary text-theme-primary min-h-screen">
    <nav class="bg-theme-primary shadow-sm border-b border-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('business.dashboard') }}" class="text-xl font-bold text-primary-500 dark:text-primary-400">
                        {{ config('app.name') }}
                    </a>
                    <span class="text-sm text-theme-tertiary">Business Portal</span>
                    <nav class="flex items-center space-x-4">
                        <a href="{{ route('business.dashboard') }}" class="text-theme-secondary hover:text-primary-500 transition-colors text-sm font-medium">Dashboard</a>
                        <a href="{{ route('business.events.index') }}" class="text-theme-secondary hover:text-primary-500 transition-colors text-sm font-medium">Events</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <x-theme-toggle />
                    <span class="text-theme-secondary">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-theme-tertiary hover:text-theme-primary transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-success-100 dark:bg-success-900 border border-success-400 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-danger-100 dark:bg-danger-900 border border-danger-400 dark:border-danger-700 text-danger-700 dark:text-danger-300 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
