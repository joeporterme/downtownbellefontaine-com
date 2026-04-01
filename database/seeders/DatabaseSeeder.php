<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            BusinessCategorySeeder::class,
            BusinessSeeder::class,
            EventSeeder::class,
            BlogSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
