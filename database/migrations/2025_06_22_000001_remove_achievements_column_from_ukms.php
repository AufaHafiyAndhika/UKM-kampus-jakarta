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
            // Remove the achievements column as we now use the ukm_achievements table
            if (Schema::hasColumn('ukms', 'achievements')) {
                $table->dropColumn('achievements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ukms', function (Blueprint $table) {
            // Add back the achievements column if needed
            if (!Schema::hasColumn('ukms', 'achievements')) {
                $table->text('achievements')->nullable()->after('requirements')->comment('Prestasi UKM');
            }
        });
    }
};
