<?php

namespace App\Console\Commands;

use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImportBusinessImages extends Command
{
    protected $signature = 'import:business-images
                            {--dry-run : Show matches without downloading}
                            {--limit= : Limit the number of businesses to process}';

    protected $description = 'Import business images from sn_listing_images.sql mappings';

    // Mapping of listingId => imageUrl from sn_listing_images.sql
    protected array $imageMap = [];

    // Mapping of listingId => businessName from sn_listing.sql
    protected array $listingNames = [];

    public function handle(): void
    {
        $this->info('Building listing name map from sn_listing.sql...');
        $this->buildListingNames();
        $this->info('Found ' . count($this->listingNames) . ' listing names');

        $this->info('Building image map from sn_listing_images.sql...');
        $this->buildImageMap();
        $this->info('Found ' . count($this->imageMap) . ' business image mappings');

        // Get businesses without images
        $query = Business::whereNull('featured_image')
            ->orWhere('featured_image', '');

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        $businesses = $query->get();
        $this->info('Found ' . $businesses->count() . ' businesses without images');

        $updated = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($businesses->count());
        $bar->start();

        foreach ($businesses as $business) {
            // Find the original listingId by matching business name
            $listingId = $this->findListingIdByName($business->name);

            if (!$listingId || !isset($this->imageMap[$listingId]) || empty($this->imageMap[$listingId])) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $imageUrl = $this->imageMap[$listingId];

            if ($this->option('dry-run')) {
                $this->newLine();
                $this->info("[DRY RUN] {$business->name} (listingId: {$listingId}) => {$imageUrl}");
                $updated++;
                $bar->advance();
                continue;
            }

            $path = $this->downloadAndProcessImage($imageUrl, $business->slug ?? Str::slug($business->name));
            if ($path) {
                $business->update(['featured_image' => $path]);
                $updated++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Complete!");
        $this->info("Updated: {$updated}");
        $this->info("Skipped (no mapping): {$skipped}");
        $this->info("Failed: {$failed}");
    }

    protected function buildListingNames(): void
    {
        $sqlPath = resource_path('sn_listing.sql');

        if (!file_exists($sqlPath)) {
            $this->error('sn_listing.sql not found in resources folder');
            return;
        }

        $content = file_get_contents($sqlPath);

        // Match INSERT VALUES: (listingId, 'listingTitle', ...)
        // Pattern: (id, 'name', 'category', 'description', ...)
        preg_match_all(
            "/\((\d+),\s*'([^']*(?:''[^']*)*)',/",
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $listingId = (int) $match[1];
            // Handle escaped single quotes in names
            $name = str_replace("''", "'", $match[2]);
            $this->listingNames[$listingId] = $name;
        }
    }

    protected function findListingIdByName(string $businessName): ?int
    {
        // Exact match first
        foreach ($this->listingNames as $listingId => $name) {
            if (strcasecmp($name, $businessName) === 0) {
                return $listingId;
            }
        }

        // Fuzzy match - check if names are similar
        $businessNameLower = strtolower($businessName);
        foreach ($this->listingNames as $listingId => $name) {
            $nameLower = strtolower($name);
            // Check if one contains the other or very similar
            if (str_contains($businessNameLower, $nameLower) || str_contains($nameLower, $businessNameLower)) {
                return $listingId;
            }
            // Check similarity
            similar_text($businessNameLower, $nameLower, $percent);
            if ($percent > 85) {
                return $listingId;
            }
        }

        return null;
    }

    protected function buildImageMap(): void
    {
        $sqlPath = resource_path('sn_listing_images.sql');

        if (!file_exists($sqlPath)) {
            $this->error('sn_listing_images.sql not found in resources folder');
            return;
        }

        $content = file_get_contents($sqlPath);

        // Match INSERT VALUES: (id, listingId, 'imageUrl')
        preg_match_all(
            "/\((\d+),\s*(\d+),\s*'([^']*)'\)/",
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $listingId = (int) $match[2];
            $imageUrl = $match[3];

            // Only store if URL is not empty and is an actual image URL
            if (!empty($imageUrl) && str_starts_with($imageUrl, 'http')) {
                $this->imageMap[$listingId] = $imageUrl;
            }
        }
    }

    protected function downloadAndProcessImage(string $imageUrl, string $slug): ?string
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);
            if (!$response->successful()) {
                $this->newLine();
                $this->warn("Failed to download (HTTP {$response->status()}): {$imageUrl}");
                return null;
            }

            $imageContent = $response->body();
            if (empty($imageContent)) {
                return null;
            }

            // Keep original extension for non-JPEG formats like PNG
            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = $slug . '-' . uniqid() . '.jpg';

            $image = Image::read($imageContent);
            $image->scaleDown(1920, 1080);
            $encoded = $image->toJpeg(80);

            $path = 'businesses/' . $filename;
            Storage::disk('public')->put($path, $encoded);

            // Free memory
            unset($image, $imageContent, $encoded);
            gc_collect_cycles();

            return $path;
        } catch (\Exception $e) {
            $this->newLine();
            $this->warn("Image processing failed for '{$slug}': " . $e->getMessage());
            return null;
        }
    }
}
