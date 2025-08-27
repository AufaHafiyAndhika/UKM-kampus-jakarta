<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FixRoleEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:role-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the role enum to support ketua_ukm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== FIXING ROLE ENUM ===');

        try {
            $this->info('1. Checking current enum...');
            $result = DB::select("SHOW COLUMNS FROM users LIKE 'role'");
            if (!empty($result)) {
                $this->line("   Current: " . $result[0]->Type);
            }

            $this->info('2. Updating enum to support ketua_ukm...');
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'ketua_ukm', 'admin') DEFAULT 'student'");
            $this->info('   ✅ Enum updated!');

            $this->info('3. Verifying change...');
            $result = DB::select("SHOW COLUMNS FROM users LIKE 'role'");
            if (!empty($result)) {
                $this->line("   New: " . $result[0]->Type);
            }

            $this->info('4. Testing role assignment...');
            $user = User::where('role', 'student')->first();
            if ($user) {
                $this->line("   Testing with: " . $user->name);
                $user->update(['role' => 'ketua_ukm']);
                $user->refresh();
                $this->line("   New role: " . $user->role);

                if ($user->role === 'ketua_ukm') {
                    $this->info('   ✅ SUCCESS!');
                    $user->update(['role' => 'student']);
                    $this->line('   Reverted to student');
                } else {
                    $this->error('   ❌ FAILED!');
                }
            }

            $this->info('');
            $this->info('=== COMPLETED ===');
            $this->info('Role enum now supports: student, ketua_ukm, admin');

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
