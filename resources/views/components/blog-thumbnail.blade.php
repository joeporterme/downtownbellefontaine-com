@props([
    'post',
    'media' => 'w-full h-48 object-cover',   // classes for the img/video element
    'fallback' => 'w-full h-48',              // classes for the no-media gradient box
    'icon' => 'text-4xl',                     // size of the fallback newspaper icon
])
@php
    $img   = $post->featured_image ? \App\Support\Media::url($post->featured_image) : null;
    $video = (! $img && $post->featured_video) ? \App\Support\Media::url($post->featured_video) : null;
@endphp
@if($img)
    <img src="{{ $img }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="{{ $media }}">
@elseif($video)
    {{-- Video posts preview the clip itself: muted, looping, inline. Click through for sound/full. --}}
    <video autoplay muted loop playsinline preload="metadata"
           poster="{{ \App\Support\Media::url(\App\Models\BlogPost::DEFAULT_VIDEO_HERO) }}"
           class="{{ $media }}">
        <source src="{{ $video }}" type="video/mp4">
    </video>
    <span class="pointer-events-none absolute bottom-3 right-3 w-8 h-8 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-white">
        <i class="fa-duotone fa-light fa-play text-xs ml-0.5"></i>
    </span>
@else
    <div class="{{ $fallback }} bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
        <i class="fa-duotone fa-light fa-newspaper {{ $icon }} text-accent-300 dark:text-accent-600"></i>
    </div>
@endif
