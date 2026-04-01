<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = json_decode(file_get_contents(database_path('seeders/data/users.json')), true);

        foreach (array_chunk($users, 50) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }
}
