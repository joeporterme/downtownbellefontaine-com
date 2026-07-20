<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('press_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('source')->nullable();   // publication / outlet
            $table->date('published_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'published_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('press_items');
    }
};
