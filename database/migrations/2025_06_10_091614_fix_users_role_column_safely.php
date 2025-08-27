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
        // Check if role column exists, if not add it
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'student', 'ketua_ukm'])->default('student')->after('email');
            });
        } else {
            // If column exists but might have wrong enum values, update it safely
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'ketua_ukm') DEFAULT 'student'");
        }

        // Ensure all users have proper roles assigned
        $this->assignRoles();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the column to prevent data loss
        // Just revert to simpler enum if needed
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student') DEFAULT 'student'");
        }
    }

    /**
     * Assign proper roles to users based on email patterns
     */
    private function assignRoles(): void
    {
        // Set admin role for admin emails
        DB::table('users')
            ->where('email', 'like', '%admin%')
            ->orWhereIn('email', [
                'admin@telkomuniversity.ac.id',
                'superadmin@telkomuniversity.ac.id',
                'adminbaru@telkomuniversity.ac.id'
            ])
            ->update(['role' => 'admin']);

        // Set ketua_ukm role for ketua emails
        DB::table('users')
            ->where('email', 'like', '%ketua%')
            ->orWhereIn('email', [
                'ketua@telkomuniversity.ac.id',
                'ketuabaru@telkomuniversity.ac.id'
            ])
            ->update(['role' => 'ketua_ukm']);

        // Ensure remaining users are students
        DB::table('users')
            ->whereNotIn('role', ['admin', 'ketua_ukm'])
            ->orWhereNull('role')
            ->update(['role' => 'student']);
    }
};
