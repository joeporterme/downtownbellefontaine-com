@extends('business.layout')

@section('title', 'Edit Business')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-theme-primary mb-6">Edit Business: {{ $business->name }}</h1>

    @if($business->status === 'pending')
        <div class="mb-4 bg-warning-100 dark:bg-warning-900 border border-warning-300 dark:border-warning-700 text-warning-800 dark:text-warning-200 px-4 py-3 rounded">
            Your business is pending approval. You can still update the information.
        </div>
    @elseif($business->status === 'rejected')
        <div class="mb-4 bg-danger-100 dark:bg-danger-900 border border-danger-300 dark:border-danger-700 text-danger-800 dark:text-danger-200 px-4 py-3 rounded">
            Your business registration was rejected. Please update your information and contact support.
        </div>
    @endif

    <form method="POST" action="{{ route('business.update', $business) }}" class="bg-theme-primary shadow border border-theme rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @include('business.partials.form-fields')
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('business.dashboard') }}" class="px-4 py-2 border border-theme rounded-md text-theme-secondary hover:bg-theme-secondary transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>

    {{-- Locations Section --}}
    @if($business->locations->count() > 0)
    <div class="mt-8 bg-theme-primary shadow border border-theme rounded-lg p-6">
        <h3 class="text-lg font-medium text-theme-primary mb-4">Business Locations</h3>
        <p class="text-sm text-theme-tertiary mb-4">Contact an administrator to add or manage additional locations.</p>
        <div class="space-y-4">
            @foreach($business->locations as $location)
                <div class="border border-theme rounded-lg p-4 {{ $location->is_active ? 'bg-theme-primary' : 'bg-theme-secondary opacity-75' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            @if($location->name)
                                <h4 class="font-medium text-theme-primary">{{ $location->name }}</h4>
                            @endif
                            <p class="text-theme-secondary">{{ $location->full_address }}</p>
                            @if($location->phone)
                                <p class="text-sm text-theme-tertiary">{{ $location->phone }}</p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($location->is_primary)
                                <span class="px-2 py-1 text-xs bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded">Primary</span>
                            @endif
                            @if(!$location->is_active)
                                <span class="px-2 py-1 text-xs bg-theme-tertiary text-theme-secondary rounded">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
