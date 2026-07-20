<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path', 500);              // old path, no domain (e.g. /downtown-map)
            $table->string('to_url', 500)->nullable();      // target path/URL; null for 410 Gone
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->string('match_type', 20)->default('exact'); // exact | pattern (regex)
            $table->integer('priority')->default(0);        // pattern evaluation order (higher first)
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->string('notes')->nullable();            // group tag: pages/blog/events/tour/media
            $table->timestamps();

            $table->index(['from_path', 'is_active']);
            $table->index(['match_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
