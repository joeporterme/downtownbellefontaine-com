@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 mb-4">
                <i class="fa-duotone fa-light fa-envelope-circle-check text-3xl text-primary-600 dark:text-primary-400"></i>
            </div>
            <h1 class="text-3xl font-bold text-theme-primary">Verify Your Email</h1>
            <p class="mt-2 text-theme-tertiary">
                We sent a verification link to <span class="font-medium text-theme-secondary">{{ auth()->user()?->email }}</span>.
                Click it to activate your account and access your portal.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 bg-success-100 dark:bg-success-900 border border-success-400 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <i class="fa-duotone fa-light fa-circle-check"></i>
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-theme-primary shadow-lg border border-theme rounded-xl p-8 text-center">
            <p class="text-sm text-theme-tertiary mb-5">
                Didn't get the email? Check your spam folder, or request a new link.
            </p>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-paper-plane"></i>
                    Resend Verification Email
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-theme-tertiary text-sm">
            Wrong account?
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Log out</button>
            </form>
        </p>
    </div>
</div>
@endsection
