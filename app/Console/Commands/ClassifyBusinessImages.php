<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Services\AI\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClassifyBusinessImages extends Command
{
    protected $signature = 'businesses:classify-images
        {--apply : Write changes (default is a dry run that changes nothing)}
        {--limit=0 : Only process this many businesses (0 = all)}
        {--min-confidence=0.55 : Only auto-apply a classification at/above this confidence}
        {--model= : Vision model override (defaults to config ai.providers.openai.model)}';

    protected $description = 'Classify each business featured image as logo vs storefront photo. Photo → listing_image override; logo → logo field (leaving the big image to Street View).';

    public function handle(AIService $ai): int
    {
        $businesses = Business::query()
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '')
            ->orderBy('name')
            ->when((int) $this->option('limit') > 0, fn ($q) => $q->limit((int) $this->option('limit')))
            ->get();

        $apply = (bool) $this->option('apply');
        $min = (float) $this->option('min-confidence');
        $model = $this->option('model') ?: null;

        $this->info(($apply ? 'APPLYING CHANGES' : 'DRY RUN (no changes)').' — '.$businesses->count().' businesses with a featured image');
        $this->newLine();

        $rows = [];
        $photos = $logos = $errors = $lowconf = 0;

        foreach ($businesses as $b) {
            $path = Storage::disk('public')->path($b->featured_image);
            $result = $ai->classifyImageType($path, $model);

            if (! $result) {
                $errors++;
                $rows[] = [substr($b->name, 0, 26), 'ERROR', '—', 'could not classify (missing file or API error)'];
                continue;
            }

            $type = $result['type'];
            $conf = $result['confidence'];
            $action = $apply ? '' : '(dry run)';

            if ($type === 'photo') {
                $photos++;
            } else {
                $logos++;
            }

            if ($apply) {
                if ($conf < $min) {
                    $lowconf++;
                    $action = 'LOW CONFIDENCE — left unchanged';
                } elseif ($type === 'photo') {
                    // Real photo → user-defined override; leave logo empty.
                    $b->listing_image = $b->featured_image;
                    $b->save();
                    $action = 'set listing_image (override)';
                } else {
                    // Logo → move into the logo/avatar slot; big image comes from Street View.
                    if (blank($b->logo)) {
                        $b->logo = $b->featured_image;
                    }
                    $b->featured_image = null;
                    $b->save();
                    $action = 'moved to logo → Street View';
                }
            }

            $rows[] = [substr($b->name, 0, 26), strtoupper($type), number_format($conf, 2), trim($action.'  '.substr($result['reason'], 0, 28))];
        }

        $this->table(['Business', 'Type', 'Conf', 'Action / reason'], $rows);
        $this->newLine();
        $this->info("Photos: {$photos}   Logos: {$logos}   Errors: {$errors}   Low-confidence(skipped): {$lowconf}");

        if (! $apply) {
            $this->comment('Dry run only. Re-run with --apply to write. Then: php artisan streetview:backfill');
        } else {
            $this->comment('Done. Now run: php artisan streetview:backfill  (fills logo/no-image businesses; skips photo overrides)');
        }

        return self::SUCCESS;
    }
}
