@extends('layouts.app')

@section('title', 'DORA')
@section('description', "Learn about Downtown Bellefontaine's DORA (Designated Outdoor Refreshment Area) - rules, map, and special event information.")

@section('content')
<div class="py-12 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-theme-primary mb-8">DORA - Designated Outdoor Refreshment Area</h1>

        <div class="prose prose-lg max-w-none text-theme-secondary">
            <p>Established in 2020 for Special Events Only, the City of Bellefontaine's DORA is a designated public area where alcoholic beverages can be purchased in a designated cup from permitted establishments and carried within the district.</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4 text-theme-primary">Where is Downtown Bellefontaine's DORA?</h2>

            <p>The DORA enhances Bellefontaine's revitalized downtown, encouraging more community members and visitors to stroll the area during special events. Keep up with <a href="https://www.facebook.com/DowntownBellefontaine/" target="_blank" class="text-primary-600 dark:text-primary-400 hover:underline">Downtown Bellefontaine Ohio on Facebook</a> to catch all of the upcoming events, and learn more about the City of Bellefontaine's Downtown DORA below!</p>

            <p>In accordance with O.R.C. 4301.82 [B] [1] [b], the boundaries of the City of Bellefontaine DORA are shown below. This area contains 46.14 Acres.</p>

            <figure class="my-6">
                <img src="/images/pages/dora-map.jpg" alt="DORA Map" class="rounded-lg mx-auto max-w-lg">
                <figcaption class="text-sm text-center mt-2 text-theme-tertiary">DORA Map</figcaption>
            </figure>

            <h2 class="text-2xl font-semibold mt-8 mb-4 text-theme-primary">Public Health and Safety</h2>

            <p>The City of Bellefontaine seeks to use a DORA District in its Historic Downtown for Special Events ONLY. All host entities for special events are required to develop and submit for approval a public health and safety plan along with their special event application. The proposed plan will be reviewed by city staff and others with interest along with the host entity. Only once the plan is agreed upon and all stipulations met will a permit be issued.</p>

            <p>City staff will ensure that adequate sanitation, signage, and public safety requirements are met for each unique event. The necessity for portable bathrooms, handicap accessibility, pedestrian mobility, police and fire services, ingress and egress, crowd control, DORA boundary management and other factors will be addressed. Event organizers will be required to pay for special duty officers or overtime for public safety workers if necessary.</p>

            <p>It is the City of Bellefontaine's intent that only beer and wine may be carried through the DORA District in City-approved DORA plastic cups.</p>

            <p>The Chief of Police will dictate at DORA events the need for additional police officers at the expense of the host entity. The Chief of Police shall have the ability to revise or end a DORA event at any time if, in his/her professional opinion, it is in the public's best interest to do so.</p>

            <h2 class="text-2xl font-semibold mt-8 mb-4 text-theme-primary">DORA Rules</h2>

            <p>Enjoying the DORA safely and appropriately is very important, so there are a few rules to follow.</p>

            <ol class="list-decimal list-inside space-y-2 my-4">
                <li>You must present your valid state or federal ID to a participating DORA vendor, be 21 years of age or older and receive a wristband that you must wear while carrying your beverage.</li>
                <li>You may purchase alcoholic beverages (beer and wine only) from a participating establishment or event vendor. No outside alcoholic beverages are permitted in the DORA.</li>
                <li>You may purchase up to two beverages at a time. Beverages must be in an approved DORA cup.</li>
                <li>Any alcoholic beverages being consumed in public areas in the DORA must be in an approved DORA single use cup.</li>
                <li>You cannot carry a DORA beverage into any other DORA beverage selling business. You may carry your beverage into any non-alcohol serving business at the sole discretion of that business.</li>
                <li>DORA beverages may only be consumed during the DORA approved hours of operation. Possible hours for future DORA events include Monday through Saturday, 9 a.m. to midnight, and Sunday 12 p.m. to 9 p.m.</li>
            </ol>

            <h2 class="text-2xl font-semibold mt-8 mb-4 text-theme-primary">DORA Frequently Asked Questions</h2>

            <div class="space-y-6">
                <div>
                    <p class="font-semibold text-theme-primary">Q: Can I walk anywhere with my DORA cup?</p>
                    <p>A: NO. Patrons can only carry DORA beverages in the defined area in the approved container. Retail and private establishments may allow DORA beverages or may opt to NOT allow them. Each establishment within the DORA will have their own policy.</p>
                </div>
                <div>
                    <p class="font-semibold text-theme-primary">Q: What is a DORA cup?</p>
                    <p>A: A plastic single-use cup designated for all establishments serving alcohol within the DORA. The DORA rules will be provided on a label with each cup.</p>
                </div>
                <div>
                    <p class="font-semibold text-theme-primary">Q: Can I take my DORA beverage into another establishment?</p>
                    <p>A: No. Once a DORA beverage has left the business where it was purchased, it must be consumed and the DORA cup disposed of prior to entering another establishment.</p>
                </div>
                <div>
                    <p class="font-semibold text-theme-primary">Q: What are the hours the DORA will be allowed to operate?</p>
                    <p>A: Generally, the allowable DORA event hours are Monday through Saturday, 9 a.m. to midnight. Sunday allowable hours are noon to 9 p.m. However, specific hours will be set for each DORA event. DORA beverages may not be consumed outside these hours.</p>
                </div>
                <div>
                    <p class="font-semibold text-theme-primary">Q: How will patrons know the DORA area limits?</p>
                    <p>A: Signs at key egress and endpoints will be installed on the boundaries. More information can be found on the DORA page on the City website.</p>
                </div>
                <div>
                    <p class="font-semibold text-theme-primary">Q: Can I pour my own alcohol in the DORA cup and carry it?</p>
                    <p>A: No. Beverages MUST be purchased from participating vendors. No cans, glass bottles, or outside beverages of any kind are allowed in public areas within DORA boundaries. In addition, DORA beverages may not be taken outside of the DORA route or patrons may be subject to legal consequences.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
