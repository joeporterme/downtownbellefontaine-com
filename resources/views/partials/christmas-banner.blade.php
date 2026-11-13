@php
    // Shows Nov 1 through Christmas Day (Ohio time), then hides itself until next season.
    $xmasNow = now('America/New_York');
    $xmasYear = $xmasNow->year;
    $showXmas = $xmasNow->month === 11 || ($xmasNow->month === 12 && $xmasNow->day <= 25);
@endphp

@if($showXmas)
<div id="xmas-banner" class="relative overflow-hidden text-white bg-[linear-gradient(100deg,#0b4d33_0%,#11663f_45%,#9e1b2c_100%)]"
     role="region" aria-label="Ohio's Christmas Capital">
    {{-- Soft snow dots --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-25 bg-[radial-gradient(circle,#fff_1px,transparent_1.5px)] bg-[length:22px_22px]"></div>

    <div class="relative max-w-7xl mx-auto pl-4 pr-11 sm:pl-6 sm:pr-12 lg:pl-8 py-2 flex items-center gap-3 sm:gap-4">
        <a href="https://ohiochristmascapital.com/" target="_blank" rel="noopener" class="shrink-0" tabindex="-1" aria-hidden="true">
            <img src="{{ asset('images/ohio-christmas-capital.png') }}" alt="" width="44" height="44" class="h-10 w-10 sm:h-11 sm:w-11 drop-shadow">
        </a>

        <p class="min-w-0 flex-1 text-sm leading-snug">
            <a href="https://ohiochristmascapital.com/" target="_blank" rel="noopener" class="sm:hidden font-bold">Ohio's Christmas Capital</a>
            <span class="hidden sm:inline font-bold">Bellefontaine is Ohio's Christmas Capital.</span>
            <span class="hidden md:inline text-white/85">Santa's shed, the Hometown Parade, holiday shopping and lights all season.</span>
        </p>

        {{-- Countdown (filled in by the script below) --}}
        <div class="hidden sm:flex items-center gap-1.5 shrink-0" data-xmas-countdown data-target="{{ $xmasYear }}-12-25T00:00:00-05:00">
            @foreach(['d' => 'days', 'h' => 'hrs', 'm' => 'min', 's' => 'sec'] as $unit => $label)
                <span class="flex flex-col items-center justify-center w-11 rounded-md bg-white/15 ring-1 ring-white/20 py-0.5 {{ $unit === 's' ? 'hidden lg:flex' : '' }}">
                    <span class="text-base font-extrabold tabular-nums leading-none" data-unit="{{ $unit }}">--</span>
                    <span class="text-[9px] uppercase tracking-wider text-white/75">{{ $label }}</span>
                </span>
            @endforeach
            <span class="hidden xl:block text-[11px] uppercase tracking-wider font-semibold text-white/80 ml-1 leading-tight">til<br>Christmas</span>
        </div>
        {{-- Phones: compact day count --}}
        <span class="sm:hidden shrink-0 text-xs font-bold bg-white/15 ring-1 ring-white/20 rounded-full px-2.5 py-1" data-xmas-days></span>

        <a href="https://ohiochristmascapital.com/" target="_blank" rel="noopener"
           class="shrink-0 hidden sm:inline-flex items-center gap-1.5 rounded-full bg-[#f2c14e] text-[#4a1d0f] font-bold text-sm px-4 py-1.5 hover:bg-white transition-colors">
            Plan your holiday visit <i class="fa-duotone fa-light fa-arrow-up-right-from-square text-[11px]"></i>
        </a>
        <a href="https://ohiochristmascapital.com/" target="_blank" rel="noopener" class="sm:hidden shrink-0 text-white" aria-label="Visit ohiochristmascapital.com">
            <i class="fa-duotone fa-light fa-arrow-right"></i>
        </a>

        <button type="button" data-xmas-close aria-label="Dismiss Christmas banner"
                class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full text-white/80 hover:text-white hover:bg-white/15 flex items-center justify-center">
            <i class="fa-duotone fa-light fa-xmark"></i>
        </button>
    </div>
</div>
<script>
    (function () {
        var el = document.getElementById('xmas-banner');
        var key = 'xmas-banner-dismissed-{{ $xmasYear }}';
        try { if (localStorage.getItem(key)) { el.remove(); return; } } catch (e) {}

        el.querySelector('[data-xmas-close]').addEventListener('click', function () {
            try { localStorage.setItem(key, '1'); } catch (e) {}
            el.remove();
        });

        var box = el.querySelector('[data-xmas-countdown]');
        var target = new Date(box.dataset.target).getTime();
        var days = el.querySelector('[data-xmas-days]');
        var pad = function (n) { return n < 10 ? '0' + n : '' + n; };

        function tick() {
            var diff = Math.max(0, target - Date.now());
            var d = Math.floor(diff / 864e5), h = Math.floor(diff / 36e5) % 24,
                m = Math.floor(diff / 6e4) % 60, s = Math.floor(diff / 1e3) % 60;
            if (diff === 0) {
                box.innerHTML = '<span class="font-display text-2xl leading-none">Merry Christmas</span>';
                days.textContent = 'Merry Christmas';
                return clearInterval(timer);
            }
            box.querySelector('[data-unit=d]').textContent = d;
            box.querySelector('[data-unit=h]').textContent = pad(h);
            box.querySelector('[data-unit=m]').textContent = pad(m);
            box.querySelector('[data-unit=s]').textContent = pad(s);
            days.textContent = d === 0 ? 'Tomorrow' : d + (d === 1 ? ' day' : ' days');
        }
        var timer = setInterval(tick, 1000);
        tick();
    })();
</script>
@endif
