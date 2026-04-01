@extends('layouts.app')

@section('title', 'Set New Password')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                <i class="fa-duotone fa-light fa-lock-keyhole text-3xl text-primary-600 dark:text-primary-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary">Set New Password</h1>
            <p class="mt-2 text-theme-tertiary">Choose a new password for your account.</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-theme-primary shadow-lg border border-theme rounded-xl p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

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
                        value="{{ old('email', $email) }}"
                        required
                        autocomplete="email"
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
                        New Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autofocus
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
                        Confirm New Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm your new password"
                        class="w-full px-4 py-2.5 rounded-lg border border-theme bg-theme-secondary text-theme-primary placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                    >
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-shield-check"></i>
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
