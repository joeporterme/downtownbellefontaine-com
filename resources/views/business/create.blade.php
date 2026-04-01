@extends('business.layout')

@section('title', 'Register Business')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-theme-primary mb-6">Register Your Business</h1>

    <form method="POST" action="{{ route('business.store') }}" class="bg-theme-primary shadow border border-theme rounded-lg p-6">
        @csrf

        <div class="space-y-6">
            @include('business.partials.form-fields', ['business' => null])
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('business.dashboard') }}" class="px-4 py-2 border border-theme rounded-md text-theme-secondary hover:bg-theme-secondary transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                Submit for Approval
            </button>
        </div>
    </form>
</div>
@endsection
