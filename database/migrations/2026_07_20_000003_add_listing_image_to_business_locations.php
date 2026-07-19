<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            // Per-location curated override photo (manual upload or a downloaded
            // Google Places photo) that beats this location's Street View snapshot.
            $table->string('listing_image')->nullable()->after('streetview_image');
            $table->string('listing_image_credit')->nullable()->after('listing_image');
            // Transient: a Google Places photo URL chosen in the admin; the
            // observer downloads it into listing_image on save, then clears this.
            $table->string('places_photo_url')->nullable()->after('listing_image_credit');
        });
    }

    public function down(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->dropColumn(['listing_image', 'listing_image_credit', 'places_photo_url']);
        });
    }
};
