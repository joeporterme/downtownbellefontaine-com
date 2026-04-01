@extends('business.layout')

@section('title', 'My Events')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-theme-primary">My Events</h1>
        <a href="{{ route('events.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors">
            Submit New Event
        </a>
    </div>

    @if(session('success'))
        <div class="bg-success-100 dark:bg-success-900/50 border border-success-300 dark:border-success-700 text-success-700 dark:text-success-300 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if($events->isEmpty())
        <div class="bg-theme-secondary rounded-lg shadow border border-theme p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-theme-tertiary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-theme-primary">No events yet</h3>
            <p class="mt-1 text-theme-secondary">Get started by submitting your first event.</p>
            <div class="mt-6">
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Submit Event
                </a>
            </div>
        </div>
    @else
        <div class="bg-theme-primary shadow border border-theme rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-theme">
                <thead class="bg-theme-secondary">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">
                            Event
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-theme-tertiary uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-theme-tertiary uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-theme">
                    @foreach($events as $event)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($event->featured_image)
                                        <img src="{{ Storage::url($event->featured_image) }}" alt="" class="h-10 w-16 object-cover rounded mr-3">
                                    @else
                                        <div class="h-10 w-16 bg-primary-100 dark:bg-primary-900 rounded mr-3 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-theme-primary">{{ $event->title }}</div>
                                        @if($event->location_name)
                                            <div class="text-sm text-theme-tertiary">{{ $event->location_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-theme-primary">{{ $event->event_date->format('M j, Y') }}</div>
                                @if($event->start_time)
                                    <div class="text-sm text-theme-tertiary">{{ $event->formatted_time }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($event->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 dark:bg-success-900 text-success-800 dark:text-success-200">
                                        Approved
                                    </span>
                                @elseif($event->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 dark:bg-warning-900 text-warning-800 dark:text-warning-200">
                                        Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-100 dark:bg-danger-900 text-danger-800 dark:text-danger-200">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($event->status === 'approved')
                                    <a href="{{ route('events.show', $event) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 mr-3">
                                        View
                                    </a>
                                @endif
                                <a href="{{ route('events.edit', $event) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
