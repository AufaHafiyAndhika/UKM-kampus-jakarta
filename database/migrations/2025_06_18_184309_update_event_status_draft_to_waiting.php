<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'draft' status to 'waiting'
        DB::table('events')
            ->where('status', 'draft')
            ->update(['status' => 'waiting']);

        // Modify the enum to replace 'draft' with 'waiting'
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('waiting', 'published', 'ongoing', 'completed', 'cancelled') DEFAULT 'waiting'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing 'waiting' status back to 'draft'
        DB::table('events')
            ->where('status', 'waiting')
            ->update(['status' => 'draft']);

        // Modify the enum to replace 'waiting' with 'draft'
        DB::statement("ALTER TABLE events MODIFY COLUMN status ENUM('draft', 'published', 'ongoing', 'completed', 'cancelled') DEFAULT 'draft'");
    }
};
