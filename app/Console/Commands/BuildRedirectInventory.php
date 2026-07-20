<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Support\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class BuildRedirectInventory extends Command
{
    protected $signature = 'redirects:inventory
                            {--base=https://downtownbellefontaine.com : Base URL of the live WordPress site}';

    protected $description = 'Deep-dive the old WordPress site: inventory every old URL (live Yoast sitemap) and map the media actually used on the new site. Writes storage/app/redirects/{old-urls,media-map}.csv.';

    public function handle(): int
    {
        $dir = storage_path('app/redirects');
        File::ensureDirectoryExists($dir);

        $this->inventoryOldUrls(rtrim($this->option('base'), '/'), $dir);
        $this->inventoryUsedMedia($dir);

        return self::SUCCESS;
    }

    /**
     * Enumerate every published old URL from the live Yoast sitemap index
     * (authoritative — includes the blog_post CPT the WXR omits).
     */
    protected function inventoryOldUrls(string $base, string $dir): void
    {
        $this->info("Fetching sitemap index from {$base} …");

        $rows = [];

        try {
            $index = Http::timeout(30)->withHeaders(['User-Agent' => 'Mozilla/5.0'])->get("{$base}/sitemap_index.xml");

            if (! $index->successful()) {
                throw new \RuntimeException("sitemap_index.xml returned {$index->status()}");
            }

            foreach ($this->locs($index->body()) as $subUrl) {
                $type = str_replace('-sitemap.xml', '', basename(parse_url($subUrl, PHP_URL_PATH)));
                $sub = Http::timeout(30)->withHeaders(['User-Agent' => 'Mozilla/5.0'])->get($subUrl);

                if (! $sub->successful()) {
                    $this->warn("  skipped {$subUrl} ({$sub->status()})");

                    continue;
                }

                foreach ($this->locs($sub->body()) as $url) {
                    $rows[] = [
                        'url' => $url,
                        'path' => rtrim(parse_url($url, PHP_URL_PATH), '/') ?: '/',
                        'type' => $type,
                    ];
                }

                $this->line("  {$type}: ".count($this->locs($sub->body())));
            }
        } catch (\Throwable $e) {
            $this->warn("Live sitemap unavailable ({$e->getMessage()}); the old-URL inventory may be incomplete.");
        }

        // De-dupe by path.
        $rows = collect($rows)->unique('path')->values();

        $this->writeCsv("{$dir}/old-urls.csv", ['url', 'path', 'type'], $rows->all());
        $this->info('Wrote '.$rows->count().' old URLs → storage/app/redirects/old-urls.csv');
    }

    /**
     * Map old WordPress media URLs to their new location for media actually
     * used on the new site. Blog featured images are matched post→thumbnail
     * via the WXR export (resources/posts.xml), joined to the new post by slug.
     */
    protected function inventoryUsedMedia(string $dir): void
    {
        $wxr = resource_path('posts.xml');

        if (! File::exists($wxr)) {
            $this->warn('resources/posts.xml not found; skipping media map.');

            return;
        }

        $this->info('Parsing WXR for blog featured images …');

        $xml = simplexml_load_file($wxr);
        $ns = $xml->getNamespaces(true);

        $attachments = [];   // attachment_id => full old URL
        $postThumb = [];     // post slug => thumbnail attachment_id

        foreach ($xml->channel->item as $item) {
            $wp = $item->children($ns['wp'] ?? 'http://wordpress.org/export/1.2/');
            $type = (string) $wp->post_type;

            if ($type === 'attachment') {
                $attachments[(string) $wp->post_id] = (string) $wp->attachment_url;
            } elseif ($type === 'post') {
                foreach ($wp->postmeta as $meta) {
                    if ((string) $meta->meta_key === '_thumbnail_id') {
                        $postThumb[(string) $wp->post_name] = (string) $meta->meta_value;
                    }
                }
            }
        }

        $rows = [];

        foreach (BlogPost::whereNotNull('featured_image')->get() as $post) {
            $thumbId = $postThumb[$post->slug] ?? null;

            if (! $thumbId || empty($attachments[$thumbId])) {
                continue;
            }

            $oldPath = parse_url($attachments[$thumbId], PHP_URL_PATH);

            if (! $oldPath || ! str_contains($oldPath, '/wp-content/uploads/')) {
                continue;
            }

            // Store the target as a domain-independent path (the app becomes
            // downtownbellefontaine.com at launch; the matcher makes it absolute
            // against the current host).
            $newUrl = Media::url($post->featured_image);
            $newPath = parse_url($newUrl, PHP_URL_PATH) ?: $newUrl;

            $rows[] = [
                'old_path' => $oldPath,
                'new_url' => $newPath,
                'source' => 'blog',
            ];
        }

        $rows = collect($rows)->unique('old_path')->values();

        // Working artifact (bundle) + the git-tracked copy the seeder reads on prod.
        $this->writeCsv("{$dir}/media-map.csv", ['old_path', 'new_url', 'source'], $rows->all());

        $seedPath = database_path('data');
        File::ensureDirectoryExists($seedPath);
        $this->writeCsv("{$seedPath}/redirect-media-map.csv", ['old_path', 'new_url', 'source'], $rows->all());

        $this->info('Wrote '.$rows->count().' used-media mappings → storage/app/redirects/media-map.csv + database/data/redirect-media-map.csv');
    }

    /** Extract <loc> values from a sitemap XML body. */
    protected function locs(string $xml): array
    {
        preg_match_all('#<loc>([^<]+)</loc>#', $xml, $m);

        return $m[1] ?? [];
    }

    protected function writeCsv(string $path, array $header, array $rows): void
    {
        $fh = fopen($path, 'w');
        fputcsv($fh, $header);

        foreach ($rows as $row) {
            fputcsv($fh, is_array($row) ? array_values($row) : (array) $row);
        }

        fclose($fh);
    }
}
