<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businesses = json_decode(file_get_contents(database_path('seeders/data/businesses.json')), true);

        foreach ($businesses as &$business) {
            $business['hours'] = isset($business['hours']) ? json_encode($business['hours']) : null;
            $business['social_links'] = isset($business['social_links']) ? json_encode($business['social_links']) : null;
        }

        foreach (array_chunk($businesses, 50) as $chunk) {
            DB::table('businesses')->insert($chunk);
        }

        // Seed pivot table
        $pivots = json_decode(file_get_contents(database_path('seeders/data/business_business_category.json')), true);

        foreach (array_chunk($pivots, 50) as $chunk) {
            DB::table('business_business_category')->insert($chunk);
        }

        // Seed locations
        $locations = json_decode(file_get_contents(database_path('seeders/data/business_locations.json')), true);

        foreach ($locations as &$location) {
            $location['hours'] = isset($location['hours']) ? (is_array($location['hours']) ? json_encode($location['hours']) : $location['hours']) : null;
        }

        foreach (array_chunk($locations, 50) as $chunk) {
            DB::table('business_locations')->insert($chunk);
        }
    }
}
