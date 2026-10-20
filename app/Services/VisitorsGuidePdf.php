<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Throwable;

/**
 * Renders the printable Visitors Guide from live content. The file is cached
 * per content version, so it is rebuilt only after something changes and
 * every download reflects the current site.
 */
class VisitorsGuidePdf
{
    public function __construct(
        private VisitorsGuide $guide,
        private VisitorsGuideMap $map,
    ) {}

    /** Absolute path to an up-to-date PDF, building it if needed. */
    public function path(): string
    {
        $disk = Storage::disk('public');
        $file = 'visitors-guide/guide-'.$this->guide->version().'.pdf';

        if ($disk->exists($file)) {
            return $disk->path($file);
        }

        // One build at a time; a second request waits for the first to finish.
        Cache::lock('visitors-guide-pdf', 120)->block(90, function () use ($disk, $file) {
            if (! $disk->exists($file)) {
                $disk->put($file, $this->build());
                $this->prune($file);
            }
        });

        return $disk->path($file);
    }

    public function build(): string
    {
        @set_time_limit(120);

        try {
            $map = $this->map->render();
        } catch (Throwable $e) {
            report($e);
            $map = ['path' => null, 'offmap' => []];
        }

        $directory = $this->guide->directory();

        $html = view('visitors-guide.pdf', [
            'directory' => $directory,
            'columns' => $this->columns($directory, 4),
            'cover' => $this->coverImage(),
            'calendar' => $this->guide->signatureEventsByMonth(),
            'upcoming' => $this->guide->upcomingEvents(5),
            'timeline' => $this->guide->timeline(),
            'clues' => $this->guide->scavengerClues(),
            'driveTimes' => VisitorsGuide::DRIVE_TIMES,
            'updated' => $this->guide->lastUpdated(),
            'mapPath' => $map['path'],
            'offmap' => array_flip($map['offmap']),
            'settings' => SiteSetting::current(),
        ])->render();

        $mpdf = $this->mpdf();
        $mpdf->SetTitle('Downtown Bellefontaine Visitors Guide');
        $mpdf->SetAuthor('Downtown Bellefontaine Partnership');
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }

    /**
     * Split the directory into balanced print columns. A section heading is
     * never left alone at the bottom of a column.
     *
     * @return array<int, array<int, array>>  columns of ['type' => heading|entry, ...]
     */
    private function columns(array $directory, int $count): array
    {
        $blocks = [];
        foreach ($directory['sections'] as $section) {
            $blocks[] = ['type' => 'heading', 'section' => $section, 'weight' => 2.4];
            foreach ($section['businesses'] as $b) {
                $blocks[] = ['type' => 'entry', 'b' => $b, 'weight' => 1.0];
            }
        }
        if ($directory['parking']) {
            $blocks[] = ['type' => 'heading', 'section' => VisitorsGuide::PARKING + ['slug' => 'parking'], 'weight' => 2.4];
            foreach ($directory['parking'] as $b) {
                $blocks[] = ['type' => 'entry', 'b' => $b, 'weight' => 1.0];
            }
        }

        $target = array_sum(array_column($blocks, 'weight')) / $count;
        $columns = [[]];
        $height = 0;

        foreach ($blocks as $i => $block) {
            $isLastColumn = count($columns) === $count;
            $overflow = $height + $block['weight'] > $target * 1.04;
            // Move a heading forward with its first entry rather than orphan it.
            $orphan = $block['type'] === 'heading' && $height + $block['weight'] + 1 > $target * 1.04;

            if (! $isLastColumn && $height > 0 && ($overflow || $orphan)) {
                $columns[] = [];
                $height = 0;
            }
            $columns[count($columns) - 1][] = $block;
            $height += $block['weight'];
        }

        return $columns;
    }

    /** Teal-toned crop of the courthouse photo for the cover panel. */
    private function coverImage(): string
    {
        $src = public_path('images/home/welcome-courthouse.jpg');
        $disk = Storage::disk('public');
        $file = 'visitors-guide/cover-'.substr(sha1($src.@filemtime($src)), 0, 10).'.jpg';

        if (! $disk->exists($file)) {
            $image = \Intervention\Image\ImageManager::gd()->read($src)
                ->cover(1000, 1290, 'center')
                ->greyscale()
                ->colorize(-38, 2, 6)
                ->brightness(-12)
                ->contrast(8);
            $disk->put($file, (string) $image->toJpeg(82));
        }

        return $disk->path($file);
    }

    private function mpdf(): Mpdf
    {
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $tmp = storage_path('framework/cache/mpdf');
        if (! is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
            'tempDir' => $tmp,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
                resource_path('fonts/Montserrat/static'),
            ]),
            'fontdata' => $fontData + [
                'montserrat' => ['R' => 'Montserrat-Regular.ttf', 'B' => 'Montserrat-Bold.ttf'],
                'montserratsemibold' => ['R' => 'Montserrat-SemiBold.ttf'],
                'montserratblack' => ['R' => 'Montserrat-Black.ttf'],
                'fridayvibes' => ['R' => 'Friday Vibes.ttf'],
            ],
            'default_font' => 'montserrat',
            'img_dpi' => 150,
        ]);
    }

    private function prune(string $keep): void
    {
        $disk = Storage::disk('public');
        foreach ($disk->files('visitors-guide') as $path) {
            if (str_starts_with(basename($path), 'guide-') && $path !== $keep) {
                $disk->delete($path);
            }
        }
    }
}
