@extends('layouts.app')

@section('title', 'DORA')
@section('description', "Downtown Bellefontaine's DORA (Designated Outdoor Refreshment Area) - grab a drink in an approved cup and explore 46 acres of historic downtown, seven days a week.")

@section('content')
{{-- Hero --}}
<x-page-hero
    eyebrow="Designated Outdoor Refreshment Area"
    title="DORA District"
    subtitle="A designated cup, a drink in hand, and 46 acres of historic downtown to explore."
    image="/images/pages/dora-hero.jpg" />

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'DORA District']]" />

{{-- Intro --}}
<section class="py-16 bg-theme-primary">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mb-4">What is DORA?</h2>
        <div class="space-y-4 text-theme-secondary leading-relaxed">
            <p>Established in 2020, the City of Bellefontaine's Designated Outdoor Refreshment Area (DORA) is a designated public area where alcoholic beverages can be purchased in an approved DORA cup from permitted establishments and carried throughout the district.</p>
            <p>The DORA adds another way to experience Downtown Bellefontaine, giving residents and visitors the opportunity to grab a drink and explore downtown's shops, restaurants, attractions and public spaces.</p>
            <p>Follow
                <a href="https://www.facebook.com/DowntownBellefontaine/" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Downtown Bellefontaine on Facebook</a>
                for the latest downtown events, activities and updates.</p>
        </div>
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
            <img src="/images/pages/dora-map.jpg" alt="Official DORA District map for the City of Bellefontaine"
                 data-lightbox data-lightbox-caption="Official DORA District map — City of Bellefontaine"
                 class="rounded-xl mx-auto w-full max-w-3xl cursor-zoom-in">
            <figcaption class="text-sm text-center mt-3 text-theme-tertiary">Official DORA District map — City of Bellefontaine</figcaption>
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
                ['icon' => 'fa-id-card', 'title' => 'ID', 'body' => 'Present a valid state or federal ID to a participating DORA vendor, be 21 or older, to purchase your beverage.'],
                ['icon' => 'fa-store', 'title' => 'From Participating Vendors Only', 'body' => 'Purchase alcoholic beverages from a participating establishment or event vendor. No outside alcohol is permitted within the DORA.'],
                ['icon' => 'fa-cup-straw', 'title' => 'Approved Cup Required', 'body' => 'Any alcoholic beverage consumed in public areas within the DORA must be in an approved, plastic, single-use DORA cup.'],
                ['icon' => 'fa-ban', 'title' => 'No Vendor Hopping', 'body' => 'You cannot carry a DORA beverage into another business that sells DORA beverages. Businesses that do not serve alcohol may choose whether to allow DORA beverages inside. Look for signage or check with the individual business before entering.'],
                ['icon' => 'fa-clock', 'title' => 'Approved Hours Only', 'body' => 'The DORA is in effect seven days a week — Monday through Saturday, 9 a.m. to midnight, and Sunday, noon to 9 p.m. The City of Bellefontaine may suspend DORA operations at its discretion.'],
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
                <span class="font-display text-2xl text-accent-500">Safe &amp; Welcoming</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2 mb-4">Public Health &amp; Safety</h2>
                <div class="space-y-4 text-theme-secondary leading-relaxed text-sm">
                    <p>The City of Bellefontaine is committed to maintaining a safe, clean and welcoming DORA throughout Historic Downtown Bellefontaine.</p>
                    <p>The City ensures appropriate sanitation, signage and public safety measures are addressed within the district. Depending on downtown activities and special events, considerations may include additional restroom facilities, accessibility, pedestrian mobility, police and fire services, ingress and egress, crowd control, DORA boundary management and additional waste receptacles.</p>
                    <p>The Bellefontaine Police Department maintains a presence downtown and is available to address concerns as they arise. Special events may also be required to provide additional police, public works or other staffing as needed.</p>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-theme-primary rounded-2xl border border-theme p-5 flex gap-4">
                    <i class="fa-duotone fa-light fa-wine-glass text-2xl text-primary-500 flex-shrink-0 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-theme-primary mb-1">Beer &amp; Wine Only</h3>
                        <p class="text-theme-secondary text-sm">It is the City's intent that only beer and wine may be carried through the DORA in approved DORA cups.</p>
                    </div>
                </div>
                <div class="bg-theme-primary rounded-2xl border border-theme p-5 flex gap-4">
                    <i class="fa-duotone fa-light fa-user-police text-2xl text-primary-500 flex-shrink-0 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-theme-primary mb-1">Police Discretion</h3>
                        <p class="text-theme-secondary text-sm">The Chief of Police may suspend DORA operations at any time if, in their professional judgment, doing so is in the best interest of public safety.</p>
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
            <h2 class="text-2xl sm:text-3xl font-bold text-theme-primary mt-2">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            @foreach([
                ['q' => 'Can I walk anywhere with my DORA cup?', 'a' => 'No. DORA beverages may only be carried within the defined DORA boundaries and must remain in an approved DORA container. Individual businesses within the district may choose whether to allow DORA beverages inside their establishments.'],
                ['q' => 'What is a DORA cup?', 'a' => 'A DORA cup is an approved, single-use plastic cup used by participating establishments to serve alcoholic beverages that may be carried within the DORA. DORA rules are provided with each cup.'],
                ['q' => 'Can I take my DORA beverage into another establishment?', 'a' => 'It depends on the establishment. You cannot carry a DORA beverage into another business that sells DORA beverages. Businesses that do not serve alcohol may choose whether to allow DORA beverages inside.'],
                ['q' => 'What hours is the DORA open?', 'a' => 'The DORA is in effect seven days a week. Hours are Monday through Saturday from 9 a.m. to midnight and Sunday from noon to 9 p.m. The City may suspend DORA operations from time to time at its discretion.'],
                ['q' => 'How will I know where the DORA boundary is?', 'a' => 'Signs are installed at key entry, exit and boundary points throughout the district. The DORA map above also shows the full designated area.'],
                ['q' => 'Can I pour my own alcohol into a DORA cup?', 'a' => 'No. Alcoholic beverages must be purchased from a participating DORA vendor and served in an approved DORA cup. Outside alcoholic beverages, cans and glass bottles are not permitted in public areas within the DORA. DORA beverages also cannot be taken outside the designated DORA boundaries.'],
                ['q' => 'Can I bring alcohol purchased somewhere else into the DORA?', 'a' => 'No. Only alcoholic beverages purchased from participating DORA establishments or approved event vendors and served in an approved DORA cup may be carried in public areas within the district.'],
                ['q' => 'Will the DORA create additional litter, noise or other issues downtown?', 'a' => 'The City of Bellefontaine has multiple trash receptacles throughout the DORA and may require event organizers to provide additional receptacles during special events. The Bellefontaine Police Department is available to address public safety concerns, and special events may be required to provide additional police, public works or other staffing when necessary. The City also retains the ability to suspend DORA operations when circumstances warrant.'],
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
