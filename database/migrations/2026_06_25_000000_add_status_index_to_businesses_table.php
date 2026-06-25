<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            // The public site filters businesses by status on nearly every query
            // (approved() scope), so index the column to speed those lookups up.
            $table->index('status', 'businesses_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex('businesses_status_index');
        });
    }
};
