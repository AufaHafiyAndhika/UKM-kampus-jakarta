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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('title'); // Certificate title
            $table->text('description')->nullable();
            $table->string('file_path'); // Path to generated PDF
            $table->datetime('issued_date');
            $table->foreignId('issued_by')->constrained('users')->onDelete('cascade');
            $table->string('verification_code')->unique(); // For certificate verification
            $table->boolean('is_verified')->default(true);
            $table->datetime('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();

            // Ensure unique certificate per user per event
            $table->unique(['user_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
