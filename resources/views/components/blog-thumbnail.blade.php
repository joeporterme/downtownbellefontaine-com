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
    {{-- Video posts preview the clip itself: muted, looping, inline. Click through for sound/full.
         Playback is driven by the IntersectionObserver below so clips only play while on screen
         (and never for visitors who prefer reduced motion). --}}
    <video autoplay muted loop playsinline preload="metadata"
           poster="{{ \App\Support\Media::url(\App\Models\BlogPost::DEFAULT_VIDEO_HERO) }}"
           class="blog-thumb-video {{ $media }}">
        <source src="{{ $video }}" type="video/mp4">
    </video>
    <span class="pointer-events-none absolute bottom-3 right-3 w-8 h-8 rounded-full bg-black/55 backdrop-blur-sm flex items-center justify-center text-white">
        <i class="fa-duotone fa-light fa-play text-xs ml-0.5"></i>
    </span>
    @once
        @push('scripts')
        <script>
        (function(){
            if (window.__blogThumbVideoInit) return; window.__blogThumbVideoInit = true;
            function setup(){
                var vids = document.querySelectorAll('video.blog-thumb-video');
                if (!vids.length) return;
                var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduce) { vids.forEach(function(v){ v.removeAttribute('autoplay'); v.pause(); }); return; }
                if (!('IntersectionObserver' in window)) return; // native autoplay handles it
                var io = new IntersectionObserver(function(entries){
                    entries.forEach(function(e){
                        var v = e.target;
                        if (e.isIntersecting) { v.muted = true; var p = v.play(); if (p && p.catch) p.catch(function(){}); }
                        else { v.pause(); }
                    });
                }, { threshold: 0.25 });
                vids.forEach(function(v){ io.observe(v); });
            }
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup); else setup();
        })();
        </script>
        @endpush
    @endonce
@else
    <div class="{{ $fallback }} bg-gradient-to-br from-accent-100 to-accent-200 dark:from-accent-800 dark:to-accent-900 flex items-center justify-center">
        <i class="fa-duotone fa-light fa-newspaper {{ $icon }} text-accent-300 dark:text-accent-600"></i>
    </div>
@endif
