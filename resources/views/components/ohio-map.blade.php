{{-- Ohio "you are here" map: star on Bellefontaine, spokes + drive times to nearby cities. --}}
@php
    // Simplified Ohio outline (lon, lat), projected with a flat cos(40°) scale.
    // Good enough for a "you are here" graphic, not for navigation.
    $outline = [
        [-84.82, 41.70], [-83.45, 41.73], [-83.00, 41.55], [-82.70, 41.45], [-82.20, 41.47],
        [-81.70, 41.50], [-81.25, 41.75], [-80.52, 41.98], [-80.52, 40.64], [-80.62, 40.40],
        [-80.70, 40.10], [-80.85, 39.75], [-81.20, 39.40], [-81.75, 39.20], [-82.00, 38.95],
        [-82.25, 38.60], [-82.60, 38.42], [-83.00, 38.72], [-83.65, 38.63], [-84.20, 38.80],
        [-84.55, 39.08], [-84.82, 39.10],
    ];
    $pad = 34;
    $k = 100;
    $xy = fn ($lon, $lat) => [round(($lon + 84.82) * $k * 0.766 + $pad, 1), round((41.98 - $lat) * $k + $pad, 1)];
    $path = 'M'.collect($outline)->map(fn ($p) => implode(',', $xy(...$p)))->implode(' L').' Z';

    [$hx, $hy] = $xy(-83.759, 40.361);

    // Drive times come from the Visitors Guide so both stay in sync.
    $times = collect(\App\Services\VisitorsGuide::DRIVE_TIMES)->pluck('time', 'place');
    // [name, lon, lat, label dx, label dy, anchor, time]
    $cities = [
        ['Columbus', -82.999, 39.961, 8, -3, 'start', $times['Columbus'] ?? '1 hr'],
        ['Dayton', -84.191, 39.759, -8, 4, 'end', $times['Dayton'] ?? '1 hr'],
        ['Springfield', -83.808, 39.924, 6, 18, 'start', $times['Springfield'] ?? '40 min'],
        ['Lima', -84.105, 40.742, -8, 4, 'end', $times['Lima'] ?? '45 min'],
        ['Marysville', -83.367, 40.236, 8, 4, 'start', $times['Marysville'] ?? '35 min'],
        ['Toledo', -83.555, 41.654, 8, 4, 'start', 'about 2 hr'],
        ['Cleveland', -81.694, 41.499, 0, 16, 'middle', 'about 2.5 hr'],
        ['Cincinnati', -84.512, 39.103, 8, 4, 'start', 'about 2 hr'],
    ];
    $w = round(4.30 * $k * 0.766 + $pad * 2);
    $h = round(3.58 * $k + $pad * 2);
    // Radius of a ~1 hour drive (distance to Columbus).
    [$cx, $cy] = $xy(-82.999, 39.961);
    $hour = round(sqrt(($cx - $hx) ** 2 + ($cy - $hy) ** 2));
@endphp

<div class="bg-theme-primary rounded-2xl border border-theme shadow-sm p-4 sm:p-6">
    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="w-full h-auto" role="img" aria-labelledby="wib-title">
        <title id="wib-title">Map of Ohio showing Bellefontaine about an hour from Columbus and Dayton</title>
        <path d="{{ $path }}" class="fill-primary-50 dark:fill-primary-950 stroke-primary-400" stroke-width="2" stroke-linejoin="round" />

        {{-- ~1 hour drive ring --}}
        <circle cx="{{ $hx }}" cy="{{ $hy }}" r="{{ $hour }}" class="fill-accent-500/10 stroke-accent-500" stroke-width="1.5" stroke-dasharray="5 5" />
        <text x="{{ $hx }}" y="{{ $hy - $hour - 5 }}" text-anchor="middle" class="fill-accent-600 dark:fill-accent-400" style="font-size: 11px; font-weight: 700; letter-spacing: .08em">1 HOUR DRIVE</text>

        @foreach($cities as [$name, $lon, $lat, $dx, $dy, $anchor, $time])
            @php [$x, $y] = $xy($lon, $lat); @endphp
            <line x1="{{ $hx }}" y1="{{ $hy }}" x2="{{ $x }}" y2="{{ $y }}" class="stroke-primary-300 dark:stroke-primary-700" stroke-width="1.2" stroke-dasharray="3 4" />
            <circle cx="{{ $x }}" cy="{{ $y }}" r="4" class="fill-primary-600 dark:fill-primary-400" />
            <text x="{{ $x + $dx }}" y="{{ $y + $dy }}" text-anchor="{{ $anchor }}" class="fill-current text-theme-primary" style="font-size: 12px; font-weight: 700">{{ $name }}
                <tspan x="{{ $x + $dx }}" dy="13" class="fill-current text-theme-tertiary" style="font-size: 10.5px; font-weight: 500">{{ $time }}</tspan>
            </text>
        @endforeach

        {{-- You are here --}}
        <circle cx="{{ $hx }}" cy="{{ $hy }}" r="16" class="fill-accent-500/25">
            <animate attributeName="r" values="10;20;10" dur="2.6s" repeatCount="indefinite" />
            <animate attributeName="opacity" values="1;.2;1" dur="2.6s" repeatCount="indefinite" />
        </circle>
        <polygon transform="translate({{ $hx }} {{ $hy }})" points="0,-13 3.8,-4.2 13,-4 5.8,2.2 8.2,11.5 0,6 -8.2,11.5 -5.8,2.2 -13,-4 -3.8,-4.2"
                 class="fill-accent-500 stroke-white" stroke-width="1.5" stroke-linejoin="round" />
        <text x="{{ $hx + 16 }}" y="{{ $hy - 10 }}" class="fill-current text-theme-primary" style="font-size: 14px; font-weight: 900; letter-spacing: .02em">BELLEFONTAINE</text>
    </svg>
</div>
