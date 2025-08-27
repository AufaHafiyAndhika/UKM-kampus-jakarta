<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $ketuaUkmRole = Role::firstOrCreate(['name' => 'ketua_ukm']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create permissions
        $permissions = [
            // Student permissions
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
            
            // Ketua UKM permissions
            'manage_ukm',
            'edit_ukm',
            'create_event',
            'manage_ukm_members',
            'view_ukm_dashboard',
            
            // Admin permissions
            'manage_users',
            'manage_all_ukms',
            'manage_all_events',
            'view_admin_dashboard',
            'approve_registrations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $studentRole->givePermissionTo([
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
        ]);

        $ketuaUkmRole->givePermissionTo([
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
            'manage_ukm',
            'edit_ukm',
            'create_event',
            'manage_ukm_members',
            'view_ukm_dashboard',
        ]);

        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Created roles: student, ketua_ukm, admin');
    }
}
