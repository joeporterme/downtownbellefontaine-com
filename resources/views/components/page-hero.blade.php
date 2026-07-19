@props([
    'eyebrow' => null,
    'title' => null,
    'subtitle' => null,
    'image' => null,
    'height' => 'py-24 sm:py-32',
])

@php
    // Prefer the CMS Page record for this route, falling back to the props the
    // blade passes (which act as the built-in defaults for each page).
    $page = $currentPage ?? null;
    $heroEyebrow = $page?->hero_eyebrow ?: $eyebrow;
    $heroTitle = $page?->hero_heading ?: ($title ?: $page?->title);
    $heroSubtitle = $page?->hero_subheading ?: $subtitle;
    $heroImage = \App\Support\Media::url($page?->hero_image ?: $image);
@endphp

<section class="relative overflow-hidden bg-primary-800 dark:bg-primary-950">
    @if($heroImage)
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ $heroImage }}" alt="{{ $heroTitle }}"
                 data-parallax data-parallax-speed="0.25"
                 class="parallax-img absolute inset-0 w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-b from-primary-900/55 via-primary-900/40 to-primary-900/80"></div>
        </div>
    @endif

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 {{ $height }} text-center">
        @if($heroEyebrow)
            <p class="text-accent-300 font-display text-3xl sm:text-4xl mb-3">{{ $heroEyebrow }}</p>
        @endif
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">{{ $heroTitle }}</h1>
        @if($heroSubtitle)
            <p class="text-primary-100 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">{{ $heroSubtitle }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
