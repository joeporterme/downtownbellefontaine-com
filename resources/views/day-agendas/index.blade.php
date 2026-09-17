@extends('layouts.app')

@section('title', 'Day Agendas & Itineraries')
@section('description', 'Ready-made day trips and itineraries for Downtown Bellefontaine, Ohio — kids days, girls\' weekends, antiquing, foodie tours, Ohio Caverns, Indian Lake, and more.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Plan a Visit"
    title="Day Agendas"
    subtitle="Ready-made itineraries for a perfect day in and around Downtown Bellefontaine — just pick a vibe and go."
    image="/images/pages/mckinley-street.jpg" />

<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Plan a Visit', 'url' => route('pages.plan-a-visit')],
    ['label' => 'Day Agendas'],
]" />

<div class="py-16 md:py-20 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($agendas->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($agendas as $agenda)
                    <article class="group">
                        <a href="{{ route('day-agendas.show', $agenda) }}" class="block bg-theme-secondary rounded-2xl border border-theme overflow-hidden card-hover h-full flex flex-col">
                            <div class="relative overflow-hidden">
                                @if($agenda->featured_image)
                                    <img src="{{ \App\Support\Media::url($agenda->featured_image) }}" alt="{{ $agenda->title }}"
                                         class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                @else
                                    <div class="w-full h-52 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-900 flex items-center justify-center">
                                        <i class="fa-duotone fa-light fa-map-location-dot text-5xl text-primary-300 dark:text-primary-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h2 class="text-xl font-bold text-theme-primary leading-snug mb-2 group-hover:text-accent-600 transition-colors">{{ $agenda->title }}</h2>
                                <p class="text-theme-secondary text-sm leading-relaxed line-clamp-3 flex-grow">{{ $agenda->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($agenda->content), 150) }}</p>
                                <span class="mt-4 inline-flex items-center gap-2 text-accent-600 font-semibold text-sm group-hover:gap-3 transition-all">
                                    View the itinerary <i class="fa-duotone fa-light fa-arrow-right"></i>
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-theme-secondary rounded-2xl border border-theme p-10 text-center">
                <i class="fa-duotone fa-light fa-map-location-dot text-5xl text-primary-400 mb-4"></i>
                <p class="text-theme-secondary">Itineraries are on the way — check back soon.</p>
            </div>
        @endif
    </div>
</div>
@endsection
