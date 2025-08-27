<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@telkomuniversity.ac.id'],
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
            ]
        );

        // Create additional admin users if needed
        User::updateOrCreate(
            ['email' => 'superadmin@telkomuniversity.ac.id'],
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
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('1. Email: admin@telkomuniversity.ac.id | Password: admin123');
        $this->command->info('2. Email: superadmin@telkomuniversity.ac.id | Password: superadmin123');
    }
}
