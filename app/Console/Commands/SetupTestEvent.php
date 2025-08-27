<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use App\Models\User;
use App\Models\Ukm;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SetupTestEvent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:setup-event {--id= : Event ID to update} {--create : Create new test event}';

    /**
     * The console command description.
     */
    protected $description = 'Setup test event for attendance system testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Setting up test event for attendance system...');

        if ($this->option('create')) {
            $this->createTestEvent();
        } elseif ($this->option('id')) {
            $this->updateExistingEvent($this->option('id'));
        } else {
            $this->showMenu();
        }
    }

    private function showMenu()
    {
        $this->info('📋 Available Events:');
        
        $events = Event::with('ukm')
                      ->withCount(['registrations as approved_registrations' => function($query) {
                          $query->where('status', 'approved');
                      }])
                      ->latest()
                      ->take(10)
                      ->get();

        $headers = ['ID', 'Title', 'UKM', 'End Date', 'Status', 'Registrations'];
        $rows = [];

        foreach ($events as $event) {
            $status = $event->end_datetime < now() ? '✅ Ended' : '⏳ Ongoing';
            $rows[] = [
                $event->id,
                substr($event->title, 0, 30),
                $event->ukm->name ?? 'N/A',
                $event->end_datetime->format('Y-m-d H:i'),
                $status,
                $event->approved_registrations
            ];
        }

        $this->table($headers, $rows);

        $choice = $this->choice('What would you like to do?', [
            'update' => 'Update existing event to be ended',
            'create' => 'Create new test event',
            'show' => 'Show events ready for attendance'
        ]);

        switch ($choice) {
            case 'update':
                $eventId = $this->ask('Enter Event ID to update');
                $this->updateExistingEvent($eventId);
                break;
            case 'create':
                $this->createTestEvent();
                break;
            case 'show':
                $this->showReadyEvents();
                break;
        }
    }

    private function updateExistingEvent($eventId)
    {
        $event = Event::find($eventId);
        
        if (!$event) {
            $this->error("Event with ID $eventId not found!");
            return;
        }

        $this->info("Updating event: {$event->title}");

        // Update event to be ended
        $event->update([
            'start_datetime' => Carbon::now()->subDays(2),
            'end_datetime' => Carbon::now()->subDay(),
            'status' => 'published'
        ]);

        $this->info('✅ Event updated to be ended');

        // Create attendance records for approved registrations
        $approvedRegistrations = $event->registrations()->where('status', 'approved')->get();
        
        foreach ($approvedRegistrations as $registration) {
            EventAttendance::firstOrCreate([
                'event_id' => $event->id,
                'user_id' => $registration->user_id,
                'event_registration_id' => $registration->id,
            ]);
        }

        $this->info("✅ Created attendance records for {$approvedRegistrations->count()} registrations");
        $this->showEventInfo($event);
    }

    private function createTestEvent()
    {
        $ukm = Ukm::first();
        
        if (!$ukm) {
            $this->error('No UKM found! Please create a UKM first.');
            return;
        }

        $this->info("Creating test event for UKM: {$ukm->name}");

        // Create test event
        $event = Event::create([
            'ukm_id' => $ukm->id,
            'title' => 'Workshop Testing Absensi - ' . now()->format('Y-m-d H:i'),
            'slug' => 'workshop-testing-absensi-' . now()->timestamp,
            'description' => 'Event khusus untuk testing sistem absensi dan sertifikat. Event ini sudah berakhir untuk keperluan testing.',
            'start_datetime' => Carbon::now()->subDays(2),
            'end_datetime' => Carbon::now()->subDay(),
            'location' => 'Lab Komputer (Testing)',
            'max_participants' => 30,
            'type' => 'workshop',
            'status' => 'published',
            'registration_open' => true,
        ]);

        $this->info("✅ Test event created: {$event->title}");

        // Create test registrations
        $students = User::where('role', 'student')->take(3)->get();
        
        if ($students->isEmpty()) {
            $this->warn('No students found! Please create student accounts first.');
            return;
        }

        foreach ($students as $student) {
            $registration = EventRegistration::create([
                'user_id' => $student->id,
                'event_id' => $event->id,
                'status' => 'approved',
                'motivation' => 'Test registration for attendance system testing',
            ]);

            EventAttendance::create([
                'event_id' => $event->id,
                'user_id' => $student->id,
                'event_registration_id' => $registration->id,
            ]);

            $this->info("✅ Created registration & attendance for: {$student->name}");
        }

        $this->showEventInfo($event);
    }

    private function showReadyEvents()
    {
        $this->info('📊 Events Ready for Attendance Testing:');

        $events = Event::where('end_datetime', '<', now())
                      ->with(['ukm', 'registrations', 'attendances'])
                      ->latest('end_datetime')
                      ->take(10)
                      ->get();

        if ($events->isEmpty()) {
            $this->warn('No ended events found!');
            return;
        }

        foreach ($events as $event) {
            $approvedRegs = $event->registrations->where('status', 'approved')->count();
            $attendanceRecords = $event->attendances->count();
            $submittedAttendance = $event->attendances->where('status', 'present')->count();

            $this->info("📅 {$event->title} (ID: {$event->id})");
            $this->line("   UKM: {$event->ukm->name}");
            $this->line("   Ended: {$event->end_datetime->format('Y-m-d H:i')}");
            $this->line("   Registrations: $approvedRegs | Attendance Records: $attendanceRecords | Submitted: $submittedAttendance");
            $this->line("   URL: /events/{$event->slug}");
            $this->line('');
        }
    }

    private function showEventInfo($event)
    {
        $this->info('🎯 Event Information:');
        $this->line("Title: {$event->title}");
        $this->line("Slug: {$event->slug}");
        $this->line("URL: /events/{$event->slug}");
        $this->line("End Date: {$event->end_datetime->format('Y-m-d H:i')}");
        
        $approvedRegs = $event->registrations()->where('status', 'approved')->count();
        $attendanceRecords = $event->attendances()->count();
        
        $this->line("Approved Registrations: $approvedRegs");
        $this->line("Attendance Records: $attendanceRecords");
        
        $this->info('');
        $this->info('🚀 Testing Steps:');
        $this->line('1. Login sebagai mahasiswa yang terdaftar');
        $this->line('2. Buka URL: /events/' . $event->slug);
        $this->line('3. Klik tombol "Isi Absensi"');
        $this->line('4. Upload bukti kehadiran');
        $this->line('5. Login sebagai admin untuk verifikasi');
        $this->line('6. Download sertifikat');
    }
}
