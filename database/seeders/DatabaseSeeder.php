<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users first
        $this->call(AdminUserSeeder::class);

        // Create sample students
        User::factory(20)->create();

        // Create sample UKMs and events if needed
        // $this->call(UkmSeeder::class);
        // $this->call(EventSeeder::class);
    }
}
