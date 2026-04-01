<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Authors
        $authors = json_decode(file_get_contents(database_path('seeders/data/authors.json')), true);
        DB::table('authors')->insert($authors);

        // Blog categories
        $categories = json_decode(file_get_contents(database_path('seeders/data/blog_categories.json')), true);
        DB::table('blog_categories')->insert($categories);

        // Blog posts
        $posts = json_decode(file_get_contents(database_path('seeders/data/blog_posts.json')), true);

        foreach (array_chunk($posts, 50) as $chunk) {
            DB::table('blog_posts')->insert($chunk);
        }

        // Blog post <-> business pivot
        $postBusiness = json_decode(file_get_contents(database_path('seeders/data/blog_post_business.json')), true);

        foreach (array_chunk($postBusiness, 100) as $chunk) {
            DB::table('blog_post_business')->insert($chunk);
        }

        // Blog post <-> business category pivot
        $postCategory = json_decode(file_get_contents(database_path('seeders/data/blog_post_business_category.json')), true);

        foreach (array_chunk($postCategory, 100) as $chunk) {
            DB::table('blog_post_business_category')->insert($chunk);
        }
    }
}
