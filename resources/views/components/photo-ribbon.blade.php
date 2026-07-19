@php
    $ribbon = \App\Models\GalleryImage::active()->ordered()->get();
@endphp

@if($ribbon->isNotEmpty())
    <section class="overflow-hidden" aria-label="Downtown photo gallery">
        <div class="flex animate-scroll">
            {{-- Real, interactive set --}}
            @foreach($ribbon as $img)
                @php $src = \App\Support\Media::url($img->image); @endphp
                <a href="{{ $src }}" data-lightbox data-lightbox-group="ribbon"
                   data-lightbox-caption="{{ $img->caption }}"
                   class="flex-shrink-0 block">
                    <img src="{{ $src }}" alt="{{ $img->caption }}" loading="lazy"
                         class="h-32 md:h-48 w-auto object-cover hover:opacity-90 transition-opacity">
                </a>
            @endforeach
            {{-- Duplicate set (decorative) for a seamless loop --}}
            @foreach($ribbon as $img)
                <span class="flex-shrink-0 block" aria-hidden="true">
                    <img src="{{ \App\Support\Media::url($img->image) }}" alt="" loading="lazy"
                         class="h-32 md:h-48 w-auto object-cover">
                </span>
            @endforeach
        </div>
    </section>
@endif
