<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = json_decode(file_get_contents(database_path('seeders/data/events.json')), true);

        DB::table('events')->insert($events);
    }
}
