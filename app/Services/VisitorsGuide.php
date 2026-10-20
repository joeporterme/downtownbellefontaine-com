<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessLocation;
use App\Models\DayAgenda;
use App\Models\Event;
use App\Models\ScavengerClue;
use App\Models\SignatureEvent;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Single source of truth for the Visitors Guide. The web page, the printable
 * map, and the PDF all read from here, so pin numbers and content always match.
 */
class VisitorsGuide
{
    /**
     * Guide sections in print order: category slug => display settings.
     * Colors follow the 2023 printed guide. Categories not listed here
     * (Professional Services, City & Government) are left out on purpose.
     */
    public const SECTIONS = [
        'shopping' => ['label' => 'Shopping', 'icon' => 'fa-bag-shopping', 'color' => '#e2504c'],
        'food-drink' => ['label' => 'Food & Drink', 'icon' => 'fa-utensils', 'color' => '#f3773d'],
        'things-to-do' => ['label' => 'Things to Do', 'icon' => 'fa-ticket', 'color' => '#d99a0b'],
        'performance-events' => ['label' => 'Entertainment', 'icon' => 'fa-masks-theater', 'color' => '#6f9a1f'],
        'beauty-salon-services' => ['label' => 'Spas & Salons', 'icon' => 'fa-spa', 'color' => '#8e4fbf'],
        'health-fitness' => ['label' => 'Health & Fitness', 'icon' => 'fa-dumbbell', 'color' => '#01757f'],
        'hotel-lodging' => ['label' => 'Places to Stay', 'icon' => 'fa-bed', 'color' => '#1f4e79'],
    ];

    public const PARKING = ['label' => 'Parking', 'icon' => 'fa-square-parking', 'color' => '#0ea5e9'];

    /** The old guide's "Spend a Day" timeline: time slot => day agenda slug. */
    public const TIMELINE = [
        '8:00 AM' => 'a-kids-day-out',
        '10:00 AM' => 'an-antiquing-adventure',
        '12:00 PM' => 'its-time-to-bro-out',
        '3:00 PM' => 'lets-go-girls-pamper-day-fun',
        '6:00 PM' => 'attention-all-foodies-its-time-to-eat',
    ];

    public const DRIVE_TIMES = [
        ['place' => 'Indian Lake', 'time' => '15 min'],
        ['place' => 'West Liberty', 'time' => '15 min'],
        ['place' => 'Mad River Mountain', 'time' => '15 min'],
        ['place' => 'Marysville', 'time' => '35 min'],
        ['place' => 'Springfield', 'time' => '40 min'],
        ['place' => 'Lima', 'time' => '45 min'],
        ['place' => 'Columbus', 'time' => '1 hr'],
        ['place' => 'Dayton', 'time' => '1 hr'],
    ];

    private ?array $directory = null;

    /**
     * Numbered directory: sections with their businesses, plus parking.
     *
     * @return array{sections: array<int, array>, parking: array<int, array>, pins: array<int, array>}
     */
    public function directory(): array
    {
        if ($this->directory !== null) {
            return $this->directory;
        }

        $businesses = Business::approved()
            ->with(['categories', 'locations'])
            ->orderBy('name')
            ->get();

        $buckets = array_fill_keys(array_keys(self::SECTIONS), []);
        $parking = [];

        foreach ($businesses as $business) {
            $location = $business->locations->firstWhere('is_primary', true) ?? $business->locations->first();
            if (! $location || ! $location->latitude || ! $location->longitude) {
                continue;
            }

            $slugs = $business->categories->pluck('slug');

            // A business lands in the first guide section it belongs to.
            $section = collect(array_keys(self::SECTIONS))->first(fn ($s) => $slugs->contains($s));

            if ($section) {
                $buckets[$section][] = $this->entry($business, $location, $section);
            } elseif ($slugs->contains('parking')) {
                $parking[] = $this->entry($business, $location, 'parking');
            }
        }

        // Number pins sequentially in print order so the map, list, and PDF agree.
        $n = 0;
        $sections = [];
        foreach (self::SECTIONS as $slug => $meta) {
            if (empty($buckets[$slug])) {
                continue;
            }
            $items = array_map(function ($e) use (&$n) {
                $e['number'] = (string) ++$n;
                return $e;
            }, $buckets[$slug]);
            $sections[] = $meta + ['slug' => $slug, 'businesses' => $items];
        }

        $parking = array_values(array_map(function ($e, $i) {
            $e['number'] = 'P'.($i + 1);
            return $e;
        }, $parking, array_keys($parking)));

        $pins = array_merge(...array_column($sections, 'businesses'), ...[$parking]);

        return $this->directory = compact('sections', 'parking', 'pins');
    }

    private function entry(Business $business, BusinessLocation $location, string $section): array
    {
        $meta = self::SECTIONS[$section] ?? self::PARKING;

        return [
            'id' => $business->id,
            'name' => $business->name,
            'section' => $section,
            'color' => $meta['color'],
            'url' => route('businesses.show', $business),
            'address' => $location->address,
            'phone' => self::formatPhone($business->phone ?: $location->phone),
            'website' => $business->website,
            'lat' => (float) $location->latitude,
            'lng' => (float) $location->longitude,
            'directions' => 'https://www.google.com/maps/dir/?api=1&destination='
                .rawurlencode(trim("{$business->name}, {$location->address}, {$location->city}, {$location->state}")),
        ];
    }

    /** "9375925333" or "+1 937-592-5333" -> "(937) 592-5333"; anything else unchanged. */
    public static function formatPhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);
        if (strlen($digits) === 11 && $digits[0] === '1') {
            $digits = substr($digits, 1);
        }

        return strlen($digits) === 10
            ? sprintf('(%s) %s-%s', substr($digits, 0, 3), substr($digits, 3, 3), substr($digits, 6))
            : ($phone ?: null);
    }

    /** @return Collection<int, SignatureEvent> keyed by month 1-12 (each a collection) */
    public function signatureEventsByMonth(): Collection
    {
        $events = SignatureEvent::active()->ordered()->get()->groupBy('month');

        return collect(SignatureEvent::MONTHS)->map(fn ($name, $m) => [
            'month' => $m,
            'name' => $name,
            'short' => substr($name, 0, 3),
            'events' => $events->get($m, collect()),
        ]);
    }

    public function upcomingEvents(int $limit = 6): Collection
    {
        return Event::approved()->upcoming()
            ->orderBy('event_date')->orderBy('start_time')
            ->take($limit)
            ->get();
    }

    /** The "Spend a Day" timeline, falling back to sort order for any missing agenda. */
    public function timeline(): Collection
    {
        $agendas = DayAgenda::published()->orderBy('sort')->get();
        $used = [];

        return collect(self::TIMELINE)->map(function ($slug, $time) use ($agendas, &$used) {
            $agenda = $agendas->firstWhere('slug', $slug)
                ?? $agendas->first(fn ($a) => ! in_array($a->id, $used) && ! in_array($a->slug, self::TIMELINE));
            if (! $agenda) {
                return null;
            }
            $used[] = $agenda->id;

            return [
                'time' => $time,
                'title' => $agenda->title,
                'teaser' => self::teaser($agenda->excerpt),
                'url' => route('day-agendas.show', $agenda),
                'image' => $agenda->featured_image ? \App\Support\Media::url($agenda->featured_image) : null,
            ];
        })->filter()->values();
    }

    /** Plain-text teaser. WordPress cut excerpts mid-sentence and marked it "[…]". */
    private static function teaser(?string $excerpt): string
    {
        $text = trim(html_entity_decode(strip_tags((string) $excerpt), ENT_QUOTES | ENT_HTML5));
        $cut = (bool) preg_match('/\[(…|\.\.\.)\]$/u', $text);
        $text = rtrim(preg_replace('/\s*\[(…|\.\.\.)\]$/u', '', $text), " ,;:");

        return Str::limit($text, 140, '…', preserveWords: true).($cut && mb_strlen($text) <= 140 ? '…' : '');
    }

    public function scavengerClues(): Collection
    {
        return ScavengerClue::active()->ordered()->with('business')->get();
    }

    /**
     * The newest content timestamp across everything the guide shows. Drives
     * the "Updated" label and, with version(), the PDF cache.
     */
    public function lastUpdated(): CarbonImmutable
    {
        $stamps = array_filter([
            Business::max('updated_at'),
            BusinessLocation::max('updated_at'),
            BusinessCategory::max('updated_at'),
            SignatureEvent::max('updated_at'),
            ScavengerClue::max('updated_at'),
            Event::max('updated_at'),
            DayAgenda::max('updated_at'),
        ]);

        return $stamps ? CarbonImmutable::parse(max($stamps)) : CarbonImmutable::now();
    }

    /**
     * A short fingerprint of everything the PDF prints. Any admin edit changes
     * it, so the next download builds a fresh PDF. The date is included
     * because "coming up next" shifts as events pass, and the template's
     * mtime because a design change should rebuild too.
     */
    public function version(): string
    {
        return substr(sha1(implode('|', [
            $this->lastUpdated()->toIso8601String(),
            Business::approved()->count(),
            DB::table('business_business_category')->count(),
            SignatureEvent::count(),
            ScavengerClue::count(),
            DayAgenda::published()->count(),
            Event::approved()->upcoming()->count(),
            now()->toDateString(),
            @filemtime(resource_path('views/visitors-guide/pdf.blade.php')),
            @filemtime(__FILE__),
            @filemtime(app_path('Services/VisitorsGuideMap.php')),
            @filemtime(app_path('Services/VisitorsGuidePdf.php')),
        ])), 0, 12);
    }
}
