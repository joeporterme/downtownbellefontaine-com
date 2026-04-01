@extends('business.layout')

@section('title', 'Dashboard')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-theme-primary">My Businesses</h1>
    <a href="{{ route('business.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors">
        + Add Business
    </a>
</div>

@if($businesses->isEmpty())
    <div class="bg-theme-primary rounded-lg shadow border border-theme p-6 text-center">
        <p class="text-theme-secondary mb-4">You haven't registered any businesses yet.</p>
        <a href="{{ route('business.create') }}" class="text-primary-600 dark:text-primary-400 hover:underline">Register your first business</a>
    </div>
@else
    <div class="bg-theme-primary shadow border border-theme overflow-hidden rounded-lg">
        <table class="min-w-full divide-y divide-theme">
            <thead class="bg-theme-secondary">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">Business</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-theme-tertiary uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-theme-primary divide-y divide-theme">
                @foreach($businesses as $business)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-theme-primary">{{ $business->name }}</div>
                        <div class="text-sm text-theme-tertiary">{{ $business->address }}, {{ $business->city }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($business->status)
                            @case('pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-warning-100 dark:bg-warning-900 text-warning-800 dark:text-warning-200">
                                    Pending Approval
                                </span>
                                @break
                            @case('approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-success-100 dark:bg-success-900 text-success-800 dark:text-success-200">
                                    Approved
                                </span>
                                @break
                            @case('rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-danger-100 dark:bg-danger-900 text-danger-800 dark:text-danger-200">
                                    Rejected
                                </span>
                                @break
                            @case('inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-theme-tertiary text-theme-secondary">
                                    Inactive
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-theme-tertiary">
                        {{ $business->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('business.edit', $business) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
