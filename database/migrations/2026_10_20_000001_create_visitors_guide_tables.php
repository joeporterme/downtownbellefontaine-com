<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The Visitors Guide's 12-month calendar. Dated events can't express
        // "every February", so the guide keeps its own small annual list.
        Schema::create('signature_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('month');           // 1-12
            $table->string('timing_note')->nullable();      // e.g. "First Friday in June"
            $table->string('blurb', 500)->nullable();
            $table->string('url')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'month']);
        });

        Schema::create('scavenger_clues', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->default(0);
            $table->string('clue', 500);
            $table->string('answer')->nullable();            // hidden behind "reveal" on the web
            $table->foreignId('business_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scavenger_clues');
        Schema::dropIfExists('signature_events');
    }
};
