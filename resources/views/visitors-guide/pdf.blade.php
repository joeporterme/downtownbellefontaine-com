{{--
    Printable Visitors Guide (mPDF). Landscape Letter, 4 pages, styled after
    the 2023 printed trifold. mPDF has no flex/grid, so layout uses fixed
    panels (position:absolute) with overflow:auto, which shrinks text to fit
    rather than spilling onto another page.

    mPDF quirks worth knowing: Friday Vibes renders small (sizes here are
    roughly double what you'd expect) and has no "&" glyph; table cells do
    not inherit text color, so every cell on a dark panel sets its own.
--}}
@php
    $teal = '#01757f';
    $deep = '#0b3d44';
    $orange = '#f3773d';
    $lime = '#8cc63e';
    $cream = '#fbf6ec';
    $site = rtrim(config('app.url'), '/');
    $host = parse_url($site, PHP_URL_HOST) ?: 'downtownbellefontaine.com';
    $img = fn ($p) => public_path($p);
    // Script text: Friday Vibes has no & ? ! or apostrophe, so those
    // characters fall back to Montserrat.
    $script = fn ($s) => preg_replace('/(&amp;|&#039;|\?|!)/', '<span style="font-family:montserrat; font-size:55%;">$1</span>', e(strtolower($s)));
    $clueColors = [$orange, $lime, $teal, '#16545d'];
@endphp
<html>
<head>
<style>
    @page { margin: 0; }
    body { font-family: montserrat; color: {{ $deep }}; font-size: 8pt; }
    .script { font-family: fridayvibes; font-weight: normal; }
    .black { font-family: montserratblack; font-weight: normal; text-transform: uppercase; }
    .semi { font-family: montserratsemibold; }
    .panel { position: absolute; overflow: auto; }
    .eyebrow { font-family: fridayvibes; color: {{ $orange }}; font-size: 40pt; line-height: 0.9; }
    .h1 { font-family: montserratblack; text-transform: uppercase; font-size: 22pt; line-height: 1; letter-spacing: 0.3pt; }
    .muted { color: #5a7479; }
    table { border-collapse: collapse; }
    td { vertical-align: top; }
</style>
</head>
<body>

{{-- ============================ PAGE 1: COVER ============================ --}}
<div class="panel" style="left:0; top:0; width:170mm; height:215.9mm;">
    <img src="{{ $cover }}" style="width:170mm; height:215.9mm;" />
</div>
<div class="panel" style="left:12mm; top:108mm; width:152mm; height:86mm;">
    <div class="script" style="color:{{ $orange }}; font-size:46pt; line-height:0.9;">- your guide to -</div>
    <div class="black" style="color:#ffffff; font-size:46pt; line-height:0.95;">Exploring<br>Downtown</div>
    <div class="script" style="color:{{ $lime }}; font-size:92pt; line-height:0.9;">Like a Local</div>
</div>
<div class="panel" style="left:3mm; top:195mm; width:60mm; height:4mm; color:#ffffff; font-size:5.5pt;">Photo &copy; Mac McGinnis</div>
<div class="panel" style="left:0; top:200mm; width:170mm; height:15.9mm; background-color:{{ $orange }}; color:#ffffff; text-align:center;">
    <div class="black" style="color:#ffffff; font-size:15pt; padding-top:4mm; letter-spacing:1pt;">{{ strtoupper($host) }}</div>
</div>

<div class="panel" style="left:170mm; top:0; width:109.4mm; height:215.9mm; background-color:{{ $cream }};">
    <div style="padding:14mm 11mm 0 11mm; text-align:center;">
        <img src="{{ $img('images/pages/downtown-logo.png') }}" style="width:86mm;" />

        <table style="margin:6mm auto 0 auto;"><tr>
            <td style="background-color:{{ $teal }}; color:#ffffff; font-weight:bold; font-size:8pt; padding:1.6mm 4mm; letter-spacing:0.5pt;">UPDATED {{ strtoupper($updated->format('F Y')) }}</td>
        </tr></table>

        <div class="script" style="color:{{ $orange }}; font-size:40pt; margin-top:7mm; line-height:0.9;">inside this guide</div>
        <table style="width:100%; margin-top:2mm; font-size:9.5pt;">
            @foreach ([['Downtown Map', 2], ['Who\'s Who Directory', 3], ['Signature Events Calendar', 4], ['Spend a Day Exploring', 4], ['Downtown Scavenger Hunt', 4]] as [$label, $page])
                <tr>
                    <td style="padding:1.6mm 0; border-bottom:0.3mm dotted #c9b99a; text-align:left; color:{{ $deep }};" class="semi">{{ $label }}</td>
                    <td style="padding:1.6mm 0; border-bottom:0.3mm dotted #c9b99a; text-align:right; color:{{ $orange }}; font-weight:bold;">p. {{ $page }}</td>
                </tr>
            @endforeach
        </table>

        <table style="width:100%; margin-top:8mm;">
            <tr>
                <td style="width:30mm; vertical-align:middle;">
                    <barcode code="{{ $site }}/visitors-guide" type="QR" size="0.85" error="M" disableborder="1" />
                </td>
                <td style="vertical-align:middle; text-align:left; padding-left:3mm; color:{{ $deep }};">
                    <div class="black" style="font-size:10pt; color:{{ $teal }};">Go interactive</div>
                    <div style="font-size:8pt;">Scan for the live map, today's events, and the newest version of this guide.</div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="panel" style="left:170mm; top:185mm; width:109.4mm; height:5mm; text-align:center; font-size:6.5pt; letter-spacing:0.6pt; font-weight:bold; color:{{ $teal }};">A COMMUNITY COLLABORATION BROUGHT TO YOU BY</div>
<div class="panel" style="left:174mm; top:192mm; width:101mm; height:16mm; text-align:center;">
    <img src="{{ $img('images/ohio-christmas-capital.png') }}" style="height:13mm;" />&nbsp;&nbsp;
    <img src="{{ $img('images/partners/small-nation.svg') }}" style="height:10mm;" />&nbsp;&nbsp;
    <img src="{{ $img('images/partners/logan-county-chamber.png') }}" style="height:8mm;" />&nbsp;&nbsp;
    <img src="{{ $img('images/partners/logan-county-visitors-bureau.png') }}" style="height:12mm;" />
</div>

<pagebreak />

{{-- ============================ PAGE 2: MAP ============================ --}}
<div class="panel" style="left:0; top:0; width:279.4mm; height:22mm; background-color:{{ $deep }};">
    <table style="width:100%;">
        <tr>
            <td style="padding:2.5mm 0 0 10mm; width:100mm;">
                <div class="eyebrow">{!! $script('find your way around') !!}</div>
                <div class="h1" style="color:#ffffff;">Downtown Map</div>
            </td>
            <td style="padding:9mm 10mm 0 0; text-align:right;">
                <table style="margin-left:auto;"><tr>
                    @foreach (array_merge($directory['sections'], [\App\Services\VisitorsGuide::PARKING]) as $section)
                        <td style="background-color:{{ $section['color'] }}; width:3mm;">&nbsp;</td>
                        <td style="color:#ffffff; font-size:7pt; padding:0 3.5mm 0 1.2mm;">{{ $section['label'] }}</td>
                    @endforeach
                </tr></table>
            </td>
        </tr>
    </table>
</div>

@if ($mapPath)
    <div class="panel" style="left:10mm; top:26mm; width:259.4mm; height:176mm; text-align:center;">
        <img src="{{ $mapPath }}" style="height:174mm; border:0.6mm solid {{ $deep }};" />
    </div>
@else
    <div class="panel" style="left:10mm; top:26mm; width:259.4mm; height:176mm; background-color:{{ $cream }}; text-align:center;">
        <div style="padding-top:70mm;" class="black">See the live map at {{ $host }}/map</div>
    </div>
@endif

<div class="panel" style="left:10mm; top:204mm; width:259.4mm; height:9mm; font-size:7pt;">
    <table style="width:100%;"><tr>
        <td class="muted">Numbers match the directory on the next page. Pins with a <b>dark ring</b> are just off the edge of this map.</td>
        <td style="text-align:right;" class="muted">Live map with directions: <b style="color:{{ $teal }};">{{ $host }}/visitors-guide</b></td>
    </tr></table>
</div>

<pagebreak />

{{-- ============================ PAGE 3: DIRECTORY ============================ --}}
<div class="panel" style="left:0; top:0; width:279.4mm; height:22mm; background-color:{{ $deep }};">
    <div style="padding:2.5mm 0 0 10mm;">
        <div class="eyebrow">{!! $script("who's who downtown") !!}</div>
        <div class="h1" style="color:#ffffff;">The Directory</div>
    </div>
</div>

<div class="panel" style="left:9mm; top:26mm; width:261.4mm; height:184mm;">
    <table style="width:100%;">
        <tr>
            @foreach ($columns as $column)
                <td style="width:25%; padding:0 2.5mm;">
                    @foreach ($column as $block)
                        @if ($block['type'] === 'heading')
                            <div class="script" style="color:{{ $block['section']['color'] }}; font-size:30pt; margin-top:1.5mm; line-height:1;">{!! $script($block['section']['label']) !!}</div>
                        @else
                            @php $b = $block['b']; @endphp
                            <table style="width:100%; margin-bottom:1mm;"><tr>
                                <td style="width:6.5mm; background-color:{{ $b['color'] }}; color:#ffffff; font-weight:bold; font-size:6.8pt; text-align:center; vertical-align:middle;">{{ $b['number'] }}</td>
                                <td style="padding-left:1.8mm; color:{{ $deep }};">
                                    <div style="font-size:7.4pt; font-weight:bold;">{{ $b['name'] }}@if (isset($offmap[$b['number']])) <span style="color:{{ $orange }}; font-size:6pt;">&#8599; off map</span>@endif</div>
                                    <div class="muted" style="font-size:6.2pt;">{{ $b['address'] }}@if ($b['phone']) &middot; {{ $b['phone'] }}@endif</div>
                                </td>
                            </tr></table>
                        @endif
                    @endforeach
                </td>
            @endforeach
        </tr>
    </table>
</div>

<pagebreak />

{{-- ====== PAGE 4: EVENTS · SPEND A DAY · SCAVENGER HUNT (three panels like the trifold) ====== --}}

{{-- Panel 1: Signature events --}}
<div class="panel" style="left:0; top:0; width:93mm; height:200mm; background-color:{{ $cream }};">
    <div style="padding:7mm 7mm 0 7mm;">
        <div class="eyebrow">{!! $script('mark your calendar') !!}</div>
        <div class="h1" style="color:{{ $teal }}; font-size:19pt;">Signature Events</div>

        <table style="width:100%; margin-top:3mm;">
            @foreach ($calendar->chunk(2) as $pair)
                <tr>
                    @foreach ($pair as $month)
                        <td style="width:50%; padding:1.6mm 1.5mm; border-bottom:0.3mm dotted #c9b99a; color:{{ $deep }};">
                            <div style="color:{{ $orange }}; font-weight:bold; font-size:7.5pt;">{{ strtoupper($month['name']) }}</div>
                            @forelse ($month['events'] as $event)
                                <div class="black" style="font-size:7.8pt; color:{{ $deep }};">{{ $event->name }}</div>
                                @if ($event->timing_note)<div class="muted" style="font-size:6pt;">{{ $event->timing_note }}</div>@endif
                            @empty
                                <div class="muted" style="font-size:6.5pt;">Something's always brewing downtown</div>
                            @endforelse
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        @if ($upcoming->isNotEmpty())
            <div class="script" style="color:{{ $teal }}; font-size:30pt; margin-top:3mm; line-height:1;">coming up next</div>
            @foreach ($upcoming as $event)
                <div style="margin-top:1.2mm; font-size:7.2pt;">
                    <b style="color:{{ $orange }};">{{ $event->event_date->format('M j') }}</b>
                    &nbsp;{{ $event->title }}
                </div>
            @endforeach
        @endif

        <table style="width:100%; margin-top:4mm;"><tr>
            <td style="width:20mm;"><barcode code="{{ $site }}/events" type="QR" size="0.6" error="M" disableborder="1" /></td>
            <td style="vertical-align:middle; font-size:7pt; padding-left:2mm; color:{{ $deep }};">Every date, time, and new event lives at <b>{{ $host }}/events</b></td>
        </tr></table>
    </div>
</div>

{{-- Panel 2: Spend a Day timeline --}}
<div class="panel" style="left:93mm; top:0; width:93.4mm; height:200mm; background-color:#ffffff;">
    <div style="padding:7mm 7mm 0 7mm;">
        <div class="script" style="color:{{ $lime }}; font-size:40pt; line-height:0.9;">spend a day exploring</div>
        <div class="h1" style="color:{{ $teal }}; font-size:19pt;">Bellefontaine!</div>

        <table style="width:100%; margin-top:4mm;">
            @foreach ($timeline as $stop)
                <tr>
                    <td style="width:19mm; padding:2.2mm 0; border-right:0.8mm solid {{ $orange }}; color:{{ $deep }};">
                        <div style="font-weight:bold; font-size:10pt;">{{ $stop['time'] }}</div>
                        <div style="margin-top:1mm;"><barcode code="{{ $stop['url'] }}" type="QR" size="0.45" error="L" disableborder="1" /></div>
                    </td>
                    <td style="padding:2.2mm 0 2.2mm 4mm; color:{{ $deep }};">
                        <div class="black" style="font-size:8.5pt; color:{{ $orange }};">{{ $stop['title'] }}</div>
                        <div style="font-size:7pt; margin-top:0.6mm;">{{ $stop['teaser'] }}</div>
                        <div class="muted" style="font-size:6pt; margin-top:0.6mm;">Scan for the full itinerary</div>
                    </td>
                </tr>
            @endforeach
        </table>

        <div style="margin-top:4mm; font-size:7.2pt;">
            <b style="color:{{ $teal }};">More days out:</b> {{ $host }}/day-agendas<br>
            <b style="color:{{ $teal }};">History buff?</b> Take the Historic Walking Tour: {{ $host }}/historic-walking-tour
        </div>
    </div>
</div>

{{-- Panel 3: Scavenger hunt --}}
<div class="panel" style="left:186.4mm; top:0; width:93mm; height:200mm; background-color:{{ $deep }};">
    <div style="padding:7mm 7mm 0 7mm;">
        <div class="script" style="color:{{ $lime }}; font-size:40pt; line-height:0.9;">downtown bellefontaine</div>
        <div class="h1" style="color:#ffffff; font-size:19pt;">Scavenger Hunt!</div>

        <table style="width:100%; margin-top:3mm;">
            @foreach ($clues as $i => $clue)
                <tr>
                    <td style="background-color:{{ $clueColors[$i % count($clueColors)] }}; width:11mm; text-align:center; vertical-align:middle; font-family:montserratblack; font-size:12pt; color:#ffffff; border-bottom:1.5mm solid {{ $deep }};">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                    <td style="background-color:#ffffff; color:{{ $deep }}; padding:2mm 2.5mm; font-size:7.2pt; font-weight:bold; text-transform:uppercase; border-bottom:1.5mm solid {{ $deep }};">{{ $clue->clue }}</td>
                </tr>
            @endforeach
        </table>

        <table style="width:100%; margin-top:2mm;"><tr>
            <td style="vertical-align:middle; color:#ffffff;">
                <div class="script" style="color:{{ $lime }}; font-size:34pt; line-height:1;">{!! $script('want more?') !!}</div>
                <div style="font-size:7.5pt; color:#ffffff;">Play along on your phone and check your answers online.</div>
            </td>
            <td style="width:24mm; text-align:right;"><barcode code="{{ $site }}/visitors-guide#scavenger-hunt" type="QR" size="0.7" error="M" disableborder="1" /></td>
        </tr></table>
    </div>
</div>

{{-- Bottom ribbon across the three panels --}}
<div class="panel" style="left:0; top:200mm; width:279.4mm; height:15.9mm; background-color:{{ $orange }};">
    <table style="width:100%;"><tr>
        <td style="padding:1.5mm 0 0 7mm; width:110mm; color:#ffffff;">
            <div class="black" style="font-size:9pt; color:#ffffff;">Don't miss a thing</div>
            <div class="script" style="font-size:28pt; color:#ffffff; line-height:0.9;">{!! $script('follow us on social media!') !!}</div>
        </td>
        <td style="padding:5mm 7mm 0 0; text-align:right; font-size:7.5pt; font-weight:bold; color:#ffffff;">
            @foreach (['Facebook' => $settings?->facebook_url, 'Instagram' => $settings?->instagram_url, 'TikTok' => $settings?->tiktok_url] as $network => $url)
                @if ($url) {{ $network }}: {{ '@'.ltrim(trim((string) parse_url($url, PHP_URL_PATH), '/'), '@') }}&nbsp;&nbsp; @endif
            @endforeach
            <br>{{ strtoupper($host) }}
        </td>
    </tr></table>
</div>

</body>
</html>
