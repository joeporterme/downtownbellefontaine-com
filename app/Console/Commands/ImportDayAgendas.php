<?php

namespace App\Console\Commands;

use App\Models\DayAgenda;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportDayAgendas extends Command
{
    protected $signature = 'day-agendas:import
                            {--file=database/data/wp-day-agendas.json : JSON export of WordPress day agendas}
                            {--no-images : Skip downloading featured images}';

    protected $description = 'Import day-agenda itineraries from the WordPress export into the day_agendas table (additive, idempotent by slug). Never touches businesses or events.';

    public function handle(): int
    {
        $path = base_path($this->option('file'));

        if (! File::exists($path)) {
            $this->error("Export file not found: {$path}");

            return self::FAILURE;
        }

        $agendas = json_decode(File::get($path), true) ?: [];
        $sort = 0;
        $imported = 0;

        foreach ($agendas as $a) {
            $slug = $a['slug'] ?: Str::slug($a['title']);

            $attrs = [
                'title' => $a['title'],
                'excerpt' => $a['excerpt'] ? Str::limit(trim(strip_tags($a['excerpt'])), 480, '…') : null,
                'content' => $this->cleanContent($a['content'] ?? ''),
                'sort' => $sort++,
                'status' => 'published',
                'published_at' => ! empty($a['date']) ? Carbon::parse($a['date'], 'UTC') : now(),
            ];

            if (! $this->option('no-images') && ! empty($a['thumb'])) {
                if ($stored = $this->downloadImage($a['thumb'], $slug)) {
                    $attrs['featured_image'] = $stored;
                }
            }

            DayAgenda::updateOrCreate(['slug' => $slug], $attrs);
            $imported++;
            $this->line("  ✓ {$a['title']}");
        }

        $this->info("Imported/updated {$imported} day agenda(s).");

        return self::SUCCESS;
    }

    /** Strip WordPress block comments + inline styles; keep itinerary HTML (safe_content sanitizes on render). */
    protected function cleanContent(string $html): string
    {
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/\s*style="[^"]*"/i', '', $html);
        $html = preg_replace('/(\R\s*){3,}/', "\n\n", $html);

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
            $dest = "day-agendas/featured/{$slug}.{$ext}";
            Storage::disk('public')->put($dest, $res->body());

            return $dest;
        } catch (\Throwable $e) {
            $this->warn("    (image download failed for {$slug}: {$e->getMessage()})");

            return null;
        }
    }
}
