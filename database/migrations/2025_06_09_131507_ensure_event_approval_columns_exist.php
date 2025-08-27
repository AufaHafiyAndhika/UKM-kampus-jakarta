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
            // Ensure approved_by column exists
            if (!Schema::hasColumn('events', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }

            // Ensure approved_at column exists
            if (!Schema::hasColumn('events', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }

            // Ensure approval_notes column exists
            if (!Schema::hasColumn('events', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop columns if they exist
            $columns = ['approval_notes', 'approved_at', 'approved_by'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    if ($column === 'approved_by') {
                        $table->dropForeign(['approved_by']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
