<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ukm;
use App\Models\UkmMember;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UkmAndStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Creating UKMs...');
        
        // UKM data
        $ukms = [
            ['name' => 'IMMA', 'category' => 'keagamaan', 'description' => 'Ikatan Muslim Muslimah - Unit Kegiatan Mahasiswa yang menaungi kegiatan keagamaan Islam'],
            ['name' => 'Basket', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Basket - Mengembangkan bakat olahraga basket'],
            ['name' => 'Futsal', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Futsal - Mengembangkan prestasi futsal mahasiswa'],
            ['name' => 'Badminton', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Badminton - Mengembangkan bakat badminton'],
            ['name' => 'Teknologi Informasi', 'category' => 'akademik', 'description' => 'UKM yang mengembangkan kemampuan teknologi informasi'],
            ['name' => 'DKV', 'category' => 'seni', 'description' => 'Desain Komunikasi Visual - Mengembangkan kreativitas desain'],
            ['name' => 'Taekwondo', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Taekwondo - Seni bela diri'],
            ['name' => 'Teknik Telekomunikasi', 'category' => 'akademik', 'description' => 'UKM pengembangan teknologi telekomunikasi'],
            ['name' => 'KMH', 'category' => 'keagamaan', 'description' => 'Keluarga Mahasiswa Hindu - Kegiatan keagamaan Hindu'],
            ['name' => 'PMK', 'category' => 'keagamaan', 'description' => 'Persekutuan Mahasiswa Kristen - Kegiatan keagamaan Kristen'],
            ['name' => 'Kesenian dan Budaya', 'category' => 'seni', 'description' => 'UKM pelestarian seni dan budaya Indonesia'],
            ['name' => 'Bulu Tangkis', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Bulu Tangkis'],
            ['name' => 'E-Sport', 'category' => 'teknologi', 'description' => 'Unit Kegiatan Mahasiswa Electronic Sport'],
            ['name' => 'Pecinta Alam', 'category' => 'lingkungan', 'description' => 'UKM yang mengembangkan kecintaan terhadap alam'],
            ['name' => 'Language Club', 'category' => 'akademik', 'description' => 'UKM pengembangan kemampuan bahasa asing']
        ];

        foreach ($ukms as $ukmData) {
            $ukm = Ukm::firstOrCreate(
                ['name' => $ukmData['name']],
                [
                    'category' => $ukmData['category'],
                    'description' => $ukmData['description'],
                    'vision' => "Menjadi UKM {$ukmData['name']} terdepan di universitas",
                    'mission' => "Mengembangkan potensi mahasiswa di bidang {$ukmData['category']}",
                    'status' => 'active',
                    'current_members' => 0,
                    'is_recruiting' => true,
                    'max_members' => 100,
                ]
            );
            
            $this->command->info("✅ Created UKM: {$ukm->name}");
        }

        $this->command->info('👥 Creating 100 students...');

        // Indonesian names
        $firstNames = [
            'Andi', 'Budi', 'Citra', 'Dian', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko',
            'Kartika', 'Lina', 'Maya', 'Nanda', 'Oka', 'Putri', 'Qori', 'Rina', 'Sari', 'Tari',
            'Umar', 'Vina', 'Wati', 'Xena', 'Yudi', 'Zara', 'Agus', 'Bayu', 'Candra', 'Dewi',
            'Eko', 'Fitri', 'Galih', 'Hana', 'Irfan', 'Jihan', 'Kiki', 'Luki', 'Mira', 'Nisa',
            'Oki', 'Prita', 'Qila', 'Reza', 'Sinta', 'Tono', 'Ulfa', 'Vira', 'Wawan', 'Yani'
        ];

        $lastNames = [
            'Pratama', 'Sari', 'Wijaya', 'Putri', 'Santoso', 'Lestari', 'Kurniawan', 'Dewi',
            'Setiawan', 'Maharani', 'Nugroho', 'Anggraini', 'Permana', 'Safitri', 'Hidayat',
            'Rahayu', 'Gunawan', 'Wulandari', 'Susanto', 'Pertiwi', 'Rahman', 'Kusuma',
            'Handoko', 'Novita', 'Irawan', 'Salsabila', 'Firmansyah', 'Aulia', 'Saputra',
            'Melati', 'Hakim', 'Cahaya', 'Wardana', 'Indah', 'Ramadhan', 'Kartini'
        ];

        $faculties = ['Teknik', 'Ekonomi', 'Hukum', 'MIPA', 'Sosial Politik', 'Psikologi'];
        $majors = [
            'Teknik Informatika', 'Teknik Elektro', 'Teknik Sipil', 'Manajemen', 'Akuntansi',
            'Hukum', 'Matematika', 'Fisika', 'Kimia', 'Ilmu Komunikasi', 'Psikologi'
        ];

        for ($i = 1; $i <= 100; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;

            $email = strtolower($firstName . '.' . $lastName . $i) . '@student.telkomuniversity.ac.id';
            $nim = '1102' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $faculty = $faculties[array_rand($faculties)];
            $major = $majors[array_rand($majors)];

            // Create student if not exists
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => 'active',
                    'nim' => $nim,
                    'faculty' => $faculty,
                    'major' => $major,
                    'phone' => '08' . rand(1000000000, 9999999999),
                ]
            );

            if ($i % 20 == 0) {
                $this->command->info("✅ Created $i students...");
            }

            // Randomly assign to UKMs (20% chance per UKM)
            $allUkms = Ukm::where('status', 'active')->get();
            
            foreach ($allUkms as $ukm) {
                if (rand(1, 100) <= 20) { // 20% chance
                    // Check if already member
                    $existingMember = UkmMember::where('user_id', $user->id)
                                              ->where('ukm_id', $ukm->id)
                                              ->first();
                    
                    if (!$existingMember) {
                        UkmMember::create([
                            'user_id' => $user->id,
                            'ukm_id' => $ukm->id,
                            'status' => 'active',
                            'joined_date' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('📊 Updating UKM member counts...');

        // Update UKM member counts
        $allUkms = Ukm::where('status', 'active')->get();
        foreach ($allUkms as $ukm) {
            $ukm->updateMemberCount();
        }

        $this->command->info('✅ Database population completed!');
        
        // Show statistics
        $ukmCount = Ukm::where('status', 'active')->count();
        $studentCount = User::where('role', 'student')->where('status', 'active')->count();
        $membershipCount = UkmMember::where('status', 'active')->count();
        
        $this->command->info("📈 Statistics:");
        $this->command->info("📋 Total UKMs: $ukmCount");
        $this->command->info("👥 Total Students: $studentCount");
        $this->command->info("🤝 Total Memberships: $membershipCount");
        
        $this->command->info('🌐 Homepage will now show updated data!');
    }
}
