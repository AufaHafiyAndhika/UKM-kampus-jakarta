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
        Schema::table('ukms', function (Blueprint $table) {
            // Add registration_status column if it doesn't exist
            if (!Schema::hasColumn('ukms', 'registration_status')) {
                $table->enum('registration_status', ['open', 'closed'])->default('open')->after('status');
            }

            // Add requirements column if it doesn't exist
            if (!Schema::hasColumn('ukms', 'requirements')) {
                $table->text('requirements')->nullable()->after('meeting_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ukms', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('ukms', 'registration_status')) {
                $table->dropColumn('registration_status');
            }

            if (Schema::hasColumn('ukms', 'requirements')) {
                $table->dropColumn('requirements');
            }
        });
    }
};
