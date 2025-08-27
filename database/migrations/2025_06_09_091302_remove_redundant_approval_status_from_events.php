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
        Schema::table('events', function (Blueprint $table) {
            // Remove redundant approval_status column
            // We'll use only the main 'status' column with values:
            // draft, published, ongoing, completed, cancelled, rejected

            if (Schema::hasColumn('events', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Re-add approval_status column if needed
            if (!Schema::hasColumn('events', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('status');
            }
        });
    }
};
