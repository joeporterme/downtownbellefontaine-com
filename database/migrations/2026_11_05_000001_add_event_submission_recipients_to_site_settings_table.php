<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Who gets an email when a business owner submits an event for
            // approval (list of emails). Empty falls back to the contact form
            // recipients.
            $table->json('event_submission_recipients')->nullable()->after('contact_form_recipients');
        });

        // Start with the same people who get contact form messages.
        DB::table('site_settings')
            ->whereNull('event_submission_recipients')
            ->update(['event_submission_recipients' => DB::raw('contact_form_recipients')]);
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('event_submission_recipients');
        });
    }
};
