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
        Schema::create('ukms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->enum('category', [
                'academic', 'sports', 'arts', 'religion', 
                'social', 'technology', 'entrepreneurship', 'other'
            ]);
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->json('contact_info')->nullable(); // {email, phone, instagram, etc}
            $table->text('meeting_schedule')->nullable();
            $table->string('meeting_location')->nullable();
            $table->integer('max_members')->default(100);
            $table->integer('current_members')->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->boolean('is_recruiting')->default(true);
            $table->date('established_date')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ukms');
    }
};
