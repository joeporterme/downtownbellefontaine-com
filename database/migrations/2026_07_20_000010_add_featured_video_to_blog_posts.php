<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add an optional featured video to blog posts. When set, the post hero
     * falls back to a default Downtown photo and the video plays in the article.
     * Mirrors `featured_image`: a relative path on the "public" disk.
     */
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('featured_video')->nullable()->after('featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('featured_video');
        });
    }
};
