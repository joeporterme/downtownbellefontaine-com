<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Who gets contact form notifications (list of emails). Empty falls
            // back to CONTACT_FORM_RECIPIENT in the environment.
            $table->json('contact_form_recipients')->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('contact_form_recipients');
        });
    }
};
