<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');

        try {
            // Check if admin already exists
            $existingAdmin = User::where('email', 'admin@telkomuniversity.ac.id')->first();

            if ($existingAdmin) {
                $this->warn('Admin user already exists!');
                $this->info("Email: {$existingAdmin->email}");
                $this->info("Name: {$existingAdmin->name}");
                $this->info("Role: {$existingAdmin->role}");
                $this->info("Status: {$existingAdmin->status}");

                if ($this->confirm('Do you want to reset the password?')) {
                    $existingAdmin->update([
                        'password' => Hash::make('admin123'),
                        'status' => 'active',
                    ]);
                    $this->info('Password reset to: admin123');
                    $this->info('Status set to: active');
                }
            } else {
                // Create new admin
                $admin = User::create([
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
                ]);

                $this->info('Admin user created successfully!');
                $this->info("Email: {$admin->email}");
                $this->info("Name: {$admin->name}");
                $this->info("Role: {$admin->role}");
                $this->info("Status: {$admin->status}");
            }

            $this->newLine();
            $this->info('🔑 LOGIN CREDENTIALS:');
            $this->info('📧 Email: admin@telkomuniversity.ac.id');
            $this->info('🔒 Password: admin123');
            $this->info('👤 Role: admin');
            $this->info('✅ Status: active');
            $this->newLine();
            $this->info('🌐 Login URL: http://localhost:8000/login');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error creating admin user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
