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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukm_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('poster')->nullable();
            $table->json('gallery')->nullable(); // Array of image paths
            $table->enum('type', ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other']);
            $table->string('location');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->datetime('registration_start')->nullable();
            $table->datetime('registration_end')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->enum('status', ['waiting', 'published', 'ongoing', 'completed', 'cancelled'])->default('waiting');
            $table->boolean('requires_approval')->default(false);
            $table->boolean('certificate_available')->default(false);
            $table->string('certificate_template')->nullable();
            $table->json('contact_person')->nullable(); // {name, phone, email}
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
