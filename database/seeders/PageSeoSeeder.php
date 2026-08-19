<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeoSeeder extends Seeder
{
    /**
     * Tailored SEO titles + meta descriptions for the static CMS pages,
     * targeting "Bellefontaine" / "Downtown Bellefontaine" search intent.
     * Idempotent: updates existing Page rows by key. Editable later in the
     * admin (Pages resource → SEO fields).
     */
    public function run(): void
    {
        foreach ($this->copy() as $key => [$title, $description]) {
            Page::where('key', $key)->update([
                'seo_title' => $title,
                'seo_description' => $description,
            ]);
        }
    }

    /**
     * @return array<string, array{0:string,1:string}>  key => [seo_title, seo_description]
     */
    protected function copy(): array
    {
        return [
            'home' => [
                'Downtown Bellefontaine, Ohio | Shop, Dine, Stay & Explore',
                'Discover Downtown Bellefontaine, Ohio — local shops, restaurants, events, and things to do in Logan County\'s most loveable, walkable downtown.',
            ],
            'things-to-do' => [
                'Things to Do in Downtown Bellefontaine, Ohio',
                'Explore things to do in Downtown Bellefontaine — the historic Holland Theatre, axe throwing, murals, live shows, plus Mad River Mountain and Indian Lake nearby.',
            ],
            'places-to-shop' => [
                'Shopping in Downtown Bellefontaine, Ohio',
                'Shop small in Downtown Bellefontaine, Ohio — boutiques, antiques, gifts, toys, chocolates, and specialty shops in our loveable, walkable downtown.',
            ],
            'food-drinks' => [
                'Where to Eat & Drink in Downtown Bellefontaine',
                'Where to eat and drink in Downtown Bellefontaine, Ohio — world-champion pizza, Brewfontaine craft beer, coffee, fine dining, and sweet treats.',
            ],
            'stay' => [
                'Where to Stay in Downtown Bellefontaine, Ohio',
                'Where to stay in Downtown Bellefontaine, Ohio — historic downtown lofts and nearby hotels. Make a weekend of it in Logan County\'s most loveable town.',
            ],
            'plan-a-visit' => [
                'Plan a Visit to Downtown Bellefontaine, Ohio',
                'Plan your visit to Downtown Bellefontaine — sample itineraries, parking, lodging, and directions. Just about an hour from Columbus and Dayton, Ohio.',
            ],
            'map' => [
                'Downtown Bellefontaine Map — Shops, Dining & Parking',
                'Explore our interactive Downtown Bellefontaine map — find local shops, restaurants, lodging, parking, and things to do across the historic district.',
            ],
            'first-fridays' => [
                'Downtown Days in Downtown Bellefontaine, Ohio',
                'Downtown Days in Downtown Bellefontaine — monthly live music, extended shop and restaurant hours, DORA drinks, and family fun on the historic square.',
            ],
            'meeting-spaces' => [
                'Meeting & Event Spaces in Downtown Bellefontaine',
                'Book meeting and event spaces in Downtown Bellefontaine, Ohio — The Syndicate, Bella Vino, The Maxwell, Build Cowork, and more unique venues.',
            ],
            'dora' => [
                'DORA District in Downtown Bellefontaine, Ohio',
                'Downtown Bellefontaine\'s DORA — the Designated Outdoor Refreshment Area. Enjoy a drink as you stroll 46 acres of historic downtown during events.',
            ],
            'media' => [
                'Downtown Bellefontaine in the News — Press & Media',
                'Press coverage and media mentions of Downtown Bellefontaine, Ohio — see how Ohio\'s most loveable downtown is making headlines across the state.',
            ],
            'contact' => [
                'Contact the Downtown Bellefontaine Partnership',
                'Contact the Downtown Bellefontaine Partnership — questions about visiting, business listings, events, or getting involved in our downtown community.',
            ],
            'historic-walking-tour' => [
                'Historic Walking Tour of Downtown Bellefontaine',
                'Take a self-guided historic walking tour of Downtown Bellefontaine — 14 stops, bronze plaques, and the stories behind our historic brick storefronts.',
            ],
            'privacy-policy' => [
                'Privacy Policy | Downtown Bellefontaine',
                'How the Downtown Bellefontaine Partnership collects, uses, and protects your personal information.',
            ],
            'terms-of-service' => [
                'Terms of Service | Downtown Bellefontaine',
                'The terms for using the Downtown Bellefontaine website, including accounts and business listings.',
            ],
        ];
    }
}
