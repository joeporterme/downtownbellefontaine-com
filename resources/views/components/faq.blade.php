@props([
    'items' => [],
    'eyebrow' => 'Good Questions',
    'title' => 'Frequently Asked Questions',
    'bg' => 'bg-theme-primary',
    'card' => 'bg-theme-secondary',
])

{{-- Visible Q&A list + FAQPage structured data, so search engines and AI
     answer engines can quote the answers directly. Items: [['q' => , 'a' => ], ...]
     Answers are plain text (escaped). --}}
@if(count($items))
<section class="py-16 {{ $bg }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">{{ $eyebrow }}</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">{{ $title }}</h2>
        </div>

        <div class="space-y-4">
            @foreach($items as $faq)
                <div class="{{ $card }} rounded-2xl border border-theme p-5">
                    <div class="flex items-start gap-3">
                        <i class="fa-duotone fa-light fa-circle-question text-xl text-accent-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-theme-primary mb-1.5">{{ $faq['q'] }}</h3>
                            <p class="text-theme-secondary text-sm leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@php
    $faqLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($items)->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->values()->all(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqLd, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
