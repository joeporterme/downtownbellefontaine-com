<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_spam')->default(false)->after('status');
            $table->unsignedSmallInteger('spam_score')->default(0)->after('is_spam');
            $table->json('spam_reasons')->nullable()->after('spam_score');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['is_spam', 'spam_score', 'spam_reasons']);
        });
    }
};
