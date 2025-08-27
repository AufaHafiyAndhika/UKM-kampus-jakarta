<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'nim' => '1301210001',
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@student.telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'gender' => 'male',
                'faculty' => 'Fakultas Informatika',
                'major' => 'Sistem Informasi',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'active',
            ],
            [
                'nim' => '1301210002',
                'name' => 'Sari Melati Putri',
                'email' => 'sari.melati@student.telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'gender' => 'female',
                'faculty' => 'Fakultas Informatika',
                'major' => 'Teknik Informatika',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'active',
            ],
            [
                'nim' => '1301210003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'gender' => 'male',
                'faculty' => 'Fakultas Teknik Elektro',
                'major' => 'Teknik Elektro',
                'batch' => '2021',
                'role' => 'student',
                'status' => 'active',
            ],
            [
                'nim' => '1301220001',
                'name' => 'Maya Sari Dewi',
                'email' => 'maya.sari@student.telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'gender' => 'female',
                'faculty' => 'Fakultas Komunikasi dan Bisnis',
                'major' => 'Manajemen',
                'batch' => '2022',
                'role' => 'student',
                'status' => 'active',
            ],
            [
                'nim' => '1301220002',
                'name' => 'Reza Pratama Wijaya',
                'email' => 'reza.pratama@student.telkomuniversity.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'gender' => 'male',
                'faculty' => 'Fakultas Informatika',
                'major' => 'Teknik Informatika',
                'batch' => '2022',
                'role' => 'student',
                'status' => 'active',
            ],
        ];

        foreach ($students as $studentData) {
            User::create($studentData);
        }

        $this->command->info('Student seeder completed successfully!');
        $this->command->info('Created ' . count($students) . ' student users');
    }
}
