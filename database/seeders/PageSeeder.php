<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Seed the CMS Page records for the existing site pages.
     * key = route name minus the "pages." prefix (or "home").
     * Bodies stay in Blade; these records drive SEO, hero, and publish status.
     */
    public function run(): void
    {
        $pages = [
            [
                'key' => 'home', 'title' => 'Home', 'nav_label' => 'Home', 'sort' => 0,
                'hero_eyebrow' => 'Welcome to', 'hero_heading' => 'Downtown Bellefontaine',
                'hero_subheading' => "Discover the heart of Logan County. Where small-town charm meets vibrant community spirit in Ohio's highest city.",
                'hero_image' => 'pages/heroes/downtown-bellefontaine-1.jpg',
                'seo_title' => 'Downtown Bellefontaine, Ohio — Shop, Dine, Stay & Explore',
                'seo_description' => 'Discover Downtown Bellefontaine, Ohio - your destination for local businesses, community events, and small-town charm in the heart of Logan County.',
            ],
            [
                'key' => 'places-to-shop', 'title' => 'Places to Shop', 'nav_label' => 'Shop', 'sort' => 10,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Places to Shop',
                'hero_image' => 'pages/heroes/shopping-1.jpg',
                'seo_description' => 'Shop local in Downtown Bellefontaine, Ohio - discover unique boutiques, antiques, toys, and specialty shops in our loveable small town.',
            ],
            [
                'key' => 'food-drinks', 'title' => 'Food & Drinks', 'nav_label' => 'Eat', 'sort' => 20,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Food & Drinks',
                'hero_image' => 'pages/heroes/bella-vino.jpg',
                'seo_description' => 'Discover the best food and drinks in Downtown Bellefontaine, Ohio - from award-winning pizza to craft beer, coffee, and sweet treats.',
            ],
            [
                'key' => 'stay', 'title' => 'Stay', 'nav_label' => 'Stay', 'sort' => 30,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Stay Awhile',
                'hero_subheading' => "One day isn't enough. Make a weekend of it with a stay in Logan County's most loveable small town.",
                'hero_image' => 'pages/heroes/downtown-bellefontaine-2.jpg',
                'seo_description' => 'Where to stay in and around Downtown Bellefontaine, Ohio - hotels, inns, and lodging for your visit to the most loveable downtown.',
            ],
            [
                'key' => 'things-to-do', 'title' => 'Things to Do', 'nav_label' => 'Play', 'sort' => 40,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Things to Do',
                'hero_subheading' => "Whether you're looking for shopping, eating, or exploring, Bellefontaine offers you the whole package.",
                'hero_image' => 'pages/heroes/mckinley-street.jpg',
                'seo_description' => 'Discover things to do in Downtown Bellefontaine, Ohio - from The Holland Theatre to Mad River Mountain, Indian Lake, and unique local experiences.',
            ],
            [
                'key' => 'plan-a-visit', 'title' => 'Plan a Visit', 'nav_label' => 'Plan a Visit', 'sort' => 50,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Plan a Visit',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-1.jpg',
                'seo_description' => 'Plan your visit to Downtown Bellefontaine, Ohio - sample itineraries, parking, lodging, and everything you need for a perfect day in town.',
            ],
            [
                'key' => 'map', 'title' => 'Interactive Map', 'nav_label' => 'Map', 'sort' => 60,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Interactive Map',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-3.jpg',
                'seo_description' => 'Explore Downtown Bellefontaine on our interactive map - find shops, restaurants, lodging, parking, and things to do.',
            ],
            [
                'key' => 'first-fridays', 'title' => 'First Fridays', 'nav_label' => 'First Fridays', 'sort' => 70,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'First Fridays',
                'hero_image' => 'pages/heroes/first-fridays.jpg',
                'seo_description' => 'Join us for First Fridays in Downtown Bellefontaine - monthly community events celebrating local shops, food, music, and entertainment.',
            ],
            [
                'key' => 'meeting-spaces', 'title' => 'Meeting Spaces', 'nav_label' => 'Meeting Spaces', 'sort' => 80,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Meeting Spaces',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-3.jpg',
                'seo_description' => 'Find the perfect meeting space in Downtown Bellefontaine - The Syndicate, Bella Vino, Build Cowork, and more unique venues for your event.',
            ],
            [
                'key' => 'dora', 'title' => 'DORA District', 'nav_label' => 'DORA', 'sort' => 90,
                'hero_eyebrow' => 'Designated Outdoor Refreshment Area', 'hero_heading' => 'DORA District',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-2.jpg',
                'seo_description' => 'Learn about Downtown Bellefontaine\'s DORA - the Designated Outdoor Refreshment Area where you can enjoy a drink while you stroll and shop.',
            ],
            [
                'key' => 'historic-walking-tour', 'title' => 'Historic Walking Tour', 'nav_label' => 'Historic Walking Tour', 'sort' => 100,
                'hero_eyebrow' => 'Downtown Bellefontaine', 'hero_heading' => 'Historic Walking Tour',
                'hero_image' => 'pages/heroes/mckinley-street.jpg',
                'seo_description' => 'Explore Downtown Bellefontaine on a self-guided historic walking tour - from America\'s shortest street to the Holland Theatre and beyond.',
            ],
            [
                'key' => 'media', 'title' => 'Media & Press', 'nav_label' => 'Media & Press', 'sort' => 110,
                'hero_eyebrow' => 'In the News', 'hero_heading' => 'Media & Press',
                'hero_subheading' => "Downtown Bellefontaine has been featured across Ohio and beyond. Here's a look at our coverage.",
                'hero_image' => 'pages/heroes/downtown-bellefontaine-2.jpg',
                'seo_description' => 'Downtown Bellefontaine in the news - read press coverage and media mentions about Ohio\'s most loveable downtown.',
            ],
            [
                'key' => 'contact', 'title' => 'Contact Us', 'nav_label' => 'Contact', 'sort' => 120,
                'hero_eyebrow' => "We'd Love to Hear From You", 'hero_heading' => 'Contact Us',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-1.jpg',
                'seo_description' => 'Get in touch with the Downtown Bellefontaine Partnership - questions about visiting, business listings, events, or partnerships.',
            ],
            [
                'key' => 'privacy-policy', 'title' => 'Privacy Policy', 'nav_label' => 'Privacy Policy', 'sort' => 130,
                'hero_eyebrow' => 'Legal', 'hero_heading' => 'Privacy Policy',
                'hero_subheading' => 'How we collect, use, and protect your information.',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-1.jpg',
                'seo_description' => 'Downtown Bellefontaine privacy policy - learn how we collect, use, and protect your personal information.',
            ],
            [
                'key' => 'terms-of-service', 'title' => 'Terms of Service', 'nav_label' => 'Terms of Service', 'sort' => 140,
                'hero_eyebrow' => 'Legal', 'hero_heading' => 'Terms of Service',
                'hero_subheading' => 'The terms for using the Downtown Bellefontaine website.',
                'hero_image' => 'pages/heroes/downtown-bellefontaine-1.jpg',
                'seo_description' => 'Terms of Service for the Downtown Bellefontaine website - the rules for using our site, accounts, and listings.',
            ],
        ];

        foreach ($pages as $data) {
            $data['status'] = 'published';
            $data['published_at'] = now();
            Page::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
