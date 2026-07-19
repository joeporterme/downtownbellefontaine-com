{{-- Sitewide photo lightbox. Triggered by any [data-lightbox] element. --}}
<div id="lightbox" class="hidden fixed inset-0 z-[200] items-center justify-center"
     role="dialog" aria-modal="true" aria-label="Photo gallery" data-lightbox-modal>

    {{-- Blurred backdrop --}}
    <div class="absolute inset-0 bg-black/70 backdrop-blur-md" data-lightbox-close></div>

    <button type="button" data-lightbox-close aria-label="Close gallery"
            class="absolute top-4 right-4 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
        <i class="fa-duotone fa-light fa-xmark text-xl"></i>
    </button>

    <button type="button" data-lightbox-prev aria-label="Previous photo"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
        <i class="fa-duotone fa-light fa-chevron-left text-xl"></i>
    </button>

    <button type="button" data-lightbox-next aria-label="Next photo"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
        <i class="fa-duotone fa-light fa-chevron-right text-xl"></i>
    </button>

    <figure class="relative z-[1] mx-4 flex flex-col items-center">
        <img src="" alt="" data-lightbox-image class="max-h-[80vh] w-auto rounded-lg shadow-2xl">
        <figcaption data-lightbox-caption class="mt-3 text-white/80 text-sm text-center max-w-xl"></figcaption>
    </figure>
</div>
