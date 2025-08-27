<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UkmAchievement;
use App\Models\Ukm;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get UKMs
        $himsi = Ukm::where('slug', 'himpunan-mahasiswa-sistem-informasi')->first();
        $basket = Ukm::where('slug', 'unit-kegiatan-mahasiswa-basket')->first();
        $musik = Ukm::where('slug', 'ukm-musik')->first();

        if (!$himsi) {
            $this->command->warn('HIMSI UKM not found, skipping achievements seeder');
            return;
        }

        $achievements = [
            // HIMSI Achievements
            [
                'ukm_id' => $himsi->id,
                'title' => 'Juara 1 Lomba Programming Contest Nasional 2024',
                'description' => 'Tim HIMSI berhasil meraih juara 1 dalam lomba programming contest tingkat nasional yang diselenggarakan oleh Universitas Indonesia. Kompetisi ini diikuti oleh 150 tim dari seluruh Indonesia.',
                'type' => 'competition',
                'level' => 'national',
                'organizer' => 'Universitas Indonesia',
                'achievement_date' => now()->subMonths(2),
                'year' => 2024,
                'position' => 1,
                'participants' => 'Ahmad Rizki Pratama, Siti Nurhaliza, Budi Santoso',
                'is_featured' => true,
            ],
            [
                'ukm_id' => $himsi->id,
                'title' => 'Juara 2 Hackathon Telkom University 2024',
                'description' => 'Berhasil meraih posisi runner-up dalam hackathon internal Telkom University dengan tema "Smart Campus Solution". Mengembangkan aplikasi manajemen UKM yang inovatif.',
                'type' => 'competition',
                'level' => 'local',
                'organizer' => 'Telkom University',
                'achievement_date' => now()->subMonths(1),
                'year' => 2024,
                'position' => 2,
                'participants' => 'Dewi Sartika, Andi Wijaya, Citra Melati',
                'is_featured' => true,
            ],
            [
                'ukm_id' => $himsi->id,
                'title' => 'Sertifikasi AWS Cloud Practitioner',
                'description' => 'Anggota HIMSI berhasil mendapatkan sertifikasi AWS Cloud Practitioner sebagai bagian dari program pengembangan skill cloud computing.',
                'type' => 'certification',
                'level' => 'international',
                'organizer' => 'Amazon Web Services',
                'achievement_date' => now()->subMonths(3),
                'year' => 2024,
                'position' => null,
                'participants' => 'Fajar Nugraha, Rina Sari, Doni Pratama, Lisa Permata, Eko Susanto',
                'is_featured' => false,
            ],
            [
                'ukm_id' => $himsi->id,
                'title' => 'Juara 3 Web Development Competition Regional',
                'description' => 'Tim HIMSI meraih juara 3 dalam kompetisi web development tingkat regional Jawa Barat dengan mengembangkan platform e-learning yang user-friendly.',
                'type' => 'competition',
                'level' => 'regional',
                'organizer' => 'APTIKOM Jawa Barat',
                'achievement_date' => now()->subMonths(4),
                'year' => 2024,
                'position' => 3,
                'participants' => 'Gita Savitri, Hendra Kusuma, Indira Putri',
                'is_featured' => true,
            ],
            [
                'ukm_id' => $himsi->id,
                'title' => 'Penghargaan Best Innovation Award',
                'description' => 'Mendapat penghargaan Best Innovation Award dari Kemendikbud untuk proyek aplikasi pembelajaran adaptif menggunakan AI.',
                'type' => 'award',
                'level' => 'national',
                'organizer' => 'Kementerian Pendidikan dan Kebudayaan',
                'achievement_date' => now()->subMonths(6),
                'year' => 2024,
                'position' => null,
                'participants' => 'Tim Riset HIMSI: 8 anggota',
                'is_featured' => true,
            ],
        ];

        // Add achievements for other UKMs if they exist
        if ($basket) {
            $achievements = array_merge($achievements, [
                [
                    'ukm_id' => $basket->id,
                    'title' => 'Juara 1 Liga Basket Antar Universitas Jawa Barat',
                    'description' => 'Tim basket Telkom University berhasil menjadi juara dalam liga basket antar universitas se-Jawa Barat setelah mengalahkan 16 tim peserta lainnya.',
                    'type' => 'competition',
                    'level' => 'regional',
                    'organizer' => 'PERBASI Jawa Barat',
                    'achievement_date' => now()->subMonths(1),
                    'year' => 2024,
                    'position' => 1,
                    'participants' => 'Tim Basket Telkom University (12 pemain)',
                    'is_featured' => true,
                ],
                [
                    'ukm_id' => $basket->id,
                    'title' => 'Juara 3 Turnamen Basket Nasional Mahasiswa',
                    'description' => 'Meraih posisi ketiga dalam turnamen basket nasional mahasiswa yang diselenggarakan di Jakarta dengan peserta dari 32 universitas.',
                    'type' => 'competition',
                    'level' => 'national',
                    'organizer' => 'PERBASI Indonesia',
                    'achievement_date' => now()->subMonths(3),
                    'year' => 2024,
                    'position' => 3,
                    'participants' => 'Tim Basket Telkom University',
                    'is_featured' => true,
                ],
            ]);
        }

        if ($musik) {
            $achievements = array_merge($achievements, [
                [
                    'ukm_id' => $musik->id,
                    'title' => 'Juara 1 Festival Musik Mahasiswa Bandung',
                    'description' => 'Band UKM Musik Telkom University berhasil meraih juara 1 dalam festival musik mahasiswa se-Bandung dengan membawakan lagu original yang menginspirasi.',
                    'type' => 'competition',
                    'level' => 'local',
                    'organizer' => 'Dinas Kebudayaan Kota Bandung',
                    'achievement_date' => now()->subMonths(2),
                    'year' => 2024,
                    'position' => 1,
                    'participants' => 'Band Telkom Harmony: 5 anggota',
                    'is_featured' => true,
                ],
                [
                    'ukm_id' => $musik->id,
                    'title' => 'Penghargaan Best Performance Award',
                    'description' => 'Mendapat penghargaan Best Performance dalam acara Dies Natalis Telkom University dengan penampilan yang memukau audience.',
                    'type' => 'award',
                    'level' => 'local',
                    'organizer' => 'Telkom University',
                    'achievement_date' => now()->subMonths(4),
                    'year' => 2024,
                    'position' => null,
                    'participants' => 'Paduan Suara UKM Musik',
                    'is_featured' => false,
                ],
            ]);
        }

        foreach ($achievements as $achievement) {
            UkmAchievement::create($achievement);
        }

        $this->command->info('Achievement seeder completed successfully!');
    }
}
