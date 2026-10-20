<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\ScavengerClue;
use App\Models\SignatureEvent;
use Illuminate\Database\Seeder;

/**
 * Starter content for the Visitors Guide, carried over from the 2023 printed
 * guide. Safe to run on prod: firstOrCreate only, so it never overwrites
 * anything the partnership has since edited in the admin.
 */
class VisitorsGuideSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(['key' => 'visitors-guide'], [
            'title' => 'Visitors Guide',
            'nav_label' => 'Visitors Guide',
            'sort' => 75,
            'seo_title' => 'Downtown Bellefontaine Visitors Guide | Map, Events & Itineraries',
            'seo_description' => 'Your guide to exploring Downtown Bellefontaine like a local — an interactive map of every shop and restaurant, the annual events calendar, day itineraries, and a printable PDF.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        foreach ($this->signatureEvents() as $i => [$month, $name, $blurb]) {
            SignatureEvent::firstOrCreate(['name' => $name], [
                'month' => $month,
                'blurb' => $blurb,
                'url' => '/downtown-days',
                'sort' => $i,
                'is_active' => true,
            ]);
        }

        foreach ($this->clues() as $i => $clue) {
            ScavengerClue::firstOrCreate(['clue' => $clue], [
                'sort' => $i + 1,
                'is_active' => true,
            ]);
        }
    }

    /** @return array<int, array{0:int,1:string,2:string}> */
    protected function signatureEvents(): array
    {
        return [
            [1, 'Barbell Crawl', 'Kick off the new year with a friendly fitness crawl through downtown gyms and studios.'],
            [2, 'Chocolate Walk', 'Stroll the square sampling sweet treats from downtown shops. Bring a sweetheart (or don\'t share).'],
            [3, 'Spring Shop Hop', 'Shake off winter and hop from boutique to boutique for spring deals and fresh finds.'],
            [4, 'Easter Egg-Stravaganza', 'Egg hunts, the Easter Bunny, and family fun all over downtown.'],
            [5, 'Brew Fest', 'Raise a glass to local and regional craft beer right in the heart of downtown.'],
            [6, 'Pineapple Palooza', 'Our favorite fruit gets its own party. Pineapple everything, all over the square.'],
            [7, 'Taste of Bellefontaine', 'Graze your way through downtown\'s kitchens in one delicious evening.'],
            [8, 'Art & Wine Walk', 'Local artists, live music, and a glass of something nice as you wander the shops.'],
            [9, 'Back to School Bash', 'Send the kids back to school in style with games, giveaways, and downtown fun.'],
            [10, 'Pumpkin Walk', 'Hundreds of carved pumpkins light up downtown, plus the pumpkin barrel scavenger hunt.'],
            [11, 'Holiday Open House', 'Ohio\'s Christmas Capital kicks off the season with festive shopping all over downtown.'],
            [12, 'Christmas Parade', 'Floats, lights, and Santa himself roll down Main Street. A Bellefontaine tradition.'],
        ];
    }

    /** @return array<int, string> */
    protected function clues(): array
    {
        return [
            'At this location, you can vote for the President of the USA or any elected official.',
            'This 3-story yellow brick building with Roman columns has been home to many businesses since 1912. One of them was Dee\'s Department Store.',
            'This Lieutenant Governor of Ohio lived at this location while a resident of Bellefontaine.',
            'This little street is named after someone we know.',
            'This building was built in 1894, and there are 2 soldiers guarding the front entry.',
            'This beautiful church has a bell tower. It was known by a different name from 1825 until 1907.',
            'This symbol of Downtown Bellefontaine has a piece of fruit on top.',
            'This federal building, built in 1914, is across the street from its newer replacement.',
        ];
    }
}
