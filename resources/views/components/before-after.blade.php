@props([
    'before',
    'after',
    'title',
    'note' => null,
    'url' => null,
    'aspect' => 'aspect-[4/3]',
])

{{-- Drag-to-compare card. The "before" photo sits on top and is clipped at
     --pos; a transparent range input drives it, so mouse, touch and keyboard
     all work with no extra JS library. --}}
<figure class="bg-theme-primary rounded-2xl overflow-hidden border border-theme shadow-sm break-inside-avoid mb-6 focus-within:ring-4 focus-within:ring-accent-400/70">
    <div class="relative {{ $aspect }} select-none overflow-hidden bg-theme-tertiary" style="--pos: 50%" data-before-after>
        <img src="{{ $after }}" alt="{{ $title }} today" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
        <img src="{{ $before }}" alt="{{ $title }} before renovation" loading="lazy"
             class="absolute inset-0 w-full h-full object-cover grayscale-[35%]"
             style="clip-path: inset(0 calc(100% - var(--pos)) 0 0)">

        <span class="absolute top-3 left-3 text-[11px] font-bold uppercase tracking-wider bg-black/60 text-white px-2 py-1 rounded-md pointer-events-none">Before</span>
        <span class="absolute top-3 right-3 text-[11px] font-bold uppercase tracking-wider bg-accent-500 text-white px-2 py-1 rounded-md pointer-events-none">After</span>

        {{-- Divider + handle --}}
        <div class="absolute inset-y-0 w-0.5 bg-white shadow-[0_0_6px_rgba(0,0,0,.5)] pointer-events-none" style="left: var(--pos)">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white text-primary-700 shadow-lg flex items-center justify-center">
                <i class="fa-duotone fa-light fa-arrows-left-right"></i>
            </div>
        </div>

        <input type="range" min="0" max="100" value="50" aria-label="Slide to compare {{ $title }} before and after"
               class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize m-0"
               oninput="this.parentElement.style.setProperty('--pos', this.value + '%')">
    </div>
    <figcaption class="px-5 py-4 flex items-center justify-between gap-3">
        <div>
            <p class="font-bold text-theme-primary">{{ $title }}</p>
            @if($note)
                <p class="text-sm text-theme-tertiary">{{ $note }}</p>
            @endif
        </div>
        @if($url)
            <a href="{{ $url }}" class="shrink-0 inline-flex items-center gap-1.5 text-primary-600 dark:text-primary-400 font-semibold text-sm hover:underline">
                Visit <i class="fa-duotone fa-light fa-arrow-right text-xs"></i>
            </a>
        @endif
    </figcaption>
</figure>
