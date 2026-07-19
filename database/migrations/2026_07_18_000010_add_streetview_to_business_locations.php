<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->string('streetview_pano_id')->nullable()->after('longitude');
            $table->decimal('streetview_heading', 8, 4)->nullable()->after('streetview_pano_id');
            $table->decimal('streetview_pitch', 6, 3)->nullable()->after('streetview_heading');
            $table->decimal('streetview_zoom', 4, 2)->nullable()->after('streetview_pitch');
            $table->string('streetview_image')->nullable()->after('streetview_zoom'); // cached snapshot path (public disk)
        });
    }

    public function down(): void
    {
        Schema::table('business_locations', function (Blueprint $table) {
            $table->dropColumn([
                'streetview_pano_id',
                'streetview_heading',
                'streetview_pitch',
                'streetview_zoom',
                'streetview_image',
            ]);
        });
    }
};
