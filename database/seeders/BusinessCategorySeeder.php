<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = json_decode(file_get_contents(database_path('seeders/data/business_categories.json')), true);

        DB::table('business_categories')->insert($categories);
    }
}
