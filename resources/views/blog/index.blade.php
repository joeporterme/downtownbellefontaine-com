@extends('layouts.app')

@section('title', 'Blog')
@section('description', 'Stories, news, and updates from Downtown Bellefontaine — new businesses, events, and life in Ohio\'s most loveable downtown.')

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="The Downtown Journal"
    title="Stories & News"
    subtitle="New shops, big events, and the people behind Ohio's most loveable downtown."
    image="/images/home/downtown-bellefontaine-3.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Blog']]" />

<div class="py-16 md:py-20 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Featured post (page 1 only) --}}
        @if($showFeatured && $featured)
            <a href="{{ route('blog.show', $featured) }}" class="group grid lg:grid-cols-2 gap-8 lg:gap-12 items-center mb-16 pb-16 border-b border-theme">
                <div class="relative overflow-hidden rounded-2xl shadow-xl aspect-[16/10]">
                    @if($featured->featured_image)
                        <img src="{{ \App\Support\Media::url($featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
                            <i class="fa-duotone fa-light fa-newspaper text-5xl text-accent-300 dark:text-accent-600"></i>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-accent-500 text-white text-xs font-semibold uppercase tracking-wide">Latest</span>
                </div>
                <div>
                    <div class="flex items-center gap-3 text-sm text-theme-tertiary mb-3">
                        @if($featured->category)
                            <span class="text-accent-600 dark:text-accent-400 font-semibold">{{ $featured->category->name }}</span>
                            <span class="opacity-40">•</span>
                        @endif
                        <span>{{ $featured->published_at->format('M j, Y') }}</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-theme-primary leading-tight mb-4 group-hover:text-accent-600 transition-colors">{{ $featured->title }}</h2>
                    <p class="text-theme-secondary text-lg leading-relaxed line-clamp-3">{{ $featured->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($featured->content), 180) }}</p>
                    <div class="mt-6 flex items-center gap-4">
                        @if($featured->author)
                            <span class="text-sm text-theme-tertiary">By <span class="text-theme-secondary font-medium">{{ $featured->author->name }}</span></span>
                        @endif
                        <span class="inline-flex items-center gap-2 text-accent-600 font-semibold group-hover:gap-3 transition-all">
                            Read story <i class="fa-duotone fa-light fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
        @endif

        {{-- Post grid --}}
        @if($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="group">
                        <a href="{{ route('blog.show', $post) }}" class="block bg-theme-secondary rounded-2xl border border-theme overflow-hidden card-hover h-full flex flex-col">
                            <div class="relative overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ \App\Support\Media::url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-52 bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
                                        <i class="fa-duotone fa-light fa-newspaper text-4xl text-accent-300 dark:text-accent-600"></i>
                                    </div>
                                @endif
                                @if($post->category)
                                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-white text-xs font-medium">{{ $post->category->name }}</span>
                                @endif
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 text-xs text-theme-tertiary mb-2">
                                    <i class="fa-duotone fa-light fa-calendar"></i>
                                    {{ $post->published_at->format('M j, Y') }}
                                    @if($post->author)
                                        <span class="opacity-40">•</span>
                                        <span>{{ $post->author->name }}</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-bold text-theme-primary group-hover:text-accent-600 transition-colors mb-2 line-clamp-2">{{ $post->title }}</h3>
                                <p class="text-theme-secondary text-sm leading-relaxed line-clamp-3 flex-grow">{{ $post->seo_description ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                                <span class="mt-4 inline-flex items-center gap-1.5 text-accent-600 font-medium text-sm">Read more <i class="fa-duotone fa-light fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @elseif(!$showFeatured)
            <div class="text-center py-16">
                <i class="fa-duotone fa-light fa-newspaper text-5xl text-theme-tertiary mb-4"></i>
                <p class="text-theme-secondary">No stories yet — check back soon.</p>
            </div>
        @endif
    </div>
</div>
@endsection
