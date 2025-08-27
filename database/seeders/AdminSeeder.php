<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // Create permissions
        $permissions = [
            'manage-users',
            'manage-ukms',
            'manage-events',
            'manage-certificates',
            'view-dashboard',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo($permissions);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@telkomuniversity.ac.id'],
            [
                'nim' => 'ADMIN001',
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'phone' => '081234567890',
                'gender' => 'male',
                'faculty' => 'Sistem Informasi',
                'major' => 'Sistem Informasi',
                'batch' => '2024',
                'status' => 'active',
                'role' => 'admin',
            ]
        );

        // Assign admin role
        $admin->assignRole('admin');

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@telkomuniversity.ac.id');
        $this->command->info('Password: admin123');
    }
}
