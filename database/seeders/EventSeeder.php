<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ukm;
use App\Models\User;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ukms = Ukm::all();
        $organizers = User::where('role', 'student')->get();

        if ($ukms->isEmpty() || $organizers->isEmpty()) {
            $this->command->warn('Please run UkmSeeder and create some student users first.');
            return;
        }

        $events = [
            // Past Events (Completed)
            [
                'title' => 'Workshop Web Development dengan Laravel',
                'description' => 'Workshop intensif untuk mempelajari framework Laravel dari dasar hingga mahir. Peserta akan belajar membuat aplikasi web modern dengan fitur-fitur terkini.',
                'requirements' => 'Laptop dengan PHP dan Composer terinstall, Pengetahuan dasar HTML/CSS/JavaScript',
                'type' => 'workshop',
                'location' => 'Lab Komputer Gedung Tokong Nanas Lt. 3',
                'start_datetime' => now()->subDays(30)->setTime(9, 0),
                'end_datetime' => now()->subDays(30)->setTime(16, 0),
                'registration_start' => now()->subDays(45),
                'registration_end' => now()->subDays(32),
                'max_participants' => 30,
                'current_participants' => 28,
                'registration_fee' => 50000,
                'status' => 'completed',
                'requires_approval' => true,
                'certificate_available' => true,
                'contact_person' => [
                    'name' => 'Ahmad Rizki',
                    'phone' => '081234567890',
                    'email' => 'ahmad.rizki@student.telkomuniversity.ac.id'
                ],
            ],
            [
                'title' => 'Turnamen Basket Antar Fakultas 2024',
                'description' => 'Kompetisi basket tahunan yang mempertemukan tim-tim terbaik dari setiap fakultas. Ajang untuk menunjukkan sportivitas dan kemampuan bermain basket.',
                'requirements' => 'Tim terdiri dari 8-12 pemain, Surat keterangan sehat dari dokter, Jersey seragam tim',
                'type' => 'competition',
                'location' => 'Lapangan Basket Telkom University',
                'start_datetime' => now()->subDays(15)->setTime(8, 0),
                'end_datetime' => now()->subDays(13)->setTime(18, 0),
                'registration_start' => now()->subDays(60),
                'registration_end' => now()->subDays(20),
                'max_participants' => 8,
                'current_participants' => 6,
                'registration_fee' => 200000,
                'status' => 'completed',
                'requires_approval' => true,
                'certificate_available' => true,
                'contact_person' => [
                    'name' => 'Budi Santoso',
                    'phone' => '081234567891',
                    'email' => 'budi.santoso@student.telkomuniversity.ac.id'
                ],
            ],
            [
                'title' => 'Konser Musik Akustik "Harmoni Kampus"',
                'description' => 'Pertunjukan musik akustik yang menampilkan talenta-talenta terbaik dari UKM Musik. Nikmati malam yang penuh dengan harmoni dan melodi indah.',
                'requirements' => 'Tiket masuk (gratis), Pakaian sopan',
                'type' => 'social',
                'location' => 'Auditorium Telkom University',
                'start_datetime' => now()->subDays(7)->setTime(19, 0),
                'end_datetime' => now()->subDays(7)->setTime(22, 0),
                'registration_start' => now()->subDays(30),
                'registration_end' => now()->subDays(8),
                'max_participants' => 500,
                'current_participants' => 450,
                'registration_fee' => 0,
                'status' => 'completed',
                'requires_approval' => false,
                'certificate_available' => false,
                'contact_person' => [
                    'name' => 'Sari Melati',
                    'phone' => '081234567892',
                    'email' => 'sari.melati@student.telkomuniversity.ac.id'
                ],
            ],

            // Upcoming Events
            [
                'title' => 'Seminar Teknologi AI dan Machine Learning',
                'description' => 'Seminar nasional tentang perkembangan terkini dalam bidang Artificial Intelligence dan Machine Learning. Pembicara dari industri dan akademisi terkemuka.',
                'requirements' => 'Mahasiswa aktif Telkom University, Laptop untuk hands-on session',
                'type' => 'seminar',
                'location' => 'Aula Besar Telkom University',
                'start_datetime' => now()->addDays(7)->setTime(9, 0),
                'end_datetime' => now()->addDays(7)->setTime(16, 0),
                'registration_start' => now()->subDays(10),
                'registration_end' => now()->addDays(5),
                'max_participants' => 200,
                'current_participants' => 45,
                'registration_fee' => 25000,
                'status' => 'published',
                'requires_approval' => true,
                'certificate_available' => true,
                'contact_person' => [
                    'name' => 'Dr. Andi Wijaya',
                    'phone' => '081234567893',
                    'email' => 'andi.wijaya@telkomuniversity.ac.id'
                ],
            ],
            [
                'title' => 'Kompetisi Programming Contest 2024',
                'description' => 'Kompetisi pemrograman tingkat universitas untuk menguji kemampuan algoritma dan problem solving. Hadiah menarik untuk para pemenang!',
                'requirements' => 'Tim 1-3 orang, Laptop dengan IDE favorit, Pengetahuan algoritma dan struktur data',
                'type' => 'competition',
                'location' => 'Lab Programming Gedung Bangkit',
                'start_datetime' => now()->addDays(14)->setTime(9, 0),
                'end_datetime' => now()->addDays(14)->setTime(17, 0),
                'registration_start' => now()->subDays(5),
                'registration_end' => now()->addDays(10),
                'max_participants' => 50,
                'current_participants' => 23,
                'registration_fee' => 75000,
                'status' => 'published',
                'requires_approval' => true,
                'certificate_available' => true,
                'contact_person' => [
                    'name' => 'Reza Pratama',
                    'phone' => '081234567894',
                    'email' => 'reza.pratama@student.telkomuniversity.ac.id'
                ],
            ],
            [
                'title' => 'Workshop Fotografi dan Videografi',
                'description' => 'Belajar teknik dasar fotografi dan videografi untuk content creator. Dari komposisi, lighting, hingga editing dengan software profesional.',
                'requirements' => 'Kamera DSLR/Mirrorless (bisa pinjam), Laptop dengan software editing, Semangat belajar tinggi',
                'type' => 'workshop',
                'location' => 'Studio Multimedia Gedung Kreatif',
                'start_datetime' => now()->addDays(21)->setTime(10, 0),
                'end_datetime' => now()->addDays(21)->setTime(15, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(18),
                'max_participants' => 25,
                'current_participants' => 8,
                'registration_fee' => 100000,
                'status' => 'published',
                'requires_approval' => false,
                'certificate_available' => true,
                'contact_person' => [
                    'name' => 'Maya Sari',
                    'phone' => '081234567895',
                    'email' => 'maya.sari@student.telkomuniversity.ac.id'
                ],
            ],
            [
                'title' => 'Bakti Sosial "Berbagi Kasih di Panti Asuhan"',
                'description' => 'Kegiatan bakti sosial mengunjungi panti asuhan untuk berbagi kasih dan memberikan bantuan kepada anak-anak yang membutuhkan.',
                'requirements' => 'Pakaian sopan dan nyaman, Membawa donasi (opsional), Hati yang tulus untuk berbagi',
                'type' => 'social',
                'location' => 'Panti Asuhan Kasih Sayang, Bandung',
                'start_datetime' => now()->addDays(28)->setTime(8, 0),
                'end_datetime' => now()->addDays(28)->setTime(16, 0),
                'registration_start' => now(),
                'registration_end' => now()->addDays(25),
                'max_participants' => 40,
                'current_participants' => 12,
                'registration_fee' => 0,
                'status' => 'published',
                'requires_approval' => false,
                'certificate_available' => false,
                'contact_person' => [
                    'name' => 'Indah Permata',
                    'phone' => '081234567896',
                    'email' => 'indah.permata@student.telkomuniversity.ac.id'
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $ukm = $ukms->random();
            $organizer = $organizers->random();

            Event::create([
                'ukm_id' => $ukm->id,
                'title' => $eventData['title'],
                'slug' => Str::slug($eventData['title']),
                'description' => $eventData['description'],
                'requirements' => $eventData['requirements'],
                'type' => $eventData['type'],
                'location' => $eventData['location'],
                'start_datetime' => $eventData['start_datetime'],
                'end_datetime' => $eventData['end_datetime'],
                'registration_start' => $eventData['registration_start'],
                'registration_end' => $eventData['registration_end'],
                'max_participants' => $eventData['max_participants'],
                'current_participants' => $eventData['current_participants'],
                'registration_fee' => $eventData['registration_fee'],
                'status' => $eventData['status'],
                'requires_approval' => $eventData['requires_approval'],
                'certificate_available' => $eventData['certificate_available'],
                'contact_person' => $eventData['contact_person'],
            ]);
        }

        $this->command->info('Event seeder completed successfully!');
        $this->command->info('Created ' . count($events) . ' events');
        $this->command->info('- Past events: 3');
        $this->command->info('- Upcoming events: 4');
    }
}
