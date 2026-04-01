<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for business-category relationship (many-to-many)
        Schema::create('business_business_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['business_id', 'business_category_id'], 'business_category_pivot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_business_category');
        Schema::dropIfExists('business_categories');
    }
};
