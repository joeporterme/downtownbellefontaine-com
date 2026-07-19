@props([
    'business',
    'height' => 'h-44',
    'icon' => 'fa-store',
])

@php
    $sv = $business->hasStreetView;
    $listing = $business->listingImageUrl;               // Street View snapshot, else featured image
    $logo = $business->logo ? \App\Support\Media::url($business->logo) : null;
    $featured = $business->featured_image ? \App\Support\Media::url($business->featured_image) : null;
    $avatar = $logo ?: $featured;                         // small badge (prefer a real logo)
    $showAvatar = $avatar && $avatar !== $listing;        // don't repeat the same image as its own badge
@endphp

<div class="relative w-full {{ $height }} overflow-hidden">
    @if($sv || $featured)
        {{-- Street View (enhanced) or the uploaded featured photo --}}
        <img src="{{ $listing }}" alt="{{ $business->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 {{ $sv ? 'streetview-img' : '' }}">
    @elseif($logo)
        {{-- Logo-only business: show it contained --}}
        <div class="w-full h-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center p-4">
            <img src="{{ $logo }}" alt="{{ $business->name }}" class="max-h-full max-w-full object-contain">
        </div>
    @else
        {{-- No imagery yet --}}
        <div class="w-full h-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
            <i class="fa-duotone fa-light {{ $icon }} text-4xl text-primary-300 dark:text-primary-700"></i>
        </div>
    @endif

    @if($showAvatar)
        <div class="absolute bottom-2 left-2 w-12 h-12 rounded-full bg-white dark:bg-gray-900 shadow-md ring-2 ring-white dark:ring-gray-900 overflow-hidden">
            <img src="{{ $avatar }}" alt="{{ $business->name }} logo" class="w-full h-full object-cover">
        </div>
    @endif
</div>
