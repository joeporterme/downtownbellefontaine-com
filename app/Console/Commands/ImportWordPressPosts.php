<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Business;
use App\Models\BusinessCategory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImportWordPressPosts extends Command
{
    protected $signature = 'import:wordpress-posts
                            {--dry-run : Run without actually importing}
                            {--skip-images : Skip downloading images}
                            {--skip-ai : Skip AI tagging and meta generation}
                            {--limit= : Limit the number of posts to import}';

    protected $description = 'Import blog posts from WordPress XML export';

    protected array $mediaLookup = [];
    protected array $businesses = [];
    protected array $businessCategories = [];

    protected int $authorId = 1; // Downtown Bellefontaine
    protected int $categoryId = 1; // News

    public function handle(): void
    {
        $postsPath = resource_path('posts.xml');
        $mediaPath = resource_path('media.xml');

        if (!file_exists($postsPath)) {
            $this->error('posts.xml not found in resources folder');
            return;
        }

        // Load businesses and categories for AI tagging
        $this->info('Loading businesses and categories from database...');
        $this->businesses = Business::pluck('name', 'id')->toArray();
        $this->businessCategories = BusinessCategory::pluck('name', 'id')->toArray();
        $this->info('Found ' . count($this->businesses) . ' businesses and ' . count($this->businessCategories) . ' categories');

        // Build media lookup if media.xml exists
        if (file_exists($mediaPath)) {
            $this->info('Building media lookup table...');
            $this->buildMediaLookup($mediaPath);
            $this->info('Found ' . count($this->mediaLookup) . ' media items');
        }

        // Parse posts
        $this->info('Parsing posts.xml...');
        $posts = $this->parsePostsXml($postsPath);
        $this->info('Found ' . count($posts) . ' posts');

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        if ($limit) {
            $posts = array_slice($posts, 0, $limit);
            $this->info("Limited to {$limit} posts");
        }

        $imported = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar(count($posts));
        $bar->start();

        foreach ($posts as $post) {
            if ($this->importPost($post)) {
                $imported++;
            } else {
                $skipped++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Import complete!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");
    }

    protected function buildMediaLookup(string $path): void
    {
        $xml = simplexml_load_file($path);
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            $postId = (string) $wpData->post_id;
            $attachmentUrl = (string) $wpData->attachment_url;

            if ($postId && $attachmentUrl) {
                $this->mediaLookup[$postId] = $attachmentUrl;
            }
        }
    }

    protected function parsePostsXml(string $path): array
    {
        $xml = simplexml_load_file($path);
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';

        $posts = [];

        foreach ($xml->channel->item as $item) {
            $wpData = $item->children($wp);
            $contentData = $item->children($content);

            // Only process posts (not pages, attachments, etc.)
            $postType = (string) $wpData->post_type;
            if ($postType !== 'post') {
                continue;
            }

            // Only process published posts
            $status = (string) $wpData->status;
            if ($status !== 'publish') {
                continue;
            }

            // Parse postmeta
            $meta = [];
            foreach ($wpData->postmeta as $postmeta) {
                $key = (string) $postmeta->meta_key;
                $value = (string) $postmeta->meta_value;
                $meta[$key] = $value;
            }

            $posts[] = [
                'wp_post_id' => (string) $wpData->post_id,
                'title' => (string) $item->title,
                'slug' => (string) $wpData->post_name,
                'content' => (string) $contentData->encoded,
                'published_at' => (string) $wpData->post_date,
                'thumbnail_id' => $meta['_thumbnail_id'] ?? null,
            ];
        }

        return $posts;
    }

    protected function importPost(array $data): bool
    {
        // Check if already imported (by slug)
        $existing = BlogPost::where('slug', $data['slug'])->first();

        if ($existing) {
            $this->newLine();
            $this->warn("Skipping duplicate: {$data['title']}");
            return false;
        }

        if ($this->option('dry-run')) {
            $this->newLine();
            $this->info("[DRY RUN] Would import: {$data['title']}");
            return true;
        }

        // Download and process image
        $featuredImage = null;
        if (!$this->option('skip-images') && !empty($data['thumbnail_id'])) {
            $featuredImage = $this->downloadAndProcessImage($data['thumbnail_id'], $data['slug']);
        }

        // Clean content HTML
        $cleanContent = $this->cleanContent($data['content']);

        // AI tagging and meta generation
        $businessIds = [];
        $categoryIds = [];
        $seoDescription = null;

        if (!$this->option('skip-ai') && !empty($cleanContent)) {
            $aiResult = $this->generateAITagsAndMeta($data['title'], $cleanContent);
            $businessIds = $aiResult['business_ids'] ?? [];
            $categoryIds = $aiResult['category_ids'] ?? [];
            $seoDescription = $aiResult['seo_description'] ?? null;
        }

        // Create the post
        $post = BlogPost::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $cleanContent,
            'blog_category_id' => $this->categoryId,
            'author_id' => $this->authorId,
            'featured_image' => $featuredImage,
            'status' => 'published',
            'published_at' => Carbon::parse($data['published_at']),
            'seo_title' => Str::limit($data['title'], 60, ''),
            'seo_description' => $seoDescription,
        ]);

        // Attach businesses and categories
        if (!empty($businessIds)) {
            $post->businesses()->attach($businessIds);
        }
        if (!empty($categoryIds)) {
            $post->businessCategories()->attach($categoryIds);
        }

        return true;
    }

    protected function generateAITagsAndMeta(string $title, string $content): array
    {
        $result = [
            'business_ids' => [],
            'category_ids' => [],
            'seo_description' => null,
        ];

        if (empty($this->businesses) && empty($this->businessCategories)) {
            return $result;
        }

        // Build context for AI
        $businessList = implode("\n", array_map(
            fn($name, $id) => "{$id}: {$name}",
            $this->businesses,
            array_keys($this->businesses)
        ));

        $categoryList = implode("\n", array_map(
            fn($name, $id) => "{$id}: {$name}",
            $this->businessCategories,
            array_keys($this->businessCategories)
        ));

        $contentPreview = $this->truncate(strip_tags($content), 1500);

        $prompt = <<<PROMPT
Analyze this blog post and provide:
1. A list of business IDs that are mentioned or clearly related to the content
2. A list of business category IDs that are relevant to the content
3. A compelling SEO meta description (150-160 characters) that summarizes the post

Blog Post Title: {$title}

Blog Post Content:
{$contentPreview}

Available Businesses:
{$businessList}

Available Business Categories:
{$categoryList}

Respond in this exact JSON format (no other text):
{
  "business_ids": [1, 2, 3],
  "category_ids": [1, 2],
  "seo_description": "Your meta description here"
}

If no businesses or categories match, use empty arrays. Always provide an seo_description.
PROMPT;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'max_tokens' => 500,
                'temperature' => 0.3,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if (!$response->successful()) {
                $this->warn("AI request failed: HTTP " . $response->status());
                return $result;
            }

            $content = $response->json('choices.0.message.content', '{}');

            // Clean up potential markdown code blocks
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);

            $parsed = json_decode(trim($content), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->warn("Failed to parse AI response as JSON");
                return $result;
            }

            // Validate business IDs
            if (!empty($parsed['business_ids']) && is_array($parsed['business_ids'])) {
                $result['business_ids'] = array_filter($parsed['business_ids'], function ($id) {
                    return isset($this->businesses[$id]);
                });
            }

            // Validate category IDs
            if (!empty($parsed['category_ids']) && is_array($parsed['category_ids'])) {
                $result['category_ids'] = array_filter($parsed['category_ids'], function ($id) {
                    return isset($this->businessCategories[$id]);
                });
            }

            // Get SEO description
            if (!empty($parsed['seo_description'])) {
                $result['seo_description'] = Str::limit($parsed['seo_description'], 160, '');
            }

            return $result;
        } catch (\Exception $e) {
            $this->warn("AI tagging failed: " . $e->getMessage());
            return $result;
        }
    }

    protected function downloadAndProcessImage(string $thumbnailId, string $slug): ?string
    {
        if (!isset($this->mediaLookup[$thumbnailId])) {
            $this->warn("Thumbnail ID {$thumbnailId} not found in media lookup");
            return null;
        }

        $imageUrl = $this->mediaLookup[$thumbnailId];

        try {
            // Download image
            $response = Http::timeout(30)->get($imageUrl);
            if (!$response->successful()) {
                $this->warn("Failed to download image (HTTP {$response->status()}): {$imageUrl}");
                return null;
            }

            $imageContent = $response->body();
            if (empty($imageContent)) {
                $this->warn("Empty image content from: {$imageUrl}");
                return null;
            }

            $filename = $slug . '-' . uniqid() . '.jpg';

            // Process with Intervention Image - resize and compress
            $image = Image::read($imageContent);

            // Resize to max 1920x1080 maintaining aspect ratio
            $image->scaleDown(1920, 1080);

            // Encode with quality reduction for compression
            $encoded = $image->toJpeg(80);

            // Save to storage (matching Filament's blog/featured directory)
            $path = 'blog/featured/' . $filename;
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

    protected function cleanContent(string $html): string
    {
        // Remove WordPress block comments
        $html = preg_replace('/<!--\s*\/?wp:[^>]*-->/s', '', $html);

        // Remove empty divs left by block removal
        $html = preg_replace('/<div class="wp-block-[^"]*">\s*<\/div>/s', '', $html);

        // Clean up WordPress-specific classes from remaining divs
        $html = preg_replace('/<div class="wp-block-[^"]*">/s', '<div>', $html);

        // Remove figure/figcaption wrappers but keep images
        $html = preg_replace('/<figure[^>]*>(.*?)<\/figure>/s', '$1', $html);
        $html = preg_replace('/<figcaption[^>]*>.*?<\/figcaption>/s', '', $html);

        // Keep allowed tags
        $html = strip_tags($html, '<p><br><strong><em><b><i><ul><ol><li><a><h2><h3><h4><blockquote><img>');

        // Remove excessive whitespace
        $html = preg_replace('/\n\s*\n\s*\n/', "\n\n", $html);
        $html = preg_replace('/(<br\s*\/?>\s*)+/', '<br>', $html);

        return trim($html);
    }

    protected function truncate(string $text, int $length): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
}
