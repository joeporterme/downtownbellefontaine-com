<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Services\Google\StreetViewService;
use Illuminate\Console\Command;

class BackfillStreetView extends Command
{
    protected $signature = 'streetview:backfill {--force : Regenerate even if a snapshot already exists}';

    protected $description = 'Generate Street View snapshots for each business\'s primary location';

    public function handle(StreetViewService $service): int
    {
        $businesses = Business::with('locations')->get();

        $generated = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($businesses->count());
        $bar->start();

        foreach ($businesses as $business) {
            $location = $business->locations->firstWhere('is_primary', true)
                ?? $business->locations->first();

            // Skip businesses without usable coordinates.
            if (! $location || blank($location->latitude) || blank($location->longitude)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Skip ones that already have a snapshot unless --force.
            if (filled($location->streetview_image) && ! $this->option('force')) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $path = $service->snapshot($location);

            if ($path) {
                $location->streetview_image = $path;
                $location->saveQuietly();
                $generated++;
            } else {
                // In force mode, clear a stale snapshot when a fresh one can't be made.
                if ($this->option('force') && filled($location->streetview_image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($location->streetview_image);
                    $location->streetview_image = null;
                    $location->saveQuietly();
                }
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Street View backfill complete.");
        $this->line("  Generated: {$generated}");
        $this->line("  Skipped:   {$skipped}");
        $this->line("  Failed:    {$failed}");

        return self::SUCCESS;
    }
}
