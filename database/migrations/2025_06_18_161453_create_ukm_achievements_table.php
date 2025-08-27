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
        Schema::create('ukm_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukm_id')->constrained('ukms')->onDelete('cascade');
            $table->string('title'); // Nama prestasi
            $table->text('description')->nullable(); // Deskripsi prestasi
            $table->enum('type', ['competition', 'award', 'certification', 'recognition', 'other'])->default('competition'); // Jenis prestasi
            $table->enum('level', ['local', 'regional', 'national', 'international'])->default('local'); // Tingkat prestasi
            $table->string('organizer')->nullable(); // Penyelenggara
            $table->date('achievement_date'); // Tanggal prestasi diraih
            $table->year('year'); // Tahun prestasi
            $table->string('certificate_file')->nullable(); // File sertifikat/bukti
            $table->text('participants')->nullable(); // Peserta yang terlibat
            $table->integer('position')->nullable(); // Posisi/ranking (1 untuk juara 1, dst)
            $table->boolean('is_featured')->default(false); // Apakah ditampilkan di homepage
            $table->timestamps();

            // Index untuk performa
            $table->index(['ukm_id', 'year']);
            $table->index(['is_featured', 'achievement_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukm_achievements');
    }
};
