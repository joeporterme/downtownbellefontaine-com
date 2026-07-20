<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;

class CrawlerController extends Controller
{
    /**
     * robots.txt — served dynamically so the Sitemap URL always matches the
     * current host (correct before and after the launch domain cutover), and
     * so AI assistants are explicitly welcomed.
     */
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /portal/',
            'Disallow: /admin/',
            'Disallow: /login',
            'Disallow: /register',
            '',
            '# AI assistants are welcome to read and cite our content',
        ];

        foreach (['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-Web', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended', 'CCBot'] as $bot) {
            $lines[] = "User-agent: {$bot}";
            $lines[] = 'Allow: /';
            $lines[] = '';
        }

        $lines[] = 'Sitemap: '.url('/sitemap.xml');

        return response(implode("\n", $lines)."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * llms.txt — a Markdown map of the site for large language models
     * (llmstxt.org convention). Curated so assistants can quickly understand
     * and cite Downtown Bellefontaine.
     */
    public function llms()
    {
        $s = SiteSetting::current();

        $link = fn (string $name, string $path, string $desc) => "- [{$name}]("
            .url($path)."): {$desc}";

        $sections = [
            '# Downtown Bellefontaine',
            '',
            '> The official guide to Downtown Bellefontaine, Ohio — the historic, walkable heart of Bellefontaine in Logan County, with local shops, restaurants, breweries, lodging, events, and attractions. Published by the Downtown Bellefontaine Partnership.',
            '',
            'Notable facts: Bellefontaine is Ohio\'s highest city (Campbell Hill, 1,549 ft) and home to America\'s first concrete street and America\'s shortest street. Downtown highlights include the historic Holland Theatre, monthly First Fridays, a DORA (Designated Outdoor Refreshment Area), and a self-guided historic walking tour.',
            '',
            '## Explore',
            $link('Things to Do', '/things-to-do', 'Attractions, entertainment, the Holland Theatre, and nearby destinations like Mad River Mountain and Indian Lake.'),
            $link('Places to Shop', '/places-to-shop', 'Local boutiques, antiques, gifts, toys, and specialty shops.'),
            $link('Food & Drinks', '/food-drinks', 'Restaurants, breweries, coffee, and dessert — including world-champion pizza and Brewfontaine craft beer.'),
            $link('Where to Stay', '/stay', 'Historic downtown lofts and nearby lodging.'),
            $link('Interactive Map', '/map', 'Find shops, dining, lodging, and parking across downtown.'),
            $link('Plan a Visit', '/plan-a-visit', 'Sample itineraries, parking, and directions.'),
            '',
            '## Events & Programs',
            $link('Events', '/events', 'Upcoming events in Downtown Bellefontaine.'),
            $link('First Fridays', '/first-fridays', 'Monthly community celebration with music, shopping, and food.'),
            $link('DORA District', '/dora', 'Designated Outdoor Refreshment Area rules, hours, and map.'),
            $link('Historic Walking Tour', '/historic-walking-tour', 'Self-guided 14-stop tour of historic downtown buildings.'),
            $link('Meeting & Event Spaces', '/meeting-spaces', 'Bookable venues for weddings, meetings, and parties.'),
            '',
            '## Directory & News',
            $link('Businesses', '/businesses', 'Full directory of downtown businesses.'),
            $link('Blog', '/blog', 'News and stories from downtown.'),
            $link('Media & Press', '/media', 'Press coverage and media mentions.'),
            '',
            '## Contact',
            $link('Contact', '/contact', 'Reach the Downtown Bellefontaine Partnership.'),
        ];

        if ($s->contact_email || $s->contact_phone) {
            $sections[] = '- Email: '.($s->contact_email ?: 'n/a').' · Phone: '.($s->contact_phone ?: 'n/a');
        }

        $sections[] = '';
        $sections[] = '## Full URL Index';
        $sections[] = $link('XML Sitemap', '/sitemap.xml', 'Machine-readable list of every page.');
        $sections[] = '';

        return response(implode("\n", $sections), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
