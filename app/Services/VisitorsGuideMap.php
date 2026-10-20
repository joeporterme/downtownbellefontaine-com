<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Geometry\Factories\CircleFactory;
use Intervention\Image\Geometry\Factories\LineFactory;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;
use RuntimeException;

/**
 * Builds the printable guide map: a Google Static Maps base (no POIs, warm
 * small-town styling) with the guide's numbered pins drawn on top, so the
 * printed numbers match the directory exactly.
 */
class VisitorsGuideMap
{
    // Static Maps logical size; scale=2 doubles the actual pixels.
    private const W = 640;
    private const H = 440;
    private const SCALE = 2;

    // Downtown core: Main St & Columbus Ave. Pins farther than this radius
    // don't widen the frame; they get clamped to the edge instead.
    private const CORE_LAT = 40.3614;
    private const CORE_LNG = -83.7596;
    private const CORE_RADIUS_M = 420;

    private const PIN_R = 17;

    public function __construct(private VisitorsGuide $guide) {}

    /**
     * Absolute filesystem path of the finished map, plus the pin numbers that
     * fell outside the frame (marked "off map" in the printed directory).
     *
     * @return array{path: string, offmap: array<int, string>}
     */
    public function render(): array
    {
        $disk = Storage::disk('public');
        $version = $this->guide->version();
        $file = "visitors-guide/map-{$version}.png";
        $meta = "visitors-guide/map-{$version}.json";

        if ($disk->exists($file) && $disk->exists($meta)) {
            return ['path' => $disk->path($file), 'offmap' => json_decode($disk->get($meta), true) ?: []];
        }

        $pins = $this->guide->directory()['pins'];
        [$centerLat, $centerLng, $zoom] = $this->frame($pins);

        $image = ImageManager::gd()->read($this->baseMap($centerLat, $centerLng, $zoom));
        $offmap = $this->drawPins($image, $pins, $centerLat, $centerLng, $zoom);

        $disk->put($file, (string) $image->toPng());
        $disk->put($meta, json_encode($offmap));
        $this->prune('map-', $version);

        return ['path' => $disk->path($file), 'offmap' => $offmap];
    }

    /** Pick a center and the tightest zoom that holds every core pin. */
    private function frame(array $pins): array
    {
        $core = array_filter($pins, fn ($p) => $this->meters(self::CORE_LAT, self::CORE_LNG, $p['lat'], $p['lng']) <= self::CORE_RADIUS_M);
        if (! $core) {
            return [self::CORE_LAT, self::CORE_LNG, 16];
        }

        $lats = array_column($core, 'lat');
        $lngs = array_column($core, 'lng');
        $lat = (min($lats) + max($lats)) / 2;
        $lng = (min($lngs) + max($lngs)) / 2;

        $margin = 28; // logical px kept clear around the outermost pins
        for ($z = 18; $z > 14; $z--) {
            [$x1, $y1] = $this->world(max($lats), min($lngs), $z);
            [$x2, $y2] = $this->world(min($lats), max($lngs), $z);
            if (($x2 - $x1) <= self::W - 2 * $margin && ($y2 - $y1) <= self::H - 2 * $margin) {
                return [$lat, $lng, $z];
            }
        }

        return [$lat, $lng, 15];
    }

    /** Cached Static Maps base image (raw PNG bytes). */
    private function baseMap(float $lat, float $lng, int $zoom): string
    {
        $params = [
            'center' => sprintf('%.6f,%.6f', $lat, $lng),
            'zoom' => $zoom,
            'size' => self::W.'x'.self::H,
            'scale' => self::SCALE,
            'maptype' => 'roadmap',
            'key' => config('services.google.maps_server_key'),
        ];
        $styles = [
            'feature:poi|visibility:off',
            'feature:transit|visibility:off',
            'feature:administrative|element:labels|visibility:off',
            'feature:landscape|color:0xf6f1e7',
            'feature:road|element:geometry|color:0xffffff',
            'feature:road.arterial|element:geometry|color:0xfde3d3',
            'feature:road|element:labels.text.fill|color:0x37606a',
            'feature:water|color:0xbfe3e6',
        ];

        $url = 'https://maps.googleapis.com/maps/api/staticmap?'.http_build_query($params)
            .'&'.implode('&', array_map(fn ($s) => 'style='.rawurlencode($s), $styles));

        $disk = Storage::disk('public');
        $file = 'visitors-guide/basemap-'.substr(sha1($url), 0, 12).'.png';
        if ($disk->exists($file)) {
            return $disk->get($file);
        }

        $response = Http::timeout(20)->get($url);
        if (! $response->successful() || ! str_starts_with((string) $response->header('Content-Type'), 'image/')) {
            throw new RuntimeException('Static Maps request failed: HTTP '.$response->status());
        }

        $disk->put($file, $response->body());
        $this->prune('basemap-', basename($file, '.png'), keepPrefix: true);

        return $response->body();
    }

    /** Draw numbered badges, nudging overlaps apart with a leader line. */
    private function drawPins($image, array $pins, float $lat, float $lng, int $zoom): array
    {
        [$cx, $cy] = $this->world($lat, $lng, $zoom);
        $w = self::W * self::SCALE;
        $h = self::H * self::SCALE;
        $r = self::PIN_R;
        $inset = $r + 6;
        $font = resource_path('fonts/Montserrat/static/Montserrat-Bold.ttf');

        $placed = [];
        $offmap = [];
        $layout = [];

        // Pass 1: decide every badge position before drawing anything.
        foreach ($pins as $pin) {
            [$px, $py] = $this->world($pin['lat'], $pin['lng'], $zoom);
            $x = ($px - $cx) * self::SCALE + $w / 2;
            $y = ($py - $cy) * self::SCALE + $h / 2;

            $outside = $x < $inset || $x > $w - $inset || $y < $inset || $y > $h - $inset;
            if ($outside) {
                $offmap[] = $pin['number'];
                $x = max($inset, min($w - $inset, $x));
                $y = max($inset, min($h - $inset, $y));
            }

            [$bx, $by] = $this->freeSpot($x, $y, $placed, $w, $h, $inset);
            $placed[] = [$bx, $by];
            $layout[] = compact('pin', 'x', 'y', 'bx', 'by', 'outside');
        }

        // Pass 2: leader lines and true-location dots for nudged badges.
        foreach ($layout as ['pin' => $pin, 'x' => $x, 'y' => $y, 'bx' => $bx, 'by' => $by, 'outside' => $outside]) {
            if ($outside || (abs($bx - $x) <= 1 && abs($by - $y) <= 1)) {
                continue;
            }
            $image->drawLine(function (LineFactory $l) use ($x, $y, $bx, $by, $pin) {
                $l->from((int) $x, (int) $y);
                $l->to((int) $bx, (int) $by);
                $l->color($pin['color']);
                $l->width(2);
            });
            $image->drawCircle((int) $x, (int) $y, function (CircleFactory $c) use ($pin) {
                $c->radius(4);
                $c->background($pin['color']);
                $c->border('#ffffff', 1);
            });
        }

        // Pass 3: badges on top of everything.
        foreach ($layout as ['pin' => $pin, 'bx' => $bx, 'by' => $by, 'outside' => $outside]) {
            $image->drawCircle((int) $bx, (int) $by, function (CircleFactory $c) use ($pin, $r, $outside) {
                $c->radius($r);
                $c->background($pin['color']);
                $c->border($outside ? '#1f2d33' : '#ffffff', 3);
            });

            $label = $pin['number'];
            $image->text($label, (int) $bx, (int) $by + 1, function (FontFactory $f) use ($font, $label) {
                $f->filename($font);
                $f->size(strlen($label) >= 3 ? 13 : 16);
                $f->color('#ffffff');
                $f->align('center');
                $f->valign('middle');
            });
        }

        return $offmap;
    }

    /** First spot near ($x,$y) that doesn't overlap an already-placed badge. */
    private function freeSpot(float $x, float $y, array $placed, int $w, int $h, int $inset): array
    {
        $min = 2 * self::PIN_R + 2;
        $fits = function ($tx, $ty) use ($placed, $min, $w, $h, $inset) {
            if ($tx < $inset || $tx > $w - $inset || $ty < $inset || $ty > $h - $inset) {
                return false;
            }
            foreach ($placed as [$qx, $qy]) {
                if (hypot($tx - $qx, $ty - $qy) < $min) {
                    return false;
                }
            }
            return true;
        };

        if ($fits($x, $y)) {
            return [$x, $y];
        }
        for ($ring = 1; $ring <= 14; $ring++) {
            $dist = $ring * $min * 0.7;
            for ($deg = 0; $deg < 360; $deg += 20) {
                $tx = $x + $dist * cos(deg2rad($deg));
                $ty = $y + $dist * sin(deg2rad($deg));
                if ($fits($tx, $ty)) {
                    return [$tx, $ty];
                }
            }
        }

        return [$x, $y];
    }

    /** Web Mercator world pixel coordinates at a zoom level (256px tiles). */
    private function world(float $lat, float $lng, int $zoom): array
    {
        $size = 256 * (2 ** $zoom);
        $sin = sin(deg2rad($lat));

        return [
            ($lng + 180) / 360 * $size,
            (0.5 - log((1 + $sin) / (1 - $sin)) / (4 * M_PI)) * $size,
        ];
    }

    private function meters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 6371000 * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /** Delete older generated files with the same prefix. */
    private function prune(string $prefix, string $keep, bool $keepPrefix = false): void
    {
        $disk = Storage::disk('public');
        foreach ($disk->files('visitors-guide') as $path) {
            $name = basename($path);
            $current = $keepPrefix ? str_starts_with($name, $keep) : str_contains($name, $keep);
            if (str_starts_with($name, $prefix) && ! $current) {
                $disk->delete($path);
            }
        }
    }
}
