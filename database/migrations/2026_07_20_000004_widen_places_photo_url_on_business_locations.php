<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            // New Places API photo media URLs are ~900+ chars — too long for
            // the default VARCHAR(255).
            $table->text('places_photo_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('places_photo_url')->nullable()->change();
        });
    }
};
