@props([
    'group' => 'page',
    'limit' => 8,
    'title' => 'See Downtown',
    'eyebrow' => 'Gallery',
])

@php
    $images = \App\Models\GalleryImage::active()->ordered()->limit($limit)->get();
@endphp

@if($images->isNotEmpty())
    <section class="py-16 bg-theme-secondary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="font-display text-2xl sm:text-3xl text-accent-500">{{ $eyebrow }}</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-1">{{ $title }}</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                @foreach($images as $img)
                    @php $src = \App\Support\Media::url($img->image); @endphp
                    <a href="{{ $src }}" data-lightbox data-lightbox-group="{{ $group }}"
                       data-lightbox-caption="{{ $img->caption }}"
                       class="group relative block overflow-hidden rounded-xl aspect-square shadow-sm">
                        <img src="{{ $src }}" alt="{{ $img->caption }}" loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute bottom-2 left-3 right-3 text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $img->caption }}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
