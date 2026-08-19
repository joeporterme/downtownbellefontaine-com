<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Redirect;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RedirectSeeder extends Seeder
{
    /**
     * Prefill the redirect manager with the WordPress → Laravel launch map.
     * Idempotent: upserts by from_path so it can be re-run safely.
     */
    public function run(): void
    {
        foreach ($this->patternRules() as $r) {
            $this->upsert($r + ['match_type' => 'pattern']);
        }

        foreach ($this->pageRules() as $from => $to) {
            $this->upsert(['from_path' => $from, 'to_url' => $to, 'notes' => 'pages']);
        }

        foreach ($this->renameRules() as $from => $to) {
            $this->upsert(['from_path' => $from, 'to_url' => $to, 'notes' => 'rename']);
        }

        $this->eventRules();
        $this->mediaRules();

        $this->command?->info('Redirects seeded: '.Redirect::count().' total.');
    }

    /**
     * Regex rules (checked after exact matches). Higher priority = evaluated first.
     */
    protected function patternRules(): array
    {
        return [
            // All 128 dated blog posts: /YYYY/MM/DD/slug → /blog/slug
            ['from_path' => '^/\d{4}/\d{2}/\d{2}/([^/]+)$', 'to_url' => '/blog/$1', 'priority' => 100, 'notes' => 'blog'],
            // Historic walking tour: landing + 14 stop pages → the single new page
            ['from_path' => '^/historictour(/.*)?$', 'to_url' => '/historic-walking-tour', 'priority' => 90, 'notes' => 'tour'],
            // Day agendas CPT → events
            ['from_path' => '^/day-agenda/.+$', 'to_url' => '/events', 'priority' => 80, 'notes' => 'events'],
            // Category & author archives → blog
            ['from_path' => '^/category/.+$', 'to_url' => '/blog', 'priority' => 70, 'notes' => 'archives'],
            ['from_path' => '^/author/.+$', 'to_url' => '/blog', 'priority' => 70, 'notes' => 'archives'],
            // Old static "things to do" microsite → new page
            ['from_path' => '^/thingstodo(/.*)?$', 'to_url' => '/things-to-do', 'priority' => 60, 'notes' => 'pages'],
            // Catch-all for every remaining old event page (exact event rules win first)
            ['from_path' => '^/event/.+$', 'to_url' => '/events', 'priority' => 10, 'notes' => 'events'],
        ];
    }

    /**
     * Exact old page paths whose slug differs from the new route (or that have
     * no direct equivalent). Editable later in the admin.
     */
    protected function pageRules(): array
    {
        return [
            '/downtown-map' => '/map',
            '/place-to-shop' => '/places-to-shop',
            '/contact-us' => '/contact',
            '/day-agendas' => '/events',
            '/create-event' => '/events',
            '/restaurantweek' => '/events',
            '/loftandhiddenspacestour' => '/events',
            '/scavenger-hunt' => '/events',
            '/christmas' => '/events',
            '/springcleaning' => '/events',
            '/subscribe' => '/',
            '/thankyou' => '/',
            '/thank-you' => '/',
            '/qa-event-creation-page' => '/',
            '/dev-page1' => '/',
        ];
    }

    /**
     * Internal page renames (not WordPress) — keep old links working.
     *
     * @return array<string,string>
     */
    protected function renameRules(): array
    {
        return [
            '/first-fridays' => '/downtown-days',
        ];
    }

    /**
     * The events that carried over: old /event/{slug} → new /events/{slug}.
     * Higher-value than the catch-all pattern (exact matches are checked first).
     */
    protected function eventRules(): void
    {
        foreach (Event::whereNotNull('slug')->get() as $event) {
            $this->upsert([
                'from_path' => '/event/'.$event->slug,
                'to_url' => '/events/'.$event->slug,
                'notes' => 'events',
            ]);
        }
    }

    /**
     * Media actually used on the new site: old /wp-content/uploads/... → new path.
     * Sourced from the git-tracked CSV produced by `php artisan redirects:inventory`.
     */
    protected function mediaRules(): void
    {
        $csv = database_path('data/redirect-media-map.csv');

        if (! File::exists($csv)) {
            $this->command?->warn('database/data/redirect-media-map.csv missing — run `php artisan redirects:inventory` first. Skipping media redirects.');

            return;
        }

        $fh = fopen($csv, 'r');
        fgetcsv($fh); // header

        while (($row = fgetcsv($fh)) !== false) {
            [$oldPath, $newUrl] = $row;

            if (blank($oldPath) || blank($newUrl)) {
                continue;
            }

            $this->upsert(['from_path' => $oldPath, 'to_url' => $newUrl, 'notes' => 'media']);
        }

        fclose($fh);
    }

    protected function upsert(array $attrs): void
    {
        Redirect::updateOrCreate(
            ['from_path' => $attrs['from_path']],
            array_merge([
                'status_code' => 301,
                'match_type' => 'exact',
                'priority' => 0,
                'is_active' => true,
            ], $attrs),
        );
    }
}
