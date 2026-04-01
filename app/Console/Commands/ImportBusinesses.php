<?php

namespace App\Console\Commands;

use App\Services\BusinessImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportBusinesses extends Command
{
    protected $signature = 'businesses:import
                            {--path= : Path to the resources folder containing SQL files}
                            {--dry-run : Preview what would be imported without making changes}';

    protected $description = 'Import businesses from legacy SQL files';

    public function handle(BusinessImportService $importer): int
    {
        $path = $this->option('path') ?? resource_path();

        $listingsPath = "{$path}/sn_listing.sql";
        $categoriesPath = "{$path}/sn_listing_category.sql";
        $assignedCatPath = "{$path}/sn_listing_assigned_cat.sql";
        $imagesPath = "{$path}/sn_listing_images.sql";

        // Verify files exist
        foreach ([$listingsPath, $categoriesPath, $assignedCatPath, $imagesPath] as $file) {
            if (!file_exists($file)) {
                $this->error("File not found: {$file}");
                return Command::FAILURE;
            }
        }

        $this->info('Starting business import...');
        $this->newLine();

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->newLine();
            return $this->dryRun($importer, $listingsPath, $categoriesPath, $assignedCatPath, $imagesPath);
        }

        // Set up progress callback
        $progressBar = null;
        $importer->onProgress(function ($message, $current, $total) use (&$progressBar) {
            if ($progressBar === null) {
                $progressBar = $this->output->createProgressBar($total);
                $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- %message%');
            }
            $progressBar->setMessage($message);
            $progressBar->setProgress($current);
        });

        try {
            $result = $importer->importFromSqlFiles(
                $listingsPath,
                $categoriesPath,
                $assignedCatPath,
                $imagesPath
            );

            if ($progressBar) {
                $progressBar->finish();
            }

            $this->newLine(2);
            $this->displayResults($result);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('Import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function dryRun(
        BusinessImportService $importer,
        string $listingsPath,
        string $categoriesPath,
        string $assignedCatPath,
        string $imagesPath
    ): int {
        $listings = $importer->parseSqlInserts($listingsPath, 'sn_listing');
        $categories = $importer->parseSqlInserts($categoriesPath, 'sn_listing_category');
        $assignedCats = $importer->parseSqlInserts($assignedCatPath, 'sn_listing_assigned_cat');
        $images = $importer->parseSqlInserts($imagesPath, 'sn_listing_images');

        $this->info('Categories to import:');
        $this->table(
            ['ID', 'Name', 'URL', 'Status'],
            collect($categories)->map(fn($c) => [
                $c['categoryId'],
                $c['categoryTitle'],
                $c['categoryURL'],
                $c['categoryStatus'] === '1' ? 'Active' : 'Inactive',
            ])->all()
        );

        $this->newLine();
        $this->info('Businesses to import: ' . count($listings));
        $this->newLine();

        $this->table(
            ['ID', 'Name', 'Email', 'Has Image', 'Categories'],
            collect($listings)->take(20)->map(function ($l) use ($assignedCats, $categories, $images) {
                $catIds = collect($assignedCats)
                    ->where('listingId', $l['listingId'])
                    ->pluck('categoryId');

                $catNames = collect($categories)
                    ->whereIn('categoryId', $catIds)
                    ->pluck('categoryTitle')
                    ->implode(', ');

                $hasImage = collect($images)->where('listingId', $l['listingId'])->isNotEmpty();

                return [
                    $l['listingId'],
                    Str::limit($l['listingTitle'], 30),
                    $l['email'] ?: '(temp email)',
                    $hasImage ? 'Yes' : 'No',
                    Str::limit($catNames, 40),
                ];
            })->all()
        );

        if (count($listings) > 20) {
            $this->line('... and ' . (count($listings) - 20) . ' more businesses');
        }

        $this->newLine();
        $this->info('Summary:');
        $this->line("  - Total businesses: " . count($listings));
        $this->line("  - Total categories: " . count($categories));
        $this->line("  - Total images: " . count($images));
        $this->line("  - Category assignments: " . count($assignedCats));

        return Command::SUCCESS;
    }

    protected function displayResults(array $result): void
    {
        $this->info('Import Complete!');
        $this->newLine();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $result['stats']['total']],
                ['Successfully Imported', $result['stats']['imported']],
                ['Skipped (Already Exists)', $result['stats']['skipped']],
                ['Errors', $result['stats']['errors']],
                ['Categories Created', $result['categories_imported']],
            ]
        );

        if (!empty($result['errors'])) {
            $this->newLine();
            $this->warn('Errors encountered:');
            foreach (array_slice($result['errors'], 0, 10) as $error) {
                $this->line("  - {$error['business']}: {$error['error']}");
            }
            if (count($result['errors']) > 10) {
                $this->line('  ... and ' . (count($result['errors']) - 10) . ' more errors');
            }
        }
    }
}
