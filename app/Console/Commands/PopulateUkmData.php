<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Ukm;
use App\Models\UkmMember;
use Illuminate\Support\Facades\Hash;

class PopulateUkmData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:ukm-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate database with UKM and student data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏢 Creating UKMs...');

        // UKM data
        $ukms = [
            ['name' => 'IMMA', 'category' => 'keagamaan', 'description' => 'Ikatan Muslim Muslimah'],
            ['name' => 'Basket', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Basket'],
            ['name' => 'Futsal', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Futsal'],
            ['name' => 'Badminton', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Badminton'],
            ['name' => 'Teknologi Informasi', 'category' => 'akademik', 'description' => 'UKM Teknologi Informasi'],
            ['name' => 'DKV', 'category' => 'seni', 'description' => 'Desain Komunikasi Visual'],
            ['name' => 'Taekwondo', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Taekwondo'],
            ['name' => 'Teknik Telekomunikasi', 'category' => 'akademik', 'description' => 'UKM Teknik Telekomunikasi'],
            ['name' => 'KMH', 'category' => 'keagamaan', 'description' => 'Keluarga Mahasiswa Hindu'],
            ['name' => 'PMK', 'category' => 'keagamaan', 'description' => 'Persekutuan Mahasiswa Kristen'],
            ['name' => 'Kesenian dan Budaya', 'category' => 'seni', 'description' => 'UKM Kesenian dan Budaya'],
            ['name' => 'Bulu Tangkis', 'category' => 'olahraga', 'description' => 'Unit Kegiatan Mahasiswa Bulu Tangkis'],
            ['name' => 'E-Sport', 'category' => 'teknologi', 'description' => 'Unit Kegiatan Mahasiswa E-Sport'],
            ['name' => 'Pecinta Alam', 'category' => 'lingkungan', 'description' => 'UKM Pecinta Alam'],
            ['name' => 'Language Club', 'category' => 'akademik', 'description' => 'UKM Language Club']
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

            $this->info("✅ Created UKM: {$ukm->name}");
        }

        $this->info('👥 Creating students...');

        // Create 50 students
        $names = [
            'Andi Pratama', 'Budi Santoso', 'Citra Dewi', 'Dian Lestari', 'Eka Kurniawan',
            'Fajar Hidayat', 'Gita Maharani', 'Hadi Gunawan', 'Indra Permana', 'Joko Susanto',
            'Kartika Sari', 'Lina Wulandari', 'Maya Safitri', 'Nanda Pertiwi', 'Oka Rahman',
            'Putri Anggraini', 'Qori Kusuma', 'Rina Handoko', 'Sari Novita', 'Tari Irawan',
            'Umar Firmansyah', 'Vina Aulia', 'Wawan Saputra', 'Xena Melati', 'Yudi Hakim',
            'Zara Cahaya', 'Agus Wardana', 'Bayu Indah', 'Candra Ramadhan', 'Dewi Kartini',
            'Eko Wijaya', 'Fitri Putri', 'Galih Setiawan', 'Hana Maharani', 'Irfan Nugroho',
            'Jihan Anggraini', 'Kiki Permana', 'Luki Safitri', 'Mira Rahayu', 'Nisa Gunawan',
            'Oki Wulandari', 'Prita Susanto', 'Qila Pertiwi', 'Reza Rahman', 'Sinta Kusuma',
            'Tono Handoko', 'Ulfa Novita', 'Vira Irawan', 'Wati Salsabila', 'Yani Aulia'
        ];

        $faculties = ['Teknik', 'Ekonomi', 'Hukum', 'MIPA', 'Sosial Politik', 'Psikologi'];
        $majors = ['Teknik Informatika', 'Teknik Elektro', 'Manajemen', 'Akuntansi', 'Hukum', 'Matematika', 'Fisika', 'Ilmu Komunikasi', 'Psikologi'];

        for ($i = 0; $i < 50; $i++) {
            $name = $names[$i];
            $email = strtolower(str_replace(' ', '.', $name)) . ($i + 1) . '@student.telkomuniversity.ac.id';
            $nim = '1102' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => 'active',
                    'nim' => $nim,
                    'faculty' => $faculties[array_rand($faculties)],
                    'major' => $majors[array_rand($majors)],
                    'phone' => '08' . rand(1000000000, 9999999999),
                ]
            );

            if ($i % 10 == 0) {
                $this->info("✅ Created $i students...");
            }

            // Assign to random UKMs
            $allUkms = Ukm::where('status', 'active')->get();
            foreach ($allUkms as $ukm) {
                if (rand(1, 100) <= 25) { // 25% chance
                    UkmMember::firstOrCreate([
                        'user_id' => $user->id,
                        'ukm_id' => $ukm->id,
                    ], [
                        'status' => 'active',
                        'joined_date' => now(),
                    ]);
                }
            }
        }

        $this->info('📊 Updating UKM member counts...');

        // Update member counts
        $allUkms = Ukm::where('status', 'active')->get();
        foreach ($allUkms as $ukm) {
            $ukm->updateMemberCount();
        }

        $this->info('✅ Database population completed!');

        // Show statistics
        $ukmCount = Ukm::where('status', 'active')->count();
        $studentCount = User::where('role', 'student')->where('status', 'active')->count();
        $membershipCount = UkmMember::where('status', 'active')->count();

        $this->info("📈 Statistics:");
        $this->info("📋 Total UKMs: $ukmCount");
        $this->info("👥 Total Students: $studentCount");
        $this->info("🤝 Total Memberships: $membershipCount");

        $this->info('🌐 Homepage will now show updated data!');

        return 0;
    }
}
