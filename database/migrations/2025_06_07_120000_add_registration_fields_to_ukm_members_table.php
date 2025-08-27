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
        Schema::table('ukm_members', function (Blueprint $table) {
            // Add registration form fields
            $table->text('previous_experience')->nullable()->after('status');
            $table->text('skills_interests')->nullable()->after('previous_experience');
            $table->text('reason_joining')->nullable()->after('skills_interests');
            $table->string('preferred_division')->nullable()->after('reason_joining');
            $table->string('cv_file')->nullable()->after('preferred_division');
            
            // Add timestamps for registration process
            $table->timestamp('applied_at')->nullable()->after('cv_file');
            $table->timestamp('approved_at')->nullable()->after('applied_at');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('rejected_at');
            
            // Add approved/rejected by
            $table->unsignedBigInteger('approved_by')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_by');
            
            // Foreign keys
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ukm_members', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            $table->dropColumn([
                'previous_experience',
                'skills_interests', 
                'reason_joining',
                'preferred_division',
                'cv_file',
                'applied_at',
                'approved_at',
                'rejected_at',
                'rejection_reason',
                'approved_by',
                'rejected_by'
            ]);
        });
    }
};
