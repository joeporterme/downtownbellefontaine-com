<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;

/**
 * Imported posts often repeat the WordPress featured image inline in the body,
 * so it shows twice (hero + content). Using the original WXR exports we know
 * each post's featured image source URL; this removes the matching <img> (and
 * its figure/link/empty-paragraph wrapper) from the content.
 */
class DedupeBlogFeatured extends Command
{
    protected $signature = 'blog:dedupe-featured
        {--apply : Write changes (default is a dry run)}
        {--media=resources/media.xml : WXR export containing attachments}
        {--posts=resources/posts.xml : WXR export containing posts}';

    protected $description = 'Remove the featured image from blog post content where it is duplicated inline.';

    private const WP_NS = 'http://wordpress.org/export/1.2/';

    public function handle(): int
    {
        $mediaFile = base_path($this->option('media'));
        $postsFile = base_path($this->option('posts'));

        if (! is_file($mediaFile) || ! is_file($postsFile)) {
            $this->error('WXR export files not found. Pass --media and --posts.');

            return self::FAILURE;
        }

        $mediaBase = $this->mediaBaseNames($mediaFile);   // attachment id => base filename
        $thumbs = $this->postThumbnails($postsFile);      // slug => attachment id
        $this->info(count($mediaBase).' attachments, '.count($thumbs).' posts with a thumbnail');

        $apply = (bool) $this->option('apply');
        $rows = [];
        $changed = 0;

        foreach (BlogPost::whereNotNull('featured_image')->where('content', 'like', '%<img%')->get() as $post) {
            $tid = $thumbs[$post->slug] ?? null;
            $base = $tid ? ($mediaBase[$tid] ?? null) : null;

            if (! $base) {
                continue;
            }

            [$new, $removed] = $this->stripImages($post->content, $base);

            if ($removed > 0) {
                $changed++;
                $rows[] = [substr($post->title, 0, 44), $base, $removed, $apply ? 'removed' : '(dry run)'];

                if ($apply) {
                    $post->content = $new;
                    $post->save();
                }
            }
        }

        $this->table(['Post', 'Featured base', '#img removed', 'Action'], $rows);
        $this->info(($apply ? 'Updated' : 'Would update').": {$changed} posts");

        if (! $apply) {
            $this->comment('Dry run. Re-run with --apply to write.');
        }

        return self::SUCCESS;
    }

    /** attachment post_id => base filename (no dir, no extension, no -WxH / -scaled suffix). */
    private function mediaBaseNames(string $file): array
    {
        $xml = simplexml_load_file($file);
        $map = [];

        foreach ($xml->channel->item as $item) {
            $wp = $item->children(self::WP_NS);
            if ((string) $wp->post_type !== 'attachment') {
                continue;
            }
            $id = (string) $wp->post_id;
            $url = (string) $wp->attachment_url;
            if ($id !== '' && $url !== '') {
                $map[$id] = $this->baseName($url);
            }
        }

        return $map;
    }

    /** post slug => _thumbnail_id. */
    private function postThumbnails(string $file): array
    {
        $xml = simplexml_load_file($file);
        $map = [];

        foreach ($xml->channel->item as $item) {
            $wp = $item->children(self::WP_NS);
            if ((string) $wp->post_type !== 'post') {
                continue;
            }
            $slug = (string) $wp->post_name;
            foreach ($wp->postmeta as $meta) {
                if ((string) $meta->meta_key === '_thumbnail_id') {
                    if ($slug !== '') {
                        $map[$slug] = (string) $meta->meta_value;
                    }
                    break;
                }
            }
        }

        return $map;
    }

    private function baseName(string $url): string
    {
        $name = pathinfo(parse_url($url, PHP_URL_PATH) ?? $url, PATHINFO_FILENAME);
        $name = preg_replace('/-\d+x\d+$/', '', $name); // WP size suffix
        $name = preg_replace('/-scaled$/', '', $name);

        return strtolower($name);
    }

    /** Remove <img> (with wrapping figure/a and empty paragraphs) whose src basename matches $base. */
    private function stripImages(string $html, string $base): array
    {
        $b = preg_quote($base, '#');
        // src ending in .../{base}(-WxH)?(-scaled)?.ext
        $imgSrc = 'src="[^"]*\/'.$b.'(?:-\d+x\d+)?(?:-scaled)?\.[a-z0-9]+(?:\?[^"]*)?"';

        $count = 0;
        $patterns = [
            // <figure>…<img match>…</figure> (WP block image, may include a caption)
            '#<figure\b[^>]*>(?:(?!</figure>).)*?<img\b[^>]*'.$imgSrc.'[^>]*>.*?</figure>#is',
            // <p><a><img match></a></p> / <p><img match></p> and bare wrappers
            '#<p\b[^>]*>\s*(?:<a\b[^>]*>\s*)?<img\b[^>]*'.$imgSrc.'[^>]*>\s*(?:</a>\s*)?</p>#is',
            '#<a\b[^>]*>\s*<img\b[^>]*'.$imgSrc.'[^>]*>\s*</a>#is',
            // bare <img match>
            '#<img\b[^>]*'.$imgSrc.'[^>]*>#is',
        ];

        foreach ($patterns as $p) {
            $html = preg_replace_callback($p, function ($m) use (&$count) {
                $count++;

                return '';
            }, $html);
        }

        // Tidy up any now-empty paragraphs left behind.
        $html = preg_replace('#<p>\s*</p>#i', '', $html);

        return [trim($html), $count];
    }
}
