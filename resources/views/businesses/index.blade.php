@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - Business Directory' : 'Business Directory')
@section('description', 'Explore local businesses in Downtown Bellefontaine, Ohio - shops, restaurants, services, and more.')

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-theme-primary">
                    @if(isset($category))
                        {{ $category->name }}
                    @else
                        Business Directory
                    @endif
                </h1>
                <p class="text-theme-secondary mt-1">Discover local businesses in Downtown Bellefontaine</p>
            </div>
        </div>

        {{-- Category Filter --}}
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('businesses.index') }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !isset($category) ? 'bg-primary-600 text-white' : 'bg-theme-secondary text-theme-secondary hover:bg-primary-100 dark:hover:bg-primary-900' }}">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('businesses.category', $cat) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ isset($category) && $category->id === $cat->id ? 'bg-primary-600 text-white' : 'bg-theme-secondary text-theme-secondary hover:bg-primary-100 dark:hover:bg-primary-900' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        @if($businesses->isEmpty())
            <div class="bg-theme-secondary rounded-lg p-8 text-center">
                <p class="text-theme-secondary">No businesses found. Check back soon!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($businesses as $business)
                    <a href="{{ route('businesses.show', $business) }}" class="bg-theme-secondary rounded-lg shadow border border-theme overflow-hidden hover:shadow-lg transition-shadow group">
                        <x-business-card-image :business="$business" icon="fa-store" height="h-40" />

                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-theme-primary mb-1 group-hover:text-primary-600 transition-colors">{{ $business->name }}</h2>

                            @if($business->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($business->categories->take(2) as $cat)
                                        <span class="text-xs px-2 py-0.5 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded">
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($business->locations->first())
                                <p class="text-theme-tertiary text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $business->locations->first()->address }}</span>
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
