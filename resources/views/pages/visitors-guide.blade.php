@extends('layouts.app')

@section('title', 'Visitors Guide')
@section('description', 'Your guide to exploring Downtown Bellefontaine like a local: an interactive map of every shop and restaurant, the annual events calendar, day itineraries, a scavenger hunt, and a printable PDF.')

@php
    $download = route('visitors-guide.download');
    $currentMonth = (int) now()->format('n');
    $clueColors = ['bg-accent-500', 'bg-[#8cc63e]', 'bg-primary-600', 'bg-primary-800'];
    $mapPins = collect($directory['pins'])->map(fn ($p) => [
        'id' => $p['id'], 'number' => $p['number'], 'name' => $p['name'], 'color' => $p['color'],
        'section' => $p['section'], 'lat' => $p['lat'], 'lng' => $p['lng'], 'url' => $p['url'],
        'address' => $p['address'], 'phone' => $p['phone'], 'directions' => $p['directions'],
    ])->values();
@endphp

@section('content')
<x-page-hero
    eyebrow="your guide to"
    title="Exploring Downtown Like a Local"
    subtitle="Every shop, restaurant, and hidden gem, plus the year's can't-miss events, ready-made day plans, and a scavenger hunt. Use it here, or take it with you."
    image="/images/home/welcome-courthouse.jpg"
    credit="Photo © Mac McGinnis">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-2">
        <a href="{{ $download }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-accent-500 hover:bg-accent-600 text-white font-bold rounded-xl shadow-lg transition-colors text-lg">
            <i class="fa-duotone fa-light fa-file-arrow-down"></i>
            Download the PDF Guide
        </a>
        <a href="#map" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold rounded-xl transition-colors">
            <i class="fa-duotone fa-light fa-map-location-dot"></i>
            Explore the map
        </a>
    </div>
    <p class="mt-5 inline-flex items-center gap-2 text-sm text-primary-100 bg-white/10 rounded-full px-4 py-1.5">
        <i class="fa-duotone fa-light fa-arrows-rotate text-accent-300"></i>
        Always current &middot; updated {{ $updated->format('F j, Y') }}
    </p>
</x-page-hero>

<x-breadcrumbs :items="[['label' => 'Home', 'url' => url('/')], ['label' => 'Plan a Visit', 'url' => route('pages.plan-a-visit')], ['label' => 'Visitors Guide']]" />

{{-- Sticky jump bar --}}
<nav aria-label="Guide sections" class="sticky top-20 z-40 bg-[var(--bg-primary)]/95 backdrop-blur border-b border-theme">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2">
        <div class="flex-1 flex items-center gap-1 overflow-x-auto py-2.5 -mx-1 px-1 [scrollbar-width:none]">
            @foreach ([['map', 'fa-map', 'Map & Directory'], ['events', 'fa-calendar-star', 'Events'], ['spend-a-day', 'fa-sun', 'Spend a Day'], ['scavenger-hunt', 'fa-magnifying-glass', 'Scavenger Hunt'], ['getting-here', 'fa-car-side', 'Getting Here']] as [$id, $icon, $label])
                <a href="#{{ $id }}" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium text-theme-secondary hover:bg-primary-50 dark:hover:bg-primary-900/40 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                    <i class="fa-duotone fa-light {{ $icon }} text-primary-500"></i>{{ $label }}
                </a>
            @endforeach
        </div>
        <a href="{{ $download }}" class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold bg-accent-500 hover:bg-accent-600 text-white transition-colors">
            <i class="fa-duotone fa-light fa-file-arrow-down"></i><span class="hidden sm:inline">PDF</span>
        </a>
    </div>
</nav>

{{-- ============================ MAP + DIRECTORY ============================ --}}
<section id="map" class="scroll-mt-36 py-14 bg-theme-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="font-display text-accent-500">find your way around</span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-theme-primary mt-1">Downtown Map &amp; Directory</h2>
            <p class="text-theme-secondary mt-3">Tap a number on the map or a name on the list. Everything's within a few blocks of the courthouse, so park once and wander.</p>
        </div>

        <div class="grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3">
                <div id="guide-map" class="h-[380px] sm:h-[480px] lg:h-[620px] rounded-2xl border border-theme shadow-sm bg-theme-secondary"></div>
            </div>

            <div class="lg:col-span-2 flex flex-col bg-theme-secondary rounded-2xl border border-theme overflow-hidden lg:h-[620px]">
                <div class="p-4 border-b border-theme space-y-3">
                    <label class="relative block">
                        <span class="sr-only">Search the directory</span>
                        <i class="fa-duotone fa-light fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-theme-tertiary"></i>
                        <input id="guide-search" type="search" placeholder="Search shops, food, salons…" autocomplete="off"
                               class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-theme bg-theme-primary text-theme-primary placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </label>
                    <div class="flex flex-wrap gap-1.5" id="guide-chips">
                        <button type="button" data-section="all" class="guide-chip is-active px-3 py-1 rounded-full text-xs font-semibold border border-theme">All</button>
                        @foreach (array_merge($directory['sections'], $directory['parking'] ? [\App\Services\VisitorsGuide::PARKING + ['slug' => 'parking']] : []) as $section)
                            <button type="button" data-section="{{ $section['slug'] }}" class="guide-chip px-3 py-1 rounded-full text-xs font-semibold border border-theme inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $section['color'] }}"></span>{{ $section['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div id="guide-list" class="flex-1 overflow-y-auto max-h-[520px] lg:max-h-none p-2">
                    @foreach (array_merge($directory['sections'], $directory['parking'] ? [\App\Services\VisitorsGuide::PARKING + ['slug' => 'parking', 'businesses' => $directory['parking']]] : []) as $section)
                        <div class="guide-group" data-section="{{ $section['slug'] }}">
                            <h3 class="sticky top-0 z-10 bg-[var(--bg-secondary)]/95 backdrop-blur px-2 pt-3 pb-1 flex items-center gap-2 text-xs font-bold uppercase tracking-wider" style="color: {{ $section['color'] }}">
                                <i class="fa-duotone fa-light {{ $section['icon'] }}"></i>{{ $section['label'] }}
                            </h3>
                            @foreach ($section['businesses'] as $b)
                                <div class="guide-item group rounded-xl px-2 py-2 flex items-start gap-3 cursor-pointer hover:bg-theme-primary transition-colors"
                                     data-id="{{ $b['id'] }}" data-section="{{ $section['slug'] }}" data-search="{{ strtolower($b['name'].' '.$b['address'].' '.$section['label']) }}"
                                     tabindex="0" role="button" aria-label="Show {{ $b['name'] }} on the map">
                                    <span class="shrink-0 mt-0.5 min-w-7 h-7 px-1.5 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-sm" style="background-color: {{ $b['color'] }}">{{ $b['number'] }}</span>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-theme-primary leading-tight">{{ $b['name'] }}</p>
                                        <p class="text-xs text-theme-tertiary mt-0.5">{{ $b['address'] }}</p>
                                        <div class="guide-actions hidden mt-2 flex-wrap gap-x-4 gap-y-1 text-xs font-semibold">
                                            @if ($section['slug'] !== 'parking')
                                                <a href="{{ $b['url'] }}" class="text-primary-600 dark:text-primary-400 hover:underline"><i class="fa-duotone fa-light fa-store mr-1"></i>Details</a>
                                            @endif
                                            <a href="{{ $b['directions'] }}" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline"><i class="fa-duotone fa-light fa-diamond-turn-right mr-1"></i>Directions</a>
                                            @if ($b['phone'])
                                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $b['phone']) }}" class="text-primary-600 dark:text-primary-400 hover:underline"><i class="fa-duotone fa-light fa-phone mr-1"></i>{{ $b['phone'] }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <p id="guide-empty" class="hidden text-center text-sm text-theme-tertiary py-10">Nothing matches that. Try another word?</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================ SIGNATURE EVENTS ============================ --}}
<section id="events" class="scroll-mt-36 py-14 bg-theme-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="font-display text-accent-500">mark your calendar</span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-theme-primary mt-1">Signature Events</h2>
            <p class="text-theme-secondary mt-3">Something to look forward to every single month. Downtown Bellefontaine knows how to throw a party.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach ($calendar as $month)
                @php $isNow = $month['month'] === $currentMonth; @endphp
                <div class="relative rounded-2xl p-4 sm:p-5 border {{ $isNow ? 'bg-accent-500 border-accent-500 text-white shadow-lg' : 'bg-theme-primary border-theme' }}">
                    @if ($isNow)
                        <span class="absolute -top-2.5 right-3 text-[10px] font-bold uppercase tracking-wider bg-primary-800 text-white rounded-full px-2 py-0.5">This month</span>
                    @endif
                    <p class="text-xs font-bold uppercase tracking-widest {{ $isNow ? 'text-white/80' : 'text-accent-600' }}">{{ $month['name'] }}</p>
                    @forelse ($month['events'] as $event)
                        <p class="mt-1.5 font-black uppercase leading-tight {{ $isNow ? 'text-white' : 'text-theme-primary' }}">
                            @if ($event->url)<a href="{{ $event->url }}" class="hover:underline">{{ $event->name }}</a>@else{{ $event->name }}@endif
                        </p>
                        @if ($event->timing_note)
                            <p class="text-xs mt-0.5 {{ $isNow ? 'text-white/80' : 'text-theme-tertiary' }}">{{ $event->timing_note }}</p>
                        @endif
                        @if ($event->blurb)
                            <p class="text-sm mt-1.5 leading-snug {{ $isNow ? 'text-white/90' : 'text-theme-secondary' }}">{{ $event->blurb }}</p>
                        @endif
                    @empty
                        <p class="mt-1.5 text-sm {{ $isNow ? 'text-white/90' : 'text-theme-tertiary' }}">Something's always brewing downtown.</p>
                    @endforelse
                </div>
            @endforeach
        </div>

        @if ($upcoming->isNotEmpty())
            <div class="mt-12">
                <div class="flex items-end justify-between gap-4 mb-4">
                    <h3 class="text-xl font-bold text-theme-primary"><i class="fa-duotone fa-light fa-calendar-clock text-accent-500 mr-2"></i>Coming up next</h3>
                    <a href="{{ route('events.index') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline whitespace-nowrap">All events <i class="fa-duotone fa-light fa-arrow-right text-xs"></i></a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($upcoming as $event)
                        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 bg-theme-primary rounded-xl border border-theme p-4 hover:shadow-md hover:border-primary-300 transition">
                            <div class="shrink-0 w-14 text-center rounded-lg bg-primary-600 text-white py-1.5">
                                <p class="text-[11px] font-bold uppercase tracking-wider">{{ $event->event_date->format('M') }}</p>
                                <p class="text-2xl font-black leading-none">{{ $event->event_date->format('j') }}</p>
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-theme-primary leading-tight">{{ $event->title }}</p>
                                <p class="text-xs text-theme-tertiary mt-1">{{ $event->event_date->format('l') }}@if ($event->formatted_time) &middot; {{ $event->formatted_time }}@endif</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ============================ SPEND A DAY ============================ --}}
@if ($timeline->isNotEmpty())
<section id="spend-a-day" class="scroll-mt-36 py-14 bg-theme-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="font-display text-[#8cc63e]">spend a day exploring</span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-theme-primary mt-1">Bellefontaine!</h2>
            <p class="text-theme-secondary mt-3">Not sure where to start? Here's a full day, morning to night. Each stop has its own itinerary with all the details.</p>
        </div>

        <ol class="relative border-l-4 border-accent-500/70 ml-3 sm:ml-24 space-y-8">
            @foreach ($timeline as $stop)
                <li class="relative pl-6 sm:pl-8">
                    <span class="absolute -left-[13px] top-1.5 w-5 h-5 rounded-full bg-accent-500 ring-4 ring-[var(--bg-primary)]" aria-hidden="true"></span>
                    <p class="sm:absolute sm:-left-[7.5rem] sm:top-1 sm:w-24 sm:text-right font-black text-primary-700 dark:text-primary-300 text-lg mb-2 sm:mb-0">{{ $stop['time'] }}</p>
                    <a href="{{ $stop['url'] }}" class="group flex flex-col sm:flex-row gap-4 bg-theme-secondary rounded-2xl border border-theme overflow-hidden hover:shadow-lg transition-shadow">
                        @if ($stop['image'])
                            <img src="{{ $stop['image'] }}" alt="" loading="lazy" class="sm:w-44 h-40 sm:h-auto object-cover">
                        @endif
                        <div class="p-5 {{ $stop['image'] ? 'sm:pl-1' : '' }}">
                            <h3 class="font-black uppercase text-accent-600 leading-tight">{{ $stop['title'] }}</h3>
                            <p class="text-theme-secondary text-sm mt-2 leading-relaxed">{{ $stop['teaser'] }}</p>
                            <span class="inline-flex items-center gap-1.5 mt-3 text-sm font-semibold text-primary-600 dark:text-primary-400 group-hover:underline">See the itinerary <i class="fa-duotone fa-light fa-arrow-right text-xs"></i></span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ol>

        <div class="mt-10 flex flex-wrap justify-center gap-3">
            <a href="{{ route('day-agendas.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                <i class="fa-duotone fa-light fa-route"></i> More day agendas
            </a>
            <a href="{{ route('pages.historic-walking-tour') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-theme-secondary hover:bg-theme-tertiary border border-theme text-theme-primary font-semibold rounded-lg transition-colors">
                <i class="fa-duotone fa-light fa-landmark"></i> Historic Walking Tour
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============================ SCAVENGER HUNT ============================ --}}
@if ($clues->isNotEmpty())
<section id="scavenger-hunt" class="scroll-mt-36 py-14 bg-primary-900 dark:bg-primary-950 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="font-display text-[#8cc63e]">downtown bellefontaine</span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight mt-1">Scavenger Hunt!</h2>
            <p class="text-primary-100 mt-3">Grab the crew and see how well you really know downtown. Find each spot, tap <strong>Found it!</strong>, and keep score right here on your phone.</p>
        </div>

        <div class="max-w-md mx-auto mb-8">
            <div class="flex items-center justify-between text-sm font-semibold mb-1.5">
                <span id="hunt-count">0 of {{ $clues->count() }} found</span>
                <button type="button" id="hunt-reset" class="text-primary-200 hover:text-white underline-offset-2 hover:underline hidden">Start over</button>
            </div>
            <div class="h-3 rounded-full bg-white/15 overflow-hidden">
                <div id="hunt-bar" class="h-full bg-[#8cc63e] rounded-full transition-all duration-500" style="width:0%"></div>
            </div>
            <p id="hunt-done" class="hidden text-center font-display text-accent-300 mt-4">you found them all!</p>
        </div>

        <div class="grid md:grid-cols-2 gap-3">
            @foreach ($clues as $i => $clue)
                <div class="hunt-clue flex bg-white text-primary-950 rounded-xl overflow-hidden shadow-sm transition" data-clue="{{ $clue->id }}">
                    <div class="shrink-0 w-14 sm:w-16 flex items-center justify-center text-xl font-black text-white {{ $clueColors[$i % count($clueColors)] }}">
                        <span class="hunt-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        {{-- Wrapped: Font Awesome's display rule would override `hidden` on the <i>. --}}
                        <span class="hunt-check hidden"><i class="fa-duotone fa-light fa-check text-2xl"></i></span>
                    </div>
                    <div class="flex-1 p-4">
                        <p class="font-bold uppercase text-sm leading-snug">{{ $clue->clue }}</p>
                        @if ($clue->answer)
                            <p class="hunt-answer hidden mt-2 text-sm text-primary-700">
                                <i class="fa-duotone fa-light fa-lightbulb-on text-accent-500 mr-1"></i>
                                @if ($clue->business)
                                    <a href="{{ route('businesses.show', $clue->business) }}" class="font-semibold underline">{{ $clue->answer }}</a>
                                @else
                                    <span class="font-semibold">{{ $clue->answer }}</span>
                                @endif
                            </p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <button type="button" class="hunt-found inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border-2 border-primary-600 text-primary-700 hover:bg-primary-50 transition-colors" aria-pressed="false">
                                <i class="fa-duotone fa-light fa-flag-checkered"></i><span>Found it!</span>
                            </button>
                            @if ($clue->answer)
                                <button type="button" class="hunt-reveal px-3 py-1.5 rounded-lg text-xs font-semibold text-primary-600 hover:bg-primary-50 transition-colors">
                                    <i class="fa-duotone fa-light fa-eye mr-1"></i>Reveal answer
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================ GETTING HERE ============================ --}}
<section id="getting-here" class="scroll-mt-36 py-14 bg-theme-secondary">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <span class="font-display text-accent-500">come on over</span>
            <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-theme-primary mt-1">Getting Here</h2>
            <p class="text-theme-secondary mt-3 leading-relaxed">Bellefontaine sits right in the middle of west-central Ohio, the highest point in the state, an easy drive from just about everywhere. Once you're here, park once and walk: downtown is compact, friendly, and best explored on foot.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('pages.plan-a-visit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-compass"></i> Plan your visit
                </a>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ rawurlencode('Logan County Courthouse, Bellefontaine, OH') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-5 py-2.5 bg-theme-primary hover:bg-theme-tertiary border border-theme text-theme-primary font-semibold rounded-lg transition-colors">
                    <i class="fa-duotone fa-light fa-diamond-turn-right"></i> Directions to downtown
                </a>
            </div>
        </div>
        <div>
            <x-ohio-map />
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($driveTimes as $d)
                    <div class="bg-theme-primary rounded-xl border border-theme p-4 text-center">
                        <p class="text-2xl font-black text-accent-500 leading-none">{{ $d['time'] }}</p>
                        <p class="text-xs font-semibold text-theme-secondary mt-1.5">{{ $d['place'] }}</p>
                    </div>
                @endforeach
            </div>
            @if ($directory['parking'])
                <div class="mt-4 flex items-start gap-3 bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 rounded-xl p-4">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-[#0ea5e9] text-white font-black flex items-center justify-center">P</span>
                    <p class="text-sm text-theme-secondary"><strong class="text-theme-primary">Parking is easy.</strong> Look for the blue <strong>P</strong> pins on the map: {{ collect($directory['parking'])->pluck('name')->map(fn ($n) => trim(str_ireplace('-parking', '', $n)))->join(', ', ' and ') }}, plus plenty of on-street spots around the square.</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ============================ TAKE IT WITH YOU ============================ --}}
<section class="py-14 bg-theme-primary">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-accent-500 text-white px-6 py-10 sm:px-12 sm:py-12 grid md:grid-cols-[1fr_auto] gap-6 items-center">
            <img src="/images/home/pineapple.svg" alt="" aria-hidden="true" class="absolute -right-8 -bottom-10 w-48 opacity-15 rotate-12 pointer-events-none">
            <div class="relative">
                <span class="font-display text-white">take it with you</span>
                <h2 class="text-2xl sm:text-3xl font-black uppercase tracking-tight mt-1">The printable guide</h2>
                <p class="text-white/90 mt-2 max-w-xl">Map, directory, events, and the scavenger hunt on four pages. Print it, fold it, and stash it in the glovebox. It's built fresh from this page, so it's never out of date.</p>
            </div>
            <a href="{{ $download }}" class="relative inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-white text-accent-600 hover:bg-primary-50 font-bold rounded-xl shadow-lg transition-colors text-lg whitespace-nowrap">
                <i class="fa-duotone fa-light fa-file-arrow-down"></i> Download PDF
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const PINS = @json($mapPins);
    const list = document.getElementById('guide-list');
    const items = Array.from(document.querySelectorAll('.guide-item'));
    const groups = Array.from(document.querySelectorAll('.guide-group'));
    const chips = Array.from(document.querySelectorAll('.guide-chip'));
    const search = document.getElementById('guide-search');
    const empty = document.getElementById('guide-empty');
    let map, info, markers = {}, activeSection = 'all', activeId = null;

    function esc(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    }

    // ---- Directory filtering (works with or without the map) ----
    function applyFilters() {
        const q = search.value.trim().toLowerCase();
        let shown = 0;
        items.forEach(el => {
            const ok = (activeSection === 'all' || el.dataset.section === activeSection) && (!q || el.dataset.search.includes(q));
            el.classList.toggle('hidden', !ok);
            if (ok) shown++;
            const m = markers[el.dataset.id];
            if (m) m.setVisible(ok);
        });
        groups.forEach(g => g.classList.toggle('hidden', !g.querySelector('.guide-item:not(.hidden)')));
        empty.classList.toggle('hidden', shown > 0);
    }

    chips.forEach(chip => chip.addEventListener('click', () => {
        activeSection = chip.dataset.section;
        chips.forEach(c => c.classList.toggle('is-active', c === chip));
        applyFilters();
        if (map) fitVisible();
    }));
    search.addEventListener('input', applyFilters);

    function select(id, fromMarker) {
        activeId = String(id);
        items.forEach(el => {
            const on = el.dataset.id === activeId;
            el.classList.toggle('is-selected', on);
            el.querySelector('.guide-actions').classList.toggle('hidden', !on);
            el.querySelector('.guide-actions').classList.toggle('flex', on);
        });
        const el = items.find(i => i.dataset.id === activeId);
        if (el && fromMarker) {
            list.scrollTo({ top: el.offsetTop - list.offsetTop - 48, behavior: 'smooth' });
        }
        const pin = PINS.find(p => String(p.id) === activeId);
        if (map && pin) {
            map.panTo({ lat: pin.lat, lng: pin.lng });
            if (map.getZoom() < 17) map.setZoom(17);
            info.setContent(
                '<div style="font-family:Montserrat,sans-serif;max-width:220px">' +
                '<div style="display:flex;gap:8px;align-items:center"><span style="background:' + pin.color + ';color:#fff;border-radius:999px;min-width:22px;height:22px;padding:0 6px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:11px">' + esc(pin.number) + '</span>' +
                '<strong style="font-size:14px;color:#0b3d44">' + esc(pin.name) + '</strong></div>' +
                '<div style="font-size:12px;color:#5a7479;margin:4px 0 6px 30px">' + esc(pin.address) + '</div>' +
                '<div style="margin-left:30px;font-size:12px;font-weight:600">' +
                (pin.section !== 'parking' ? '<a href="' + esc(pin.url) + '" style="color:#01757f;margin-right:10px">Details</a>' : '') +
                '<a href="' + esc(pin.directions) + '" target="_blank" rel="noopener" style="color:#01757f">Directions</a></div></div>'
            );
            info.open({ map, anchor: markers[activeId] });
        }
    }

    items.forEach(el => {
        el.addEventListener('click', e => { if (!e.target.closest('a')) select(el.dataset.id, false); });
        el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); select(el.dataset.id, false); } });
    });

    function fitVisible() {
        const b = new google.maps.LatLngBounds();
        let n = 0;
        PINS.forEach(p => { if (markers[p.id]?.getVisible()) { b.extend({ lat: p.lat, lng: p.lng }); n++; } });
        if (n > 1) map.fitBounds(b, 48);
        else if (n === 1) { map.setCenter(b.getCenter()); map.setZoom(17); }
    }

    // ---- Google map with numbered pins matching the printed guide ----
    window.initGuideMap = function () {
        map = new google.maps.Map(document.getElementById('guide-map'), {
            center: { lat: 40.3614, lng: -83.7596 },
            zoom: 16,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            clickableIcons: false,
            styles: [
                { featureType: 'poi', stylers: [{ visibility: 'off' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] },
                { featureType: 'landscape', stylers: [{ color: '#f6f1e7' }] },
                { featureType: 'road.arterial', elementType: 'geometry', stylers: [{ color: '#fde3d3' }] },
            ],
        });
        info = new google.maps.InfoWindow();

        PINS.forEach(p => {
            const wide = p.number.length > 2;
            const marker = new google.maps.Marker({
                position: { lat: p.lat, lng: p.lng },
                map,
                title: p.number + '. ' + p.name,
                zIndex: 1000 - (parseInt(p.number, 10) || 0),
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: wide ? 13 : 11,
                    fillColor: p.color,
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2,
                },
                label: { text: p.number, color: '#ffffff', fontSize: wide ? '10px' : '11px', fontWeight: '700', fontFamily: 'Montserrat, sans-serif' },
            });
            marker.addListener('click', () => select(p.id, true));
            markers[p.id] = marker;
        });

        // Frame the downtown core (same footprint as the printed map), not every outlying pin.
        const core = new google.maps.LatLngBounds();
        PINS.forEach(p => {
            if (Math.abs(p.lat - 40.3614) < 0.0028 && Math.abs(p.lng + 83.7596) < 0.0038) core.extend({ lat: p.lat, lng: p.lng });
        });
        if (!core.isEmpty()) {
            map.fitBounds(core, 24);
            google.maps.event.addListenerOnce(map, 'idle', () => { if (map.getZoom() < 16) map.setZoom(16); });
        }
        applyFilters();
    };

    // ---- Scavenger hunt: progress saved on this device ----
    const KEY = 'dtb_scavenger_found';
    const clues = Array.from(document.querySelectorAll('.hunt-clue'));
    if (clues.length) {
        let found = [];
        try { found = JSON.parse(localStorage.getItem(KEY) || '[]'); } catch (e) {}

        const render = () => {
            clues.forEach(c => {
                const on = found.includes(c.dataset.clue);
                c.classList.toggle('opacity-75', on);
                c.querySelector('.hunt-num').classList.toggle('hidden', on);
                c.querySelector('.hunt-check').classList.toggle('hidden', !on);
                const btn = c.querySelector('.hunt-found');
                btn.setAttribute('aria-pressed', on ? 'true' : 'false');
                btn.classList.toggle('bg-primary-600', on);
                btn.classList.toggle('text-white', on);
                btn.classList.toggle('text-primary-700', !on);
                btn.querySelector('span').textContent = on ? 'Found!' : 'Found it!';
            });
            const n = found.length, total = clues.length;
            document.getElementById('hunt-count').textContent = n + ' of ' + total + ' found';
            document.getElementById('hunt-bar').style.width = (total ? (n / total) * 100 : 0) + '%';
            document.getElementById('hunt-done').classList.toggle('hidden', n < total);
            document.getElementById('hunt-reset').classList.toggle('hidden', n === 0);
        };
        const save = () => { try { localStorage.setItem(KEY, JSON.stringify(found)); } catch (e) {} };

        clues.forEach(c => {
            c.querySelector('.hunt-found').addEventListener('click', () => {
                const id = c.dataset.clue;
                found = found.includes(id) ? found.filter(x => x !== id) : found.concat(id);
                save(); render();
            });
            const reveal = c.querySelector('.hunt-reveal');
            if (reveal) reveal.addEventListener('click', () => {
                c.querySelector('.hunt-answer').classList.remove('hidden');
                reveal.remove();
            });
        });
        document.getElementById('hunt-reset').addEventListener('click', () => { found = []; save(); render(); });
        // Drop ids for clues that no longer exist.
        found = found.filter(id => clues.some(c => c.dataset.clue === id));
        render();
    }
})();
</script>
<style>
    .guide-chip { color: var(--text-secondary, inherit); }
    .guide-chip.is-active { background-color: #01757f; border-color: #01757f; color: #fff; }
    .guide-chip.is-active span { box-shadow: 0 0 0 2px #fff; }
    .guide-item.is-selected { background-color: rgb(1 117 127 / 0.08); box-shadow: inset 3px 0 0 #01757f; }
</style>
<x-google-maps-script callback="initGuideMap" />
@endpush
