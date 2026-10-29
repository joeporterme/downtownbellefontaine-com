@props([
    'bg' => 'bg-theme-secondary',
    'eyebrow' => 'Where to find us',
    'title' => 'Close to Everything',
])

<section class="py-16 {{ $bg }}" id="where-is-bellefontaine">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            <div>
                <span class="font-display text-2xl sm:text-3xl text-accent-500">{{ $eyebrow }}</span>
                <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-theme-primary mt-1 mb-3">{{ $title }}</h2>
                <p class="text-lg text-theme-secondary leading-relaxed mb-6">
                    Downtown Bellefontaine sits in west central Ohio, right off US-33 and US-68, at the highest point in the state.
                    Columbus and Dayton are about an hour away, and Indian Lake and Mad River Mountain are just minutes up the road.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach(\App\Services\VisitorsGuide::DRIVE_TIMES as $d)
                        <div class="bg-theme-primary border border-theme rounded-xl px-3 py-2.5">
                            <p class="text-xs font-semibold uppercase tracking-wide text-theme-tertiary">{{ $d['place'] }}</p>
                            <p class="text-lg font-bold text-primary-600 dark:text-primary-400 leading-tight">{{ $d['time'] }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="https://www.google.com/maps/dir/?api=1&destination=Downtown+Bellefontaine,+OH" target="_blank" rel="noopener"
                   class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                    <i class="fa-duotone fa-light fa-route"></i> Get directions
                </a>
            </div>

            <x-ohio-map />
        </div>
    </div>
</section>
