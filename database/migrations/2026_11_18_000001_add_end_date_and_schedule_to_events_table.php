<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Multi-day events: last day of the run (null = single day).
            $table->date('end_date')->nullable()->after('event_date');
            // Plain-text hours for events whose times vary by day.
            $table->text('schedule')->nullable()->after('end_time');
            $table->index(['end_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['end_date', 'status']);
            $table->dropColumn(['end_date', 'schedule']);
        });
    }
};
