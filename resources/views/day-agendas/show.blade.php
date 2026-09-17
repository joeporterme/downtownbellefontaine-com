@extends('layouts.app')

@section('title', $agenda->title)
@section('description', $agenda->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($agenda->content), 160))
@if($agenda->featured_image)
    @section('og_image', \App\Support\Media::url($agenda->featured_image))
@endif

@section('content')
{{-- Hero with featured image --}}
<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    @if($agenda->featured_image)
        <div class="absolute inset-0">
            <img src="{{ \App\Support\Media::url($agenda->featured_image) }}" alt="{{ $agenda->title }}"
                 class="absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-b from-primary-900/55 via-primary-900/40 to-primary-900/80"></div>
        </div>
    @endif
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-28 text-center">
        <p class="text-accent-300 font-display text-3xl sm:text-4xl mb-3">Day Agenda</p>
        <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">{{ $agenda->title }}</h1>
        @if($agenda->excerpt)
            <p class="text-primary-100 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">{{ $agenda->excerpt }}</p>
        @endif
    </div>
</section>

<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Plan a Visit', 'url' => route('pages.plan-a-visit')],
    ['label' => 'Day Agendas', 'url' => route('day-agendas.index')],
    ['label' => $agenda->title],
]" />

<article class="py-14 md:py-20 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose-downtown max-w-none text-theme-secondary leading-relaxed space-y-4">
            {!! $agenda->safe_content !!}
        </div>

        @if($agenda->pdf_url)
            <div class="mt-10">
                <a href="{{ \App\Support\Media::url($agenda->pdf_url) }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-file-pdf"></i>
                    Download the printable itinerary
                </a>
            </div>
        @endif

        <div class="mt-12 pt-8 border-t border-theme">
            <a href="{{ route('day-agendas.index') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-medium hover:underline">
                <i class="fa-duotone fa-light fa-arrow-left"></i> All day agendas
            </a>
        </div>
    </div>
</article>

@if($more->isNotEmpty())
<section class="py-16 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-8 text-center">More ways to spend a day</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($more as $m)
                <a href="{{ route('day-agendas.show', $m) }}" class="group block bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover">
                    @if($m->featured_image)
                        <img src="{{ \App\Support\Media::url($m->featured_image) }}" alt="{{ $m->title }}" class="w-full h-40 object-cover" loading="lazy">
                    @else
                        <div class="w-full h-40 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-900 flex items-center justify-center">
                            <i class="fa-duotone fa-light fa-map-location-dot text-4xl text-primary-300 dark:text-primary-600"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-semibold text-theme-primary group-hover:text-accent-600 transition-colors">{{ $m->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
