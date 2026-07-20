<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // Transient: a Google Places photo URL chosen for the featured image;
            // the business observer downloads it into featured_image on save.
            $table->text('featured_places_url')->nullable()->after('featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('featured_places_url');
        });
    }
};
