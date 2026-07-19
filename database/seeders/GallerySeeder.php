<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            ['gallery/courthouse-sunset.jpg', 'The Logan County Courthouse & pineapple fountain at sunset'],
            ['gallery/downtown-aerial.jpg', 'Downtown Bellefontaine from above'],
            ['gallery/empire-block-day.jpg', 'The historic Empire Block on Court Avenue'],
            ['gallery/courthouse-holland-night.jpg', 'Downtown lights & the Holland Theatre'],
            ['gallery/courthouse-fountain.jpg', 'Courthouse Square & the fountain'],
            ['gallery/downtown-dusk.jpg', 'Downtown at dusk'],
            ['gallery/courthouse-holland-day.jpg', 'The courthouse tower over downtown'],
            ['gallery/downtown-1.jpg', 'Downtown Bellefontaine'],
            ['gallery/downtown-2.jpg', 'Historic downtown streets'],
            ['gallery/downtown-3.jpg', 'Downtown after dark'],
            ['gallery/shopping.jpg', 'Shopping local downtown'],
            ['gallery/pizza.jpg', 'Award-winning eats'],
            ['gallery/custard.jpg', "Sweet treats at Whit's"],
            ['gallery/bella-vino.jpg', 'Bella Vino Events & Wine Room'],
            ['gallery/transportation-museum.jpg', 'The Transportation Museum'],
            ['gallery/first-fridays.jpg', 'First Fridays downtown'],
            ['gallery/mckinley-street.jpg', "McKinley Street — America's shortest street"],
        ];

        foreach ($images as $i => [$path, $caption]) {
            GalleryImage::updateOrCreate(
                ['image' => $path],
                ['caption' => $caption, 'sort' => $i * 10, 'is_active' => true],
            );
        }
    }
}
