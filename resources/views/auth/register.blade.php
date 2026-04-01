@extends('layouts.app')

@section('title', 'Create Your Business Account')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-lg">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                <i class="fa-duotone fa-light fa-store text-3xl text-primary-600 dark:text-primary-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary">Join Downtown Bellefontaine</h1>
            <p class="mt-2 text-theme-tertiary">Create your free business account to manage your listing, post events, and connect with the community.</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-theme-primary shadow-lg border border-theme rounded-xl p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-theme-secondary mb-1.5">
                        <i class="fa-duotone fa-light fa-user text-primary-500 mr-1"></i>
                        Your Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Jane Smith"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                    @error('name')
                        <p class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="password" class="block text-sm font-medium text-theme-secondary mb-1.5">
                        <i class="fa-duotone fa-light fa-lock text-primary-500 mr-1"></i>
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="At least 8 characters"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                    @error('password')
                        <p class="mt-1.5 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-theme-secondary mb-1.5">
                        <i class="fa-duotone fa-light fa-lock-keyhole text-primary-500 mr-1"></i>
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-rocket"></i>
                    Create Your Account
                </button>
            </form>
        </div>

        {{-- Footer link --}}
        <p class="text-center mt-6 text-theme-tertiary text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Sign in here</a>
        </p>

        {{-- Benefits --}}
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="flex flex-col items-center gap-2 p-4 rounded-lg bg-theme-primary border border-theme">
                <i class="fa-duotone fa-light fa-building text-2xl text-primary-500"></i>
                <span class="text-xs text-theme-tertiary">Manage Your Listing</span>
            </div>
            <div class="flex flex-col items-center gap-2 p-4 rounded-lg bg-theme-primary border border-theme">
                <i class="fa-duotone fa-light fa-calendar-plus text-2xl text-primary-500"></i>
                <span class="text-xs text-theme-tertiary">Post Events</span>
            </div>
            <div class="flex flex-col items-center gap-2 p-4 rounded-lg bg-theme-primary border border-theme">
                <i class="fa-duotone fa-light fa-chart-line text-2xl text-primary-500"></i>
                <span class="text-xs text-theme-tertiary">Grow Your Reach</span>
            </div>
        </div>
    </div>
</div>
@endsection
