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
            // Add approval notes
            if (!Schema::hasColumn('events', 'approval_notes')) {
                $table->text('approval_notes')->nullable();
            }

            // Add rejection_reason if not exists
            if (!Schema::hasColumn('events', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }

            // Add rejected_by and rejected_at
            if (!Schema::hasColumn('events', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable();
                $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('events', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            // Add cancelled fields
            if (!Schema::hasColumn('events', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable();
                $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('events', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }

            if (!Schema::hasColumn('events', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $columns = [
                'cancellation_reason',
                'cancelled_at',
                'cancelled_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
                'approval_notes'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    if (in_array($column, ['cancelled_by', 'rejected_by'])) {
                        $table->dropForeign(['events_' . $column . '_foreign']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
