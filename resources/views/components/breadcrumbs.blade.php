@props(['items' => []])

@php
    $crumbs = collect($items)->filter(fn ($c) => !empty($c['label']))->values();
    $ld = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $crumbs->map(fn ($c, $i) => [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $c['label'],
            'item' => $c['url'] ?? url()->current(),
        ])->all(),
    ];
@endphp

@if($crumbs->count() > 1)
    <nav aria-label="Breadcrumb" class="bg-theme-secondary border-b border-theme">
        <ol class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-2 text-sm text-theme-tertiary">
            @foreach($crumbs as $i => $c)
                <li class="flex items-center gap-2">
                    @if($i > 0)
                        <i class="fa-duotone fa-light fa-chevron-right text-xs opacity-60"></i>
                    @endif
                    @if(!empty($c['url']) && !$loop->last)
                        <a href="{{ $c['url'] }}" class="hover:text-primary-500 transition-colors">{{ $c['label'] }}</a>
                    @else
                        <span class="text-theme-secondary" aria-current="page">{{ $c['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
    <script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
