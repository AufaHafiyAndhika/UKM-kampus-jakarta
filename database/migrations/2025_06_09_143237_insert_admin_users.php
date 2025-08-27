<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert admin users
        DB::table('users')->insertOrIgnore([
            [
                'nim' => 'ADMIN001',
                'name' => 'Administrator',
                'email' => 'admin@telkomuniversity.ac.id',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'gender' => 'male',
                'faculty' => 'Administrasi',
                'major' => 'Sistem Informasi',
                'batch' => '2024',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => 'ADMIN002',
                'name' => 'Super Administrator',
                'email' => 'superadmin@telkomuniversity.ac.id',
                'password' => Hash::make('superadmin123'),
                'phone' => '081234567891',
                'gender' => 'female',
                'faculty' => 'Administrasi',
                'major' => 'Manajemen',
                'batch' => '2024',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210001',
                'name' => 'John Doe',
                'email' => 'student@telkomuniversity.ac.id',
                'password' => Hash::make('student123'),
                'phone' => '081234567892',
                'gender' => 'male',
                'faculty' => 'Informatika',
                'major' => 'Teknik Informatika',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1103210002',
                'name' => 'Jane Smith',
                'email' => 'ketua@telkomuniversity.ac.id',
                'password' => Hash::make('ketua123'),
                'phone' => '081234567893',
                'gender' => 'female',
                'faculty' => 'Informatika',
                'major' => 'Sistem Informasi',
                'batch' => '2021',
                'role' => 'ketua_ukm',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'admin@telkomuniversity.ac.id',
            'superadmin@telkomuniversity.ac.id',
            'student@telkomuniversity.ac.id',
            'ketua@telkomuniversity.ac.id'
        ])->delete();
    }
};
