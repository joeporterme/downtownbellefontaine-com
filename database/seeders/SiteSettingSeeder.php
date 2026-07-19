<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = SiteSetting::query()->firstOrCreate([]);

        $settings->update([
            'site_name' => 'Downtown Bellefontaine',
            'tagline' => "Ohio's most loveable downtown",
            'default_meta_description' => 'Discover Downtown Bellefontaine, Ohio - local businesses, community events, and small-town charm in the heart of Logan County.',
            'default_og_image' => '/images/home/downtown-bellefontaine-1.jpg',
            'contact_email' => 'info@downtownbellefontaine.com',
            'contact_phone' => '937-441-2681',
            'city' => 'Bellefontaine',
            'state' => 'OH',
            'facebook_url' => 'https://www.facebook.com/DowntownBellefontaine',
            'instagram_url' => 'https://www.instagram.com/downtownbellefontaine',
            'x_url' => 'https://twitter.com/dtbellefontaine',
            // tiktok_url / youtube_url / google_analytics_id left blank until provided
        ]);
    }
}
