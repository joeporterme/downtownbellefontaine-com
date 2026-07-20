@extends('layouts.app')

@section('title', 'Media & Press')
@section('description', "Downtown Bellefontaine in the news — read press coverage and media mentions about Ohio's most loveable downtown.")

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="In the News"
    title="Media & Press"
    subtitle="Downtown Bellefontaine has been featured across Ohio and beyond. Here's a look at our coverage."
    image="/images/home/downtown-bellefontaine-2.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Media & Press']]" />

<section class="py-16 md:py-20 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($pressItems as $year => $items)
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <h2 class="text-2xl font-bold text-theme-primary">{{ $year }}</h2>
                    <span class="flex-1 h-px bg-theme"></span>
                </div>
                <ul class="space-y-4">
                    @foreach($items as $item)
                        <li>
                            <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                               class="group flex items-start justify-between gap-4 bg-theme-secondary rounded-xl border border-theme p-5 card-hover">
                                <div>
                                    <h3 class="font-semibold text-theme-primary group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors leading-snug">{{ $item->title }}</h3>
                                    <p class="text-sm text-theme-tertiary mt-1">
                                        @if($item->source)<span class="font-medium">{{ $item->source }}</span> <span class="opacity-40">·</span> @endif{{ $item->published_date->format('F j, Y') }}
                                    </p>
                                </div>
                                <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-primary-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 mt-1 flex-shrink-0"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="text-center py-16 text-theme-secondary">
                <i class="fa-duotone fa-light fa-newspaper text-5xl text-theme-tertiary mb-4"></i>
                <p>Press coverage will appear here soon.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
