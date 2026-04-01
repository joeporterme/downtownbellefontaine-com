@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                <i class="fa-duotone fa-light fa-right-to-bracket text-3xl text-primary-600 dark:text-primary-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary">Welcome Back</h1>
            <p class="mt-2 text-theme-tertiary">Sign in to manage your business and events.</p>
        </div>

        {{-- Status Message (e.g., after password reset) --}}
        @if (session('status'))
            <div class="mb-4 bg-success-100 dark:bg-success-900 border border-success-400 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i class="fa-duotone fa-light fa-circle-check"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-theme-primary shadow-lg border border-theme rounded-xl p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-theme-secondary mb-1.5">
                        <i class="fa-duotone fa-light fa-envelope text-primary-500 mr-1"></i>
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="jane@yourbusiness.com"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                    @error('email')
                        <p class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-theme-secondary">
                            <i class="fa-duotone fa-light fa-lock text-primary-500 mr-1"></i>
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Your password"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                    @error('password')
                        <p class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="ml-2 text-sm text-theme-tertiary">Remember me</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-right-to-bracket"></i>
                    Sign In
                </button>
            </form>
        </div>

        {{-- Footer link --}}
        <p class="text-center mt-6 text-theme-tertiary text-sm">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Create one for free</a>
        </p>
    </div>
</div>
@endsection
