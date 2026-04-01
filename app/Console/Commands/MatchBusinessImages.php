<?php

namespace App\Console\Commands;

use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class MatchBusinessImages extends Command
{
    protected $signature = 'businesses:match-images
                            {--dry-run : Show matches without downloading}
                            {--limit= : Limit the number of businesses to process}';

    protected $description = 'Match businesses with images from WordPress media.xml using AI';

    protected array $mediaItems = [];

    public function handle(): void
    {
        $mediaPath = resource_path('media.xml');

        if (!file_exists($mediaPath)) {
            $this->error('media.xml not found in resources folder');
            return;
        }

        $this->info('Parsing media.xml...');
        $this->parseMediaXml($mediaPath);
        $this->info('Found ' . count($this->mediaItems) . ' image items');

        // Get businesses without images
        $query = Business::whereNull('featured_image')
            ->orWhere('featured_image', '');

        if ($this->option('limit')) {
            $query->limit((int) $this->option('limit'));
        }

        $businesses = $query->get();
        $this->info('Found ' . $businesses->count() . ' businesses without images');

        if ($businesses->isEmpty()) {
            $this->info('All businesses already have images!');
            return;
        }

        $matched = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($businesses->count());
        $bar->start();

        foreach ($businesses as $business) {
            $imageUrl = $this->findBestImageMatch($business);

            if ($imageUrl) {
                if ($this->option('dry-run')) {
                    $this->newLine();
                    $this->info("[DRY RUN] {$business->name} => {$imageUrl}");
                    $matched++;
                } else {
                    $path = $this->downloadAndProcessImage($imageUrl, $business->slug ?? Str::slug($business->name));
                    if ($path) {
                        $business->update(['featured_image' => $path]);
                        $matched++;
                    } else {
                        $failed++;
                    }
                }
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Complete! Matched: {$matched}, Failed: {$failed}");
    }

    protected function parseMediaXml(string $path): void
    {
        $xml = simplexml_load_file($path);
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            $attachmentUrl = (string) $wpData->attachment_url;
            $title = (string) $item->title;
            $postName = (string) $wpData->post_name;

            // Only include image files
            if ($attachmentUrl && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $attachmentUrl)) {
                $this->mediaItems[] = [
                    'url' => $attachmentUrl,
                    'title' => $title,
                    'slug' => $postName,
                ];
            }
        }
    }

    protected function findBestImageMatch(Business $business): ?string
    {
        // First, try direct name matching (faster, no API call)
        $businessSlug = Str::slug($business->name);
        $businessWords = explode('-', $businessSlug);

        // Look for exact or partial matches in image names
        $candidates = [];
        foreach ($this->mediaItems as $media) {
            $score = 0;
            $mediaSlug = strtolower($media['slug']);
            $mediaTitle = strtolower($media['title']);

            // Exact slug match
            if (str_contains($mediaSlug, $businessSlug) || str_contains($mediaTitle, strtolower($business->name))) {
                $score += 100;
            }

            // Word matching
            foreach ($businessWords as $word) {
                if (strlen($word) >= 3) {
                    if (str_contains($mediaSlug, $word)) {
                        $score += 10;
                    }
                    if (str_contains($mediaTitle, $word)) {
                        $score += 10;
                    }
                }
            }

            if ($score > 0) {
                $candidates[] = [
                    'media' => $media,
                    'score' => $score,
                ];
            }
        }

        // Sort by score descending
        usort($candidates, fn($a, $b) => $b['score'] <=> $a['score']);

        // If we have a high-confidence match (score >= 50), use it directly
        if (!empty($candidates) && $candidates[0]['score'] >= 50) {
            return $candidates[0]['media']['url'];
        }

        // If we have some candidates but low confidence, use AI to pick the best one
        if (!empty($candidates)) {
            $topCandidates = array_slice($candidates, 0, 10);
            return $this->useAIToSelectBestMatch($business, $topCandidates);
        }

        return null;
    }

    protected function useAIToSelectBestMatch(Business $business, array $candidates): ?string
    {
        $candidateList = '';
        foreach ($candidates as $i => $c) {
            $candidateList .= ($i + 1) . ". {$c['media']['title']} ({$c['media']['slug']})\n";
        }

        $prompt = <<<PROMPT
Given this business information, which image from the list below is most likely to be the business's featured/main image?

Business Name: {$business->name}
Business Address: {$business->address}
Business Description: {$business->description}

Available Images:
{$candidateList}

Respond with ONLY the number of the best matching image (1-10), or "none" if none of them are a good match for this specific business.
Do not include any explanation, just the number or "none".
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'max_tokens' => 10,
                'temperature' => 0.1,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if (!$response->successful()) {
                return null;
            }

            $content = trim($response->json('choices.0.message.content', 'none'));

            if ($content === 'none' || !is_numeric($content)) {
                return null;
            }

            $index = (int) $content - 1;
            if (isset($candidates[$index])) {
                return $candidates[$index]['media']['url'];
            }
        } catch (\Exception $e) {
            $this->warn("AI matching failed for {$business->name}: " . $e->getMessage());
        }

        return null;
    }

    protected function downloadAndProcessImage(string $imageUrl, string $slug): ?string
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);
            if (!$response->successful()) {
                $this->warn("Failed to download: {$imageUrl}");
                return null;
            }

            $imageContent = $response->body();
            if (empty($imageContent)) {
                return null;
            }

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
            $this->warn("Image processing failed for '{$slug}': " . $e->getMessage());
            return null;
        }
    }
}
