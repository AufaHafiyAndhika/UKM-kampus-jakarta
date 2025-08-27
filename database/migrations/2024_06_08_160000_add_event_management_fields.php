<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns to events table
        Schema::table('events', function (Blueprint $table) {
            // File uploads for ketua UKM
            $table->string('proposal_file')->nullable()->after('poster');
            $table->string('rab_file')->nullable()->after('proposal_file');
            $table->string('lpj_file')->nullable()->after('rab_file');
        });

        // Add missing columns to event_registrations table
        Schema::table('event_registrations', function (Blueprint $table) {
            // Availability form (JSON data)
            $table->json('availability_form')->nullable()->after('status');
            
            // Registration notes from student
            $table->text('registration_notes')->nullable()->after('availability_form');
            
            // Cancellation tracking
            $table->timestamp('cancelled_at')->nullable()->after('registration_notes');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['proposal_file', 'rab_file', 'lpj_file']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['availability_form', 'registration_notes', 'cancelled_at', 'cancellation_reason']);
        });
    }
};
