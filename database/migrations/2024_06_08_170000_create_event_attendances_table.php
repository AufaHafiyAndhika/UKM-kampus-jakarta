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
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            
            // Attendance data
            $table->enum('status', ['present', 'absent', 'pending'])->default('pending');
            $table->string('proof_file')->nullable(); // Upload bukti kehadiran
            $table->text('notes')->nullable(); // Catatan mahasiswa
            $table->timestamp('submitted_at')->nullable();
            
            // Verification by admin/ketua
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Certificate
            $table->boolean('certificate_generated')->default(false);
            $table->string('certificate_file')->nullable();
            $table->timestamp('certificate_downloaded_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'status']);
            $table->index(['verification_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
