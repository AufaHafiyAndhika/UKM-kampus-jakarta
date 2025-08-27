<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ukm;

class UkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ukms = [
            [
                'name' => 'Himpunan Mahasiswa Sistem Informasi',
                'slug' => 'himsi',
                'description' => 'Himpunan Mahasiswa Sistem Informasi (HIMSI) adalah organisasi kemahasiswaan yang menaungi mahasiswa Program Studi Sistem Informasi.',
                'category' => 'academic',
                'status' => 'active',
                'logo' => 'ukm-logos/himsi.png',
                'banner' => 'ukm-banners/himsi-banner.jpg',
                'contact_info' => json_encode([
                    'email' => 'himsi@telkomuniversity.ac.id',
                    'phone' => '081234567890',
                    'instagram' => '@himsi_telkomuniv',
                    'twitter' => '@himsi_telkomuniv',
                    'website' => 'https://himsi.telkomuniversity.ac.id'
                ]),
                'vision' => 'Menjadi himpunan mahasiswa yang unggul dalam bidang sistem informasi dan teknologi.',
                'mission' => 'Mengembangkan potensi mahasiswa sistem informasi melalui kegiatan akademik dan non-akademik.',
                'meeting_schedule' => 'Setiap Jumat, 16:00 - 18:00 WIB',
                'meeting_location' => 'Ruang HIMSI, Gedung Tokong Nanas',
                'max_members' => 150,
                'current_members' => 85,
                'is_recruiting' => true,
                'established_date' => '2013-09-15',
            ],
            [
                'name' => 'Unit Kegiatan Mahasiswa Basket',
                'slug' => 'ukm-basket',
                'description' => 'UKM Basket adalah wadah bagi mahasiswa yang memiliki minat dan bakat dalam olahraga basket.',
                'category' => 'sports',
                'status' => 'active',
                'logo' => 'ukm-logos/basket.png',
                'banner' => 'ukm-banners/basket-banner.jpg',
                'contact_info' => json_encode([
                    'email' => 'basket@telkomuniversity.ac.id',
                    'phone' => '081234567891',
                    'instagram' => '@basket_telkomuniv',
                    'twitter' => '@basket_telkomuniv'
                ]),
                'vision' => 'Menjadi tim basket terbaik di tingkat universitas dan mengharumkan nama Telkom University.',
                'mission' => 'Mengembangkan bakat dan minat mahasiswa dalam olahraga basket serta membangun karakter sportivitas.',
                'meeting_schedule' => 'Senin, Rabu, Jumat - 17:00 - 19:00 WIB',
                'meeting_location' => 'Lapangan Basket Telkom University',
                'max_members' => 30,
                'current_members' => 25,
                'is_recruiting' => true,
                'established_date' => '2010-03-20',
            ],
            [
                'name' => 'Unit Kegiatan Mahasiswa Musik',
                'slug' => 'ukm-musik',
                'description' => 'UKM Musik adalah tempat berkumpulnya mahasiswa yang memiliki passion di bidang musik.',
                'category' => 'arts',
                'status' => 'active',
                'logo' => 'ukm-logos/musik.png',
                'banner' => 'ukm-banners/musik-banner.jpg',
                'contact_info' => json_encode([
                    'email' => 'musik@telkomuniversity.ac.id',
                    'phone' => '081234567892',
                    'instagram' => '@musik_telkomuniv',
                    'youtube' => 'UKM Musik Telkom University',
                    'spotify' => 'UKM Musik Tel-U'
                ]),
                'vision' => 'Menjadi wadah pengembangan talenta musik terbaik di lingkungan kampus.',
                'mission' => 'Mengembangkan kreativitas dan bakat musik mahasiswa serta melestarikan budaya musik Indonesia.',
                'meeting_schedule' => 'Selasa, Kamis - 19:00 - 21:00 WIB',
                'meeting_location' => 'Studio Musik, Gedung Student Center',
                'max_members' => 50,
                'current_members' => 35,
                'is_recruiting' => true,
                'established_date' => '2012-08-10',
            ],
        ];

        foreach ($ukms as $ukm) {
            Ukm::create($ukm);
        }

        $this->command->info('UKM data seeded successfully!');
    }
}
