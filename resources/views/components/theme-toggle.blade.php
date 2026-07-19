@props(['class' => ''])

{{-- Single button that cycles Light → Dark → System. The visible icon is the
     current mode. Icons are wrapped in spans so Tailwind's `hidden` (display:none)
     wins over Font Awesome's display rule on <i>. --}}
<button type="button" data-theme-cycle aria-label="Change color theme"
        {{ $attributes->merge(['class' => 'relative p-2 rounded-lg hover:bg-theme-tertiary transition-colors ' . $class]) }}>
    <span data-theme-icon="light" class="hidden">
        <i class="fa-duotone fa-light fa-sun-bright text-lg text-accent-500"></i>
    </span>
    <span data-theme-icon="dark" class="hidden">
        <i class="fa-duotone fa-light fa-moon-stars text-lg text-theme-secondary"></i>
    </span>
    <span data-theme-icon="system" class="hidden">
        <i class="fa-duotone fa-light fa-desktop text-lg text-theme-secondary"></i>
    </span>
</button>
