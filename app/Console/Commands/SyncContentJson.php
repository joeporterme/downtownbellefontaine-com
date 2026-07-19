<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Event;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * One-off content sync between environments via a JSON file. `dump` writes all
 * blog posts + events (with foreign keys expressed as values); `load` inserts
 * only the ones missing on the target (matched by slug), never overwriting
 * existing rows, and maps foreign keys defensively so a minor divergence in
 * related tables can't cause an FK error.
 */
class SyncContentJson extends Command
{
    protected $signature = 'content:sync {mode : dump|load} {--file=content-sync.json}';

    protected $description = 'Dump/load blog posts + events as JSON to sync content between environments (create-only).';

    public function handle(): int
    {
        $file = $this->option('file');

        return match ($this->argument('mode')) {
            'dump' => $this->dump($file),
            'load' => $this->load($file),
            default => tap(self::FAILURE, fn () => $this->error('mode must be dump or load')),
        };
    }

    protected function dump(string $file): int
    {
        $posts = BlogPost::with('businesses:id', 'businessCategories:id')->get()->map(fn (BlogPost $p) => [
            'title' => $p->title,
            'slug' => $p->slug,
            'content' => $p->content,
            'author_id' => $p->author_id,
            'blog_category_id' => $p->blog_category_id,
            'featured_image' => $p->featured_image,
            'status' => $p->status,
            'published_at' => optional($p->published_at)->toDateTimeString(),
            'seo_title' => $p->seo_title,
            'seo_description' => $p->seo_description,
            'business_ids' => $p->businesses->pluck('id')->all(),
            'business_category_ids' => $p->businessCategories->pluck('id')->all(),
        ]);

        $events = Event::all()->map(fn (Event $e) => collect($e->getAttributes())
            ->only((new Event)->getFillable())
            ->put('event_date', optional($e->event_date)->toDateString())
            ->all());

        file_put_contents($file, json_encode([
            'posts' => $posts,
            'events' => $events,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info("Dumped {$posts->count()} posts and {$events->count()} events to {$file}");

        return self::SUCCESS;
    }

    protected function load(string $file): int
    {
        if (! is_file($file)) {
            $this->error("File not found: {$file}");

            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($file), true);

        $defaultAuthor = Author::query()->min('id');
        $defaultCategory = BlogCategory::query()->min('id');
        $businessIds = Business::pluck('id')->flip();
        $businessCatIds = BusinessCategory::pluck('id')->flip();
        $userIds = User::pluck('id')->flip();

        $postsCreated = $postsSkipped = $eventsCreated = $eventsSkipped = 0;

        foreach ($data['posts'] ?? [] as $p) {
            if (BlogPost::where('slug', $p['slug'])->exists()) {
                $postsSkipped++;
                continue;
            }

            $post = BlogPost::create([
                'title' => $p['title'],
                'slug' => $p['slug'],
                'content' => $p['content'],
                'author_id' => Author::whereKey($p['author_id'])->exists() ? $p['author_id'] : $defaultAuthor,
                'blog_category_id' => BlogCategory::whereKey($p['blog_category_id'])->exists() ? $p['blog_category_id'] : $defaultCategory,
                'featured_image' => $p['featured_image'],
                'status' => $p['status'],
                'published_at' => $p['published_at'],
                'seo_title' => $p['seo_title'] ?? null,
                'seo_description' => $p['seo_description'] ?? null,
            ]);

            $post->businesses()->sync(collect($p['business_ids'] ?? [])->filter(fn ($id) => $businessIds->has($id))->all());
            $post->businessCategories()->sync(collect($p['business_category_ids'] ?? [])->filter(fn ($id) => $businessCatIds->has($id))->all());

            $postsCreated++;
        }

        foreach ($data['events'] ?? [] as $e) {
            if (Event::where('slug', $e['slug'])->exists()) {
                $eventsSkipped++;
                continue;
            }

            // Map FKs defensively — null out anything not present on this target.
            foreach (['business_id', 'submitted_by', 'approved_by'] as $fk) {
                if (! empty($e[$fk])) {
                    $pool = $fk === 'business_id' ? $businessIds : $userIds;
                    if (! $pool->has($e[$fk])) {
                        $e[$fk] = null;
                    }
                }
            }

            Event::create($e);
            $eventsCreated++;
        }

        $this->info("Posts: created {$postsCreated}, skipped {$postsSkipped} (already present)");
        $this->info("Events: created {$eventsCreated}, skipped {$eventsSkipped} (already present)");

        return self::SUCCESS;
    }
}
