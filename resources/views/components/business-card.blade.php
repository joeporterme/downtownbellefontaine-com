@props(['business', 'icon', 'bg' => 'bg-theme-primary', 'height' => 'h-44'])

<a href="{{ route('businesses.show', $business) }}" class="{{ $bg }} rounded-xl shadow border border-theme overflow-hidden hover:shadow-lg transition-all duration-300 group">
    <x-business-card-image :business="$business" :icon="$icon" :height="$height" />

    <div class="p-4">
        <h3 class="text-lg font-semibold text-theme-primary mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $business->name }}</h3>

        @if($business->address)
            <p class="text-theme-tertiary text-sm flex items-center gap-1.5">
                <i class="fa-duotone fa-light fa-location-dot text-primary-500 flex-shrink-0"></i>
                <span class="truncate">{{ $business->address }}</span>
            </p>
        @endif
    </div>
</a>
