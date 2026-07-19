<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // The published() scope (status + published_at) drives the home page,
            // blog index, sitemap, related posts, and feed. Businesses and events
            // already have equivalent indexes; blog_posts was missed.
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
        });
    }
};
