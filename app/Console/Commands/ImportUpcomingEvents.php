<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportUpcomingEvents extends Command
{
    protected $signature = 'events:import-upcoming
                            {--file=database/data/wp-upcoming-events.json : JSON export of upcoming WordPress events}
                            {--tz=UTC : How to read the WP timestamps. The events plugin stores the local wall-clock time AS a UTC timestamp, so UTC yields the correct local time.}
                            {--no-images : Skip downloading featured images}';

    protected $description = 'Import upcoming events from the live-WordPress JSON export into the events table (approved). Idempotent by slug.';

    public function handle(): int
    {
        $path = base_path($this->option('file'));

        if (! File::exists($path)) {
            $this->error("Export file not found: {$path}");

            return self::FAILURE;
        }

        $tz = $this->option('tz');
        $events = json_decode(File::get($path), true) ?: [];
        $imported = 0;

        foreach ($events as $e) {
            $slug = $e['slug'] ?: Str::slug($e['title']);
            $start = Carbon::createFromTimestamp((int) $e['start_ts'], $tz);
            $allDay = ($e['all_day'] ?? 'no') === 'yes';

            $endTime = null;
            if (! $allDay && ! empty($e['end_ts']) && ($e['hide_end'] ?? 'no') !== 'yes'
                && (int) $e['end_ts'] > (int) $e['start_ts']) {
                $endTime = Carbon::createFromTimestamp((int) $e['end_ts'], $tz)->format('H:i:s');
            }

            $attrs = [
                'title' => $e['title'],
                'description' => $this->cleanDescription($e['content'] ?? '') ?: ($e['summary'] ?: null),
                'event_date' => $start->toDateString(),
                'start_time' => $allDay ? null : $start->format('H:i:s'),
                'end_time' => $endTime,
                'more_info_url' => $e['link'] ?: null,
                'location_name' => $e['location'] ?: null,
                'address' => $e['map'] ?: null,
                'city' => 'Bellefontaine',
                'state' => 'OH',
                'status' => 'approved',
                'approved_at' => now(),
            ];

            if (! $this->option('no-images') && ! empty($e['thumb'])) {
                if ($stored = $this->downloadImage($e['thumb'], $slug)) {
                    $attrs['featured_image'] = $stored;
                }
            }

            Event::updateOrCreate(['slug' => $slug], $attrs);
            $imported++;
            $this->line("  ✓ {$start->toDateString()}  {$e['title']}");
        }

        $this->info("Imported/updated {$imported} event(s).");

        return self::SUCCESS;
    }

    /** Strip WordPress block comments, emoji/junk images, and normalize whitespace. */
    protected function cleanDescription(string $html): string
    {
        $html = preg_replace('/<!--.*?-->/s', '', $html);       // WP block comments
        $html = preg_replace('/<img\b[^>]*>/i', '', $html);     // emoji / inline images
        $html = preg_replace('/\s*style="[^"]*"/i', '', $html); // inline styles
        $html = str_replace('&nbsp;', ' ', $html);              // nbsp → space
        $html = preg_replace('/(\R\s*){3,}/', "\n\n", $html);   // collapse blank runs

        return trim($html);
    }

    /** Download a remote image to the public storage disk; returns the stored path or null. */
    protected function downloadImage(string $url, string $slug): ?string
    {
        try {
            $res = Http::timeout(30)->withHeaders(['Referer' => config('app.url')])->get($url);
            if (! $res->successful()) {
                return null;
            }
            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
            $dest = "events/{$slug}.{$ext}";
            Storage::disk('public')->put($dest, $res->body());

            return $dest;
        } catch (\Throwable $ex) {
            $this->warn("    (image download failed for {$slug}: {$ex->getMessage()})");

            return null;
        }
    }
}
