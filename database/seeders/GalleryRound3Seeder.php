<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

/**
 * Oct 2026 photo drop: Downtown Days event photos + vintage postcards for the
 * sitewide photo ribbon (adds people and history to a mostly-buildings feed).
 *
 * Additive only: firstOrCreate on the image path, never touches existing rows.
 * Files are committed under public/images/gallery/round3/.
 *
 *   php artisan db:seed --class=GalleryRound3Seeder --force
 */
class GalleryRound3Seeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'festival-crowd-main-street' => 'A festival crowd on Main Street',
            'car-show-main-street' => 'The downtown car show',
            'christmas-parade-aerial' => 'The Christmas parade from above',
            'santa-float-opera-block' => 'Santa rolls past the Opera Block',
            'lighted-parade-night' => 'The lighted Christmas parade',
            'giant-snow-globe' => 'Inside the giant snow globe',
            'ice-skating-downtown' => 'Ice skating downtown',
            'courthouse-carolers' => 'Carolers on the courthouse steps',
            'kids-train-parade' => 'The kids\' train in the parade',
            'zipline-courthouse' => 'Ziplining past the courthouse',
            'live-music-four-acre-stage' => 'Live music on Main Street',
            'stage-on-main' => 'A concert on Main Street',
            'bounce-house-street-party' => 'A street party for the kids',
            'fountain-square-crowd' => 'A full house at the fountain square',
            'fountain-square-flags' => 'The fountain square in summer',
            'easter-bunny-courthouse' => 'Photos with the Easter Bunny',
            'pineapple-palooza-friends' => 'Pineapple Palooza',
            'pineapple-palooza-night-stage' => 'Pineapple Palooza after dark',
            'postcard-birds-eye' => 'Bird\'s-eye view of downtown, vintage postcard',
            'postcard-main-street-north' => 'Main Street looking north, vintage postcard',
            'postcard-carnegie-library' => 'The Carnegie Library, vintage postcard',
            'postcard-me-church' => 'M.E. Church, vintage postcard',
            'postcard-hotel-ingalls' => 'Hotel Ingalls, vintage postcard',
            'postcard-fire-station' => 'The fire station, vintage postcard',
        ];

        $sort = (int) GalleryImage::max('sort');

        foreach ($images as $file => $caption) {
            $sort += 10;
            GalleryImage::firstOrCreate(
                ['image' => "/images/gallery/round3/{$file}.jpg"],
                ['caption' => $caption, 'sort' => $sort, 'is_active' => true],
            );
        }
    }
}
