<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SpatieRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $ketuaUkmRole = Role::firstOrCreate(['name' => 'ketua_ukm']);

        // Create permissions
        $permissions = [
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
        $studentRole->syncPermissions([
            'view_dashboard',
            'join_ukm',
            'register_event',
            'view_profile',
            'edit_profile',
        ]);

        $ketuaUkmRole->syncPermissions([
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

        $adminRole->syncPermissions(Permission::all());

        // Sync existing users with their roles
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->syncRoles([]);
                $user->assignRole($user->role);
            }
        }

        $this->command->info('Spatie roles and permissions created successfully!');
        $this->command->info('Admin roles: ' . $adminRole->users()->count());
        $this->command->info('Student roles: ' . $studentRole->users()->count());
        $this->command->info('Ketua UKM roles: ' . $ketuaUkmRole->users()->count());
    }
}
