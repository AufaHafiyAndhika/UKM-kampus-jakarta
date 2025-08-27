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
            // Add achievements column if it doesn't exist
            if (!Schema::hasColumn('ukms', 'achievements')) {
                $table->text('achievements')->nullable()->after('requirements')->comment('Prestasi UKM');
            }
            
            // Add organization_structure column if it doesn't exist
            if (!Schema::hasColumn('ukms', 'organization_structure')) {
                $table->string('organization_structure')->nullable()->after('achievements')->comment('Gambar struktur organisasi');
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
            if (Schema::hasColumn('ukms', 'organization_structure')) {
                $table->dropColumn('organization_structure');
            }
            
            if (Schema::hasColumn('ukms', 'achievements')) {
                $table->dropColumn('achievements');
            }
        });
    }
};
