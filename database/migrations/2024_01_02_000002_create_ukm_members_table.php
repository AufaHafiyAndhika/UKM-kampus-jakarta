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
        Schema::create('ukm_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ukm_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['member', 'coordinator', 'vice_leader', 'leader'])->default('member');
            $table->enum('status', ['pending', 'active', 'inactive', 'alumni'])->default('pending');
            $table->date('joined_date')->nullable();
            $table->date('left_date')->nullable();
            $table->text('reason_for_leaving')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique membership per UKM
            $table->unique(['user_id', 'ukm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukm_members');
    }
};
