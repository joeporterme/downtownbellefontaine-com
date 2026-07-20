@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title)
@section('description', $post->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160))
@if($post->featured_image)
    @section('og_image', \App\Support\Media::url($post->featured_image))
    @section('og_type', 'article')
@endif

@push('head')
@php
    $articleLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'author' => $post->author ? ['@type' => 'Person', 'name' => $post->author->name] : null,
        'image' => $post->featured_image ? \App\Support\Media::absoluteUrl($post->featured_image) : null,
        'mainEntityOfPage' => url()->current(),
    ]);
@endphp
<script type="application/ld+json">{!! json_encode($articleLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@push('styles')
<style>
    .blog-content { color: var(--text-secondary); line-height: 1.8; font-size: 1.075rem; }
    .blog-content > * + * { margin-top: 1.25rem; }
    .blog-content h2 { color: var(--text-primary); font-size: 1.75rem; font-weight: 700; line-height: 1.25; margin-top: 2.5rem; }
    .blog-content h3 { color: var(--text-primary); font-size: 1.35rem; font-weight: 700; margin-top: 2rem; }
    .blog-content a { color: #01757f; text-decoration: underline; text-underline-offset: 2px; }
    .dark .blog-content a { color: #4db8bf; }
    .blog-content strong { color: var(--text-primary); }
    .blog-content ul, .blog-content ol { padding-left: 1.5rem; }
    .blog-content ul { list-style: disc; }
    .blog-content ol { list-style: decimal; }
    .blog-content li + li { margin-top: 0.5rem; }
    .blog-content img { border-radius: 0.75rem; margin: 1.75rem auto; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    .blog-content blockquote { border-left: 4px solid #f3773d; padding-left: 1.25rem; font-style: italic; color: var(--text-primary); }
</style>
@endpush

@section('content')
<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Blog', 'url' => route('blog.index')], ['label' => $post->title]]" />

<article class="bg-theme-primary">
    @php $minutes = max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200)); @endphp
    {{-- Header — the featured image becomes the hero background when present --}}
    <header class="relative overflow-hidden {{ $post->featured_image ? 'py-24 md:py-36' : 'py-12 md:py-16' }}">
        @if($post->featured_image)
            <div class="absolute inset-0">
                <img src="{{ \App\Support\Media::url($post->featured_image) }}" alt="{{ $post->title }}"
                     data-parallax data-parallax-speed="0.2"
                     class="parallax-img absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-primary-900/70 via-primary-900/55 to-primary-900/85"></div>
            </div>
        @endif
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            @if($post->category)
                <span class="inline-block {{ $post->featured_image ? 'text-accent-300' : 'text-accent-600 dark:text-accent-400' }} font-semibold uppercase tracking-wide text-sm mb-3">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-3xl md:text-5xl font-bold {{ $post->featured_image ? 'text-white' : 'text-theme-primary' }} leading-tight">{{ $post->title }}</h1>
            <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm {{ $post->featured_image ? 'text-primary-100' : 'text-theme-tertiary' }} mt-5">
                <span><i class="fa-duotone fa-light fa-calendar mr-1.5"></i>{{ $post->published_at->format('F j, Y') }}</span>
                @if($post->author)
                    <span class="opacity-40">•</span>
                    <span><i class="fa-duotone fa-light fa-user-pen mr-1.5"></i>{{ $post->author->name }}</span>
                @endif
                <span class="opacity-40">•</span>
                <span><i class="fa-duotone fa-light fa-clock mr-1.5"></i>{{ $minutes }} min read</span>
            </div>
        </div>
    </header>

    {{-- Body --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 blog-content">
        {!! $post->safe_content !!}
    </div>

    {{-- Tagged businesses --}}
    @if($post->businesses->isNotEmpty())
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-theme">
            <p class="font-display text-2xl sm:text-3xl text-accent-500 mb-4">Featured in this story</p>
            <div class="flex flex-wrap gap-3">
                @foreach($post->businesses as $biz)
                    <a href="{{ route('businesses.show', $biz) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-theme-secondary border border-theme text-theme-secondary hover:text-primary-500 hover:border-primary-400 transition-colors text-sm font-medium">
                        <i class="fa-duotone fa-light fa-store text-primary-500"></i>{{ $biz->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Back link --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-semibold hover:gap-3 transition-all">
            <i class="fa-duotone fa-light fa-arrow-left"></i> Back to the Blog
        </a>
    </div>
</article>

{{-- Related posts --}}
@if($related->isNotEmpty())
    <section class="py-16 bg-theme-secondary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">Keep reading</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">More Downtown Stories</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($related as $post)
                    <a href="{{ route('blog.show', $post) }}" class="group block bg-theme-primary rounded-2xl border border-theme overflow-hidden card-hover">
                        <div class="overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ \App\Support\Media::url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" class="w-full h-44 object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-44 bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
                                    <i class="fa-duotone fa-light fa-newspaper text-4xl text-accent-300 dark:text-accent-600"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-theme-tertiary mb-1">{{ $post->published_at->format('M j, Y') }}</p>
                            <h3 class="font-bold text-theme-primary group-hover:text-accent-600 transition-colors line-clamp-2">{{ $post->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
