@extends('layouts.app')

@section('title', 'DORA')
@section('description', "Learn about Downtown Bellefontaine's DORA (Designated Outdoor Refreshment Area) - the rules, boundaries, and how it works during special events.")

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Designated Outdoor Refreshment Area"
    title="DORA District"
    subtitle="For special events, a designated cup, a drink in hand, and 46 acres of historic downtown to explore."
    image="/images/pages/dora-hero.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'DORA District']]" />

{{-- Intro --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">What is DORA?</h2>
        <p class="text-theme-secondary leading-relaxed">
            Established in 2020 for <strong class="text-theme-primary">Special Events Only</strong>, the City of Bellefontaine's DORA is a designated public area where alcoholic beverages can be purchased in a designated cup from permitted establishments and carried within the district. Keep up with
            <a href="https://www.facebook.com/DowntownBellefontaine/" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Downtown Bellefontaine on Facebook</a>
            for upcoming DORA events.
        </p>
    </div>
</section>

{{-- Map --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <span class="font-display text-2xl text-accent-500">The Boundaries</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Where Is It?</h2>
            <p class="text-theme-secondary mt-3 max-w-2xl mx-auto">
                In accordance with O.R.C. 4301.82 [B] [1] [b], the boundaries of the City of Bellefontaine DORA cover <strong class="text-theme-primary">46.14 acres</strong> of historic downtown.
            </p>
        </div>
        <figure class="bg-theme-primary rounded-2xl border border-theme p-4 sm:p-6 shadow-sm">
            <img src="/images/pages/dora-map.jpg" alt="DORA District Map" class="rounded-xl mx-auto w-full max-w-3xl">
            <figcaption class="text-sm text-center mt-3 text-theme-tertiary">Official DORA District map -- City of Bellefontaine</figcaption>
        </figure>
    </div>
</section>

{{-- Rules --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Know Before You Go</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">DORA Rules</h2>
            <p class="text-theme-secondary mt-3 max-w-2xl mx-auto">Enjoying the DORA safely and appropriately is important. Here's how it works.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach([
                ['icon' => 'fa-id-card', 'title' => 'ID and Wristband', 'body' => 'Present a valid state or federal ID to a participating DORA vendor, be 21 or older, and receive a wristband to wear while carrying your beverage.'],
                ['icon' => 'fa-store', 'title' => 'From Participating Vendors Only', 'body' => 'Purchase alcoholic beverages (beer and wine only) from a participating establishment or event vendor. No outside alcohol is permitted in the DORA.'],
                ['icon' => 'fa-circle-2', 'title' => 'Two-Drink Maximum', 'body' => 'You may purchase up to two beverages at a time. Beverages must be in an approved DORA cup.'],
                ['icon' => 'fa-wine-glass', 'title' => 'Approved Cup Required', 'body' => 'Any alcoholic beverage consumed in public areas within the DORA must be in an approved single-use DORA cup.'],
                ['icon' => 'fa-ban', 'title' => 'No Vendor Hopping', 'body' => "You cannot carry a DORA beverage into another DORA-serving business. Non-alcohol-serving businesses may allow drinks at their own discretion."],
                ['icon' => 'fa-clock', 'title' => 'Approved Hours Only', 'body' => 'DORA beverages may only be consumed during approved hours of operation -- typically Monday through Saturday, 9 a.m. to midnight, and Sunday 12 p.m. to 9 p.m.'],
            ] as $i => $rule)
                <div class="bg-theme-secondary rounded-2xl border border-theme p-6 flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center text-white font-bold">
                            {{ $i + 1 }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fa-duotone fa-light {{ $rule['icon'] }} text-accent-500"></i>
                            <h3 class="font-bold text-theme-primary">{{ $rule['title'] }}</h3>
                        </div>
                        <p class="text-theme-secondary text-sm leading-relaxed">{{ $rule['body'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Public Health & Safety --}}
<section class="py-16 bg-theme-secondary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <div>
                <span class="font-display text-2xl text-accent-500">Special Events Only</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2 mb-4">Public Health & Safety</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed text-sm">
                    <p>The City of Bellefontaine uses the DORA District for special events only. All host entities are required to submit a public health and safety plan with their event application, reviewed by city staff before any permit is issued.</p>
                    <p>City staff ensures adequate sanitation, signage, and public safety -- including portable restrooms, ADA accessibility, pedestrian mobility, police and fire services, ingress and egress, crowd control, and DORA boundary management. Organizers may be required to pay for special-duty officers or overtime for public safety workers.</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-theme-primary rounded-2xl border border-theme p-5 flex gap-4">
                    <i class="fa-duotone fa-light fa-shield-check text-2xl text-primary-500 flex-shrink-0 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-theme-primary mb-1">Beer & Wine Only</h3>
                        <p class="text-theme-secondary text-sm">It is the City's intent that only beer and wine may be carried through the DORA in approved DORA cups.</p>
                    </div>
                </div>
                <div class="bg-theme-primary rounded-2xl border border-theme p-5 flex gap-4">
                    <i class="fa-duotone fa-light fa-user-police text-2xl text-primary-500 flex-shrink-0 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-theme-primary mb-1">Police Discretion</h3>
                        <p class="text-theme-secondary text-sm">The Chief of Police may require additional officers at the host entity's expense, and may revise or end a DORA event at any time if it's in the public's best interest.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="font-display text-2xl text-accent-500">Good Questions</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Frequently Asked</h2>
        </div>

        <div class="space-y-4">
            @foreach([
                ['q' => 'Can I walk anywhere with my DORA cup?', 'a' => 'No. Patrons can only carry DORA beverages within the defined area and in an approved container. Retail and private establishments may set their own DORA policy.'],
                ['q' => 'What is a DORA cup?', 'a' => 'A single-use plastic cup designated for all establishments serving alcohol within the DORA. The DORA rules are printed on each cup.'],
                ['q' => 'Can I take my DORA beverage into another establishment?', 'a' => 'No. Once a DORA beverage leaves the business where it was purchased, it must be consumed (and the cup disposed of) before entering another establishment.'],
                ['q' => 'What hours is the DORA open?', 'a' => 'Generally Monday through Saturday, 9 a.m. to midnight, and Sunday noon to 9 p.m. Specific hours are set for each event. DORA beverages may not be consumed outside those hours.'],
                ['q' => 'How will I know where the DORA boundary is?', 'a' => 'Signs are installed at key entry and exit points along the boundary. The DORA map above shows the full district. More info is available on the City website.'],
                ['q' => 'Can I pour my own alcohol into a DORA cup?', 'a' => "No. Beverages must be purchased from participating vendors. No cans, glass bottles, or outside beverages of any kind are allowed in public DORA areas, and beverages may not be taken outside the DORA."],
            ] as $faq)
                <div class="bg-theme-secondary rounded-2xl border border-theme p-5">
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

@endsection
