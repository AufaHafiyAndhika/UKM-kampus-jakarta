<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\UkmAchievement;
use App\Models\Event;
use App\Models\User;
use App\Models\EventRegistration;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class KetuaUkmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isKetuaUkm()) {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ketua UKM.');
            }
            return $next($request);
        });
    }

    /**
     * Show ketua UKM dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $leadingUkms = $user->getLeadingUkms();

        // Get statistics for UKMs that this user leads
        $stats = [
            'total_ukms' => $leadingUkms->count(),
            'total_members' => 0,
            'total_events' => 0,
            'upcoming_events' => 0,
            'ongoing_events' => 0,
        ];

        // Calculate pending members count for notification badge
        $pendingCount = 0;
        foreach ($leadingUkms as $ukm) {
            $stats['total_members'] += $ukm->activeMembers()->count();
            $stats['total_events'] += $ukm->events()->count();

            // Kegiatan mendatang: belum dimulai (start_datetime > now)
            $stats['upcoming_events'] += $ukm->events()
                ->where('start_datetime', '>', now())
                ->count();

            // Kegiatan sedang berlangsung: sudah dimulai tapi belum selesai
            $stats['ongoing_events'] += $ukm->events()
                ->where('start_datetime', '<=', now())
                ->where('end_datetime', '>=', now())
                ->count();

            $pendingCount += $ukm->members()->wherePivot('status', 'pending')->count();
        }

        return view('ketua-ukm.dashboard', compact('leadingUkms', 'stats', 'pendingCount'));
    }

    /**
     * Show UKM management page.
     */
    public function manageUkm($id)
    {
        $ukm = Ukm::findOrFail($id);

        // Check if current user is the leader of this UKM
        if ($ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.dashboard')->with('error', 'Anda tidak memiliki akses untuk mengelola UKM ini.');
        }

        $members = $ukm->activeMembers()->get();
        $events = $ukm->events()->orderBy('start_datetime', 'desc')->get();

        // Load achievements for display
        $ukm->load('achievements');

        return view('ketua-ukm.manage-ukm', compact('ukm', 'members', 'events'));
    }

    /**
     * Show form to edit UKM.
     */
    public function editUkm($id)
    {
        $ukm = Ukm::findOrFail($id);

        // Check if current user is the leader of this UKM
        if ($ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit UKM ini.');
        }

        // Eager load the achievements relationship. This forces Laravel to fetch from the related table.
        $ukm->load('achievements');

        // As a final safeguard against bad data in the 'achievements' column,
        // ensure the relation is a collection before passing it to the view.
        if (!$ukm->relationLoaded('achievements') || !is_iterable($ukm->achievements)) {
             $ukm->setRelation('achievements', collect());
        }

        return view('ketua-ukm.edit-ukm', compact('ukm'));
    }

    /**
     * Update UKM information.
     */
    public function updateUkm(Request $request, $id)
    {
        try {
            $ukm = Ukm::findOrFail($id);

            // Check if current user is the leader of this UKM
            if ($ukm->leader_id !== Auth::id()) {
                return redirect()->route('ketua-ukm.dashboard')->with([
                    'error' => 'Akses ditolak!',
                    'toast' => [
                        'type' => 'error',
                        'title' => 'Akses Ditolak!',
                        'message' => 'Anda tidak memiliki akses untuk mengedit UKM ini.',
                        'duration' => 6000
                    ]
                ]);
            }

            $request->validate([
            'description' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'meeting_schedule' => 'nullable|string',
            'meeting_location' => 'nullable|string',
            'achievements' => 'nullable|array',
            'achievements.*.title' => 'nullable|string|max:255',
            'achievements.*.description' => 'nullable|string',
            'achievements.*.type' => 'nullable|in:competition,award,certification,recognition,other',
            'achievements.*.level' => 'nullable|in:local,regional,national,international',
            'achievements.*.organizer' => 'nullable|string|max:255',
            'achievements.*.achievement_date' => 'nullable|date',
            'achievements.*.year' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'achievements.*.position' => 'nullable|integer|min:1',
            'achievements.*.participants' => 'nullable|string',
            'achievements.*.is_featured' => 'nullable|boolean',
            'achievements.*.certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organization_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $contactInfo = [];
        if ($request->filled('contact_info.email')) $contactInfo['email'] = $request->input('contact_info.email');
        if ($request->filled('contact_info.phone')) $contactInfo['phone'] = $request->input('contact_info.phone');
        if ($request->filled('contact_info.instagram')) $contactInfo['instagram'] = $request->input('contact_info.instagram');
        if ($request->filled('contact_info.website')) $contactInfo['website'] = $request->input('contact_info.website');

        // Handle logo upload
        $logoPath = $ukm->logo; // Keep existing logo by default
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($ukm->logo && Storage::disk('public')->exists($ukm->logo)) {
                Storage::disk('public')->delete($ukm->logo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('ukms/logos', 'public');
        }

        // Handle organization structure upload
        $organizationStructurePath = $ukm->organization_structure; // Keep existing by default
        if ($request->hasFile('organization_structure')) {
            // Delete old organization structure if exists
            if ($ukm->organization_structure && Storage::disk('public')->exists($ukm->organization_structure)) {
                Storage::disk('public')->delete($ukm->organization_structure);
            }

            // Store new organization structure
            $organizationStructurePath = $request->file('organization_structure')->store('ukms/organization_structures', 'public');
        }

        $ukm->update([
            'description' => $request->description,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'meeting_schedule' => $request->meeting_schedule,
            'meeting_location' => $request->meeting_location,
            'logo' => $logoPath,
            'organization_structure' => $organizationStructurePath,
            'contact_info' => json_encode($contactInfo),
        ]);

        // Handle achievements update
        if ($request->has('achievements') && is_array($request->achievements)) {
            // Delete existing achievements
            $ukm->achievements()->delete();

            // Create new achievements
            foreach ($request->achievements as $achievementData) {
                // Only create achievement if title is provided and not empty
                if (!empty($achievementData['title']) && trim($achievementData['title']) !== '') {
                    // Handle certificate file upload
                    $certificateFile = null;
                    if (isset($achievementData['certificate_file']) && $achievementData['certificate_file']) {
                        $certificateFile = $achievementData['certificate_file']->store('ukms/certificates', 'public');
                    }

                    $ukm->achievements()->create([
                        'title' => $achievementData['title'],
                        'description' => $achievementData['description'] ?? null,
                        'type' => $achievementData['type'] ?? 'competition',
                        'level' => $achievementData['level'] ?? 'local',
                        'organizer' => $achievementData['organizer'] ?? null,
                        'achievement_date' => $achievementData['achievement_date'] ?? now(),
                        'year' => $achievementData['year'] ?? date('Y'),
                        'position' => $achievementData['position'] ?? null,
                        'participants' => $achievementData['participants'] ?? null,
                        'is_featured' => isset($achievementData['is_featured']) ? true : false,
                        'certificate_file' => $certificateFile,
                    ]);
                }
            }
        }

            return redirect()->route('ketua-ukm.manage', $ukm->id)->with([
                'success' => 'Informasi UKM berhasil diperbarui!',
                'toast' => [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => "UKM {$ukm->name} telah berhasil diperbarui dengan informasi terbaru.",
                    'duration' => 5000
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating UKM: ' . $e->getMessage());

            return redirect()->back()->with([
                'error' => 'Gagal memperbarui UKM!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat memperbarui UKM. Silakan coba lagi.',
                    'duration' => 7000
                ]
            ])->withInput();
        }
    }

    /**
     * Show events for ketua UKM.
     */
    public function events()
    {
        $user = Auth::user();
        $leadingUkms = $user->ledUkms;

        if ($leadingUkms->count() === 0) {
            return redirect()->route('ketua-ukm.dashboard')->with('error', 'Anda belum ditugaskan untuk memimpin UKM manapun.');
        }

        // Get all events from UKMs that this user leads
        $events = Event::whereIn('ukm_id', $leadingUkms->pluck('id'))
                      ->with(['ukm'])
                      ->orderBy('start_datetime', 'desc')
                      ->paginate(15);

        return view('ketua-ukm.events.index', compact('events', 'leadingUkms'));
    }

    /**
     * Show form to create event.
     */
    public function createEvent($ukmId = null)
    {
        $user = Auth::user();
        $leadingUkms = $user->ledUkms;

        if ($leadingUkms->count() === 0) {
            return redirect()->route('ketua-ukm.dashboard')->with('error', 'Anda belum ditugaskan untuk memimpin UKM manapun.');
        }

        // If specific UKM ID provided, validate access
        if ($ukmId) {
            $ukm = Ukm::findOrFail($ukmId);
            if ($ukm->leader_id !== Auth::id()) {
                return redirect()->route('ketua-ukm.dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat event untuk UKM ini.');
            }
        } else {
            // Default to first UKM if user leads only one UKM
            if ($leadingUkms->count() === 1) {
                $ukm = $leadingUkms->first();
            } else {
                $ukm = null;
            }
        }

        $types = ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other'];

        return view('ketua-ukm.events.create', compact('leadingUkms', 'ukm', 'types'));
    }

    /**
     * Store new event.
     */
    public function storeEvent(Request $request)
    {
        try {
            $request->validate([
                'ukm_id' => 'required|exists:ukms,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'start_datetime' => 'required|date',
                'end_datetime' => 'required|date|after:start_datetime',
                'registration_start' => 'nullable|date|before:start_datetime',
                'registration_end' => 'nullable|date|before:start_datetime|after:registration_start',
                'location' => 'required|string|max:255',
                'max_participants' => 'nullable|integer|min:1',
                'type' => 'required|in:workshop,seminar,competition,meeting,social,other',
                'registration_open' => 'boolean',
                'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'proposal_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'rab_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'lpj_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'certificate_template' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        try {
            $ukm = Ukm::findOrFail($request->ukm_id);

            // Check if current user is the leader of this UKM
            if ($ukm->leader_id !== Auth::id()) {
                return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk membuat event untuk UKM ini.');
            }
        } catch (\Exception $e) {
            Log::error('Error finding UKM: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'UKM tidak ditemukan atau terjadi kesalahan.');
        }

        // Handle file uploads
        $posterPath = null;
        $proposalPath = null;
        $rabPath = null;
        $lpjPath = null;
        $certificatePath = null;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('events/posters', 'public');
        }

        if ($request->hasFile('proposal_file')) {
            $proposalPath = $request->file('proposal_file')->store('events/proposals', 'public');
        }

        if ($request->hasFile('rab_file')) {
            $rabPath = $request->file('rab_file')->store('events/rab', 'public');
        }

        if ($request->hasFile('lpj_file')) {
            $lpjPath = $request->file('lpj_file')->store('events/lpj', 'public');
        }

        if ($request->hasFile('certificate_template')) {
            $certificatePath = $request->file('certificate_template')->store('events/certificates', 'public');
        }

        try {
            $event = Event::create([
                'title' => $request->title,
                'slug' => \Illuminate\Support\Str::slug($request->title),
                'description' => $request->description,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,
                'location' => $request->location,
                'max_participants' => $request->max_participants,
                'type' => $request->type,
                'ukm_id' => $ukm->id,
                'status' => 'waiting',
                'registration_open' => $request->has('registration_open'),
                'requires_approval' => $request->has('requires_approval'),
                'poster' => $posterPath,
                'proposal_file' => $proposalPath,
                'rab_file' => $rabPath,
                'lpj_file' => $lpjPath,
                'certificate_template' => $certificatePath,
            ]);

            Log::info('Event created successfully', [
                'event_id' => $event->id,
                'title' => $event->title,
                'ukm_id' => $ukm->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('ketua-ukm.events')->with('success', 'Event berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage(), [
                'request_data' => $request->except(['poster', 'proposal_file', 'rab_file', 'lpj_file', 'certificate_template']),
                'user_id' => Auth::id(),
                'ukm_id' => $ukm->id ?? null
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat event. Silakan coba lagi.');
        }
    }

    /**
     * Show event detail.
     */
    public function showEvent(Event $event)
    {
        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk melihat event ini.');
        }

        $event->load(['ukm', 'registrations.user']);

        return view('ketua-ukm.events.show', compact('event'));
    }

    /**
     * Show form to edit event.
     */
    public function editEvent(Event $event)
    {
        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk mengedit event ini.');
        }

        $user = Auth::user();
        $leadingUkms = $user->ledUkms;
        $types = ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other'];

        return view('ketua-ukm.events.edit', compact('event', 'leadingUkms', 'types'));
    }

    /**
     * Update event.
     */
    public function updateEvent(Request $request, Event $event)
    {
        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk mengedit event ini.');
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_datetime' => 'required|date', // Removed after:now for testing
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_start' => 'nullable|date|before:start_datetime',
            'registration_end' => 'nullable|date|before:start_datetime|after:registration_start',
            'location' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'type' => 'required|in:workshop,seminar,competition,meeting,social,other',
            'registration_open' => 'boolean',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'proposal_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'rab_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'lpj_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'certificate_template' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        // Verify UKM access
        $ukm = Ukm::findOrFail($request->ukm_id);
        if ($ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk UKM ini.');
        }

        // Handle file uploads
        $updateData = [
            'ukm_id' => $request->ukm_id,
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title),
            'description' => $request->description,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'type' => $request->type,
            'registration_open' => $request->has('registration_open'),
            'requires_approval' => $request->has('requires_approval'),
        ];

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $posterPath = $request->file('poster')->store('events/posters', 'public');
            $updateData['poster'] = $posterPath;
        }

        // Handle proposal file upload
        if ($request->hasFile('proposal_file')) {
            // Delete old proposal if exists
            if ($event->proposal_file) {
                Storage::disk('public')->delete($event->proposal_file);
            }
            $proposalPath = $request->file('proposal_file')->store('events/proposals', 'public');
            $updateData['proposal_file'] = $proposalPath;
        }

        // Handle RAB file upload
        if ($request->hasFile('rab_file')) {
            // Delete old RAB if exists
            if ($event->rab_file) {
                Storage::disk('public')->delete($event->rab_file);
            }
            $rabPath = $request->file('rab_file')->store('events/rab', 'public');
            $updateData['rab_file'] = $rabPath;
        }

        // Handle LPJ file upload
        if ($request->hasFile('lpj_file')) {
            // Delete old LPJ if exists
            if ($event->lpj_file) {
                Storage::disk('public')->delete($event->lpj_file);
            }
            $lpjPath = $request->file('lpj_file')->store('events/lpj', 'public');
            $updateData['lpj_file'] = $lpjPath;
        }

        // Handle certificate template upload
        if ($request->hasFile('certificate_template')) {
            // Delete old template if exists
            if ($event->certificate_template) {
                Storage::disk('public')->delete($event->certificate_template);
            }
            $certificatePath = $request->file('certificate_template')->store('events/certificates', 'public');
            $updateData['certificate_template'] = $certificatePath;
        }

        $event->update($updateData);

        // Force update status based on new dates for any published/ongoing/completed event
        if (in_array($event->status, ['published', 'ongoing', 'completed'])) {
            $event->updateStatusBasedOnDates();
        }

        return redirect()->route('ketua-ukm.events.show', $event)->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Refresh event status based on current date
     */
    public function refreshEventStatus(Event $event)
    {
        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk event ini.');
        }

        $oldStatus = $event->status;

        // Force update status based on current dates
        $event->updateStatusBasedOnDates();

        $event->refresh();
        $newStatus = $event->status;

        if ($oldStatus !== $newStatus) {
            $message = "Status event berhasil diperbarui dari '{$oldStatus}' menjadi '{$newStatus}'.";
        } else {
            $message = "Status event sudah sesuai dengan tanggal saat ini: '{$newStatus}'.";
        }

        return redirect()->route('ketua-ukm.events.show', $event)->with('success', $message);
    }

    /**
     * Delete event.
     */
    public function destroyEvent(Event $event)
    {
        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== Auth::id()) {
            return redirect()->route('ketua-ukm.events')->with('error', 'Anda tidak memiliki akses untuk menghapus event ini.');
        }

        // Only allow deletion if event is still waiting or not published
        if ($event->status === 'published') {
            return redirect()->route('ketua-ukm.events')->with('error', 'Event yang sudah dipublikasikan tidak dapat dihapus.');
        }

        // Delete poster if exists
        if ($event->poster) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('ketua-ukm.events')->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Show attendances for an event
     */
    public function showAttendances(Event $event)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk melihat absensi event ini.');
        }

        // Get attendances with filters
        $query = $event->attendances()->with(['user', 'registration']);

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Apply verification filter
        if (request('verification')) {
            $query->where('verification_status', request('verification'));
        }

        $attendances = $query->orderBy('submitted_at', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);

        return view('ketua-ukm.events.attendances', compact('event', 'attendances'));
    }

    /**
     * Verify attendance
     */
    public function verifyAttendance(Request $request, Event $event, EventAttendance $attendance)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi absensi event ini.');
        }

        // Verify that attendance belongs to this event
        if ($attendance->event_id !== $event->id) {
            abort(404, 'Absensi tidak ditemukan untuk event ini.');
        }

        $request->validate([
            'action' => 'required|in:verify,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'verify') {
            $attendance->verify($user->id, $request->notes);
            $message = 'Absensi berhasil diverifikasi.';
        } else {
            $attendance->reject($user->id, $request->notes);
            $message = 'Absensi ditolak.';
        }

        return back()->with('success', $message);
    }

    /**
     * Bulk verify attendances
     */
    public function bulkVerifyAttendances(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if user is ketua UKM
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi absensi event ini.');
        }

        $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'exists:event_attendances,id',
            'action' => 'required|in:verify,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendanceIds = $request->attendance_ids;
        $action = $request->action;
        $notes = $request->notes;

        // Get attendances that belong to this event
        $attendances = $event->attendances()
                            ->whereIn('id', $attendanceIds)
                            ->where('verification_status', 'pending')
                            ->get();

        if ($attendances->isEmpty()) {
            return back()->with('error', 'Tidak ada absensi yang dapat diverifikasi.');
        }

        $successCount = 0;
        foreach ($attendances as $attendance) {
            try {
                if ($action === 'verify') {
                    $attendance->verify($user->id, $notes);
                } else {
                    $attendance->reject($user->id, $notes);
                }
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Failed to bulk verify attendance: ' . $e->getMessage());
            }
        }

        $actionText = $action === 'verify' ? 'diverifikasi' : 'ditolak';
        $message = "Berhasil {$actionText} {$successCount} absensi dari {$attendances->count()} yang dipilih.";

        return back()->with('success', $message);
    }

    /**
     * Show event registrations for approval
     */
    public function showEventRegistrations(Event $event)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk melihat pendaftaran event ini.');
        }

        // Get registrations with filters
        $query = $event->registrations()->with('user');

        // Apply search filter
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $registrations = $query->orderBy('created_at', 'desc')
                              ->paginate(20);

        return view('ketua-ukm.events.registrations', compact('event', 'registrations'));
    }

    /**
     * Show registration details
     */
    public function showRegistrationDetails(Event $event, EventRegistration $registration)
    {
        $user = Auth::user();

        // Check if user is ketua UKM
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail pendaftaran ini.');
        }

        // Check if current user is the leader of this UKM
        if ($event->ukm->leader_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail pendaftaran event ini.');
        }

        // Check if registration belongs to this event
        if ($registration->event_id !== $event->id) {
            abort(404, 'Pendaftaran tidak ditemukan untuk event ini.');
        }

        $registration->load(['user', 'event.ukm']);

        return view('ketua-ukm.events.registration-details', compact('event', 'registration'));
    }

    /**
     * Approve event registration
     */
    public function approveEventRegistration(Request $request, Event $event, $registrationId)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui pendaftaran event ini.');
        }

        $registration = $event->registrations()->findOrFail($registrationId);

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Update registration status
        $registration->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $user->id,
        ]);

        // Update event participant count
        $event->updateParticipantCount();

        // Send notification to user
        try {
            NotificationService::sendEventRegistrationApproved($registration->user, $event);
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return back()->with('success', 'Pendaftaran berhasil disetujui.');
    }

    /**
     * Reject event registration
     */
    public function rejectEventRegistration(Request $request, Event $event, $registrationId)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk menolak pendaftaran event ini.');
        }

        $registration = $event->registrations()->findOrFail($registrationId);

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        // Update registration status
        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->notes,
        ]);

        // Send notification to user
        try {
            NotificationService::sendEventRegistrationRejected($registration->user, $event, $request->notes);
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return back()->with('success', 'Pendaftaran berhasil ditolak.');
    }

    /**
     * Bulk approve event registrations
     */
    public function bulkApproveEventRegistrations(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if user is ketua UKM (simplified check for now)
        if ($user->role !== 'ketua_ukm') {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui pendaftaran event ini.');
        }

        $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:event_registrations,id',
        ]);

        $registrationIds = $request->registration_ids;

        // Update all selected registrations
        $updated = $event->registrations()
                        ->whereIn('id', $registrationIds)
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => $user->id,
                        ]);

        // Update event participant count
        $event->updateParticipantCount();

        // Send notifications to approved users
        try {
            $approvedRegistrations = $event->registrations()
                                          ->whereIn('id', $registrationIds)
                                          ->with('user')
                                          ->get();

            foreach ($approvedRegistrations as $registration) {
                NotificationService::sendEventRegistrationApproved($registration->user, $event);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send bulk notifications: ' . $e->getMessage());
        }

        return back()->with('success', "Berhasil menyetujui $updated pendaftaran sekaligus.");
    }

    /**
     * Show pending members for approval.
     */
    public function pendingMembers()
    {
        $user = Auth::user();
        $ukm = Ukm::where('leader_id', $user->id)->first();

        if (!$ukm) {
            return redirect()->route('ketua-ukm.dashboard')
                           ->with('error', 'Anda tidak memiliki akses untuk mengelola anggota UKM.');
        }

        // Get only pending members
        $pendingMembers = $ukm->members()->wherePivot('status', 'pending')->get();

        return view('ketua-ukm.pending-members', compact('ukm', 'pendingMembers'));
    }

    /**
     * Show members management page.
     */
    public function members()
    {
        $user = Auth::user();

        // Debug: Log current user
        Log::info('Ketua UKM accessing members page', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role
        ]);

        // Get UKM that user leads
        $ukm = Ukm::where('leader_id', $user->id)->first();

        // Debug: Log UKM found
        Log::info('UKM lookup result', [
            'user_id' => $user->id,
            'ukm_found' => $ukm ? $ukm->name : 'None',
            'ukm_id' => $ukm ? $ukm->id : null
        ]);

        if (!$ukm) {
            return redirect()->route('ketua-ukm.dashboard')
                           ->with('error', 'Anda tidak memiliki akses untuk mengelola anggota UKM.');
        }

        // Get members by status
        $pendingMembers = $ukm->members()->wherePivot('status', 'pending')->get();
        $activeMembers = $ukm->members()->wherePivot('status', 'active')->get();
        $rejectedMembers = $ukm->members()->wherePivot('status', 'rejected')->get();

        // Debug: Log member counts
        Log::info('Member counts for UKM', [
            'ukm_id' => $ukm->id,
            'ukm_name' => $ukm->name,
            'pending_count' => $pendingMembers->count(),
            'active_count' => $activeMembers->count(),
            'rejected_count' => $rejectedMembers->count(),
            'pending_members' => $pendingMembers->pluck('name', 'id')->toArray(),
        ]);

        // Get recent statistics
        $recentlyApproved = $ukm->members()
            ->wherePivot('status', 'active')
            ->wherePivot('approved_at', '>=', now()->startOfMonth())
            ->get();

        $recentlyRejected = $ukm->members()
            ->wherePivot('status', 'rejected')
            ->wherePivot('rejected_at', '>=', now()->startOfMonth())
            ->get();

        return view('ketua-ukm.members', compact(
            'ukm', 'pendingMembers', 'activeMembers', 'rejectedMembers',
            'recentlyApproved', 'recentlyRejected'
        ));
    }

    /**
     * Approve member application.
     */
    public function approveMember($memberId)
    {
        $user = Auth::user();
        $ukm = Ukm::where('leader_id', $user->id)->first();

        if (!$ukm) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengelola anggota UKM.');
        }

        // Check if member exists and is pending
        $member = $ukm->members()->wherePivot('user_id', $memberId)->wherePivot('status', 'pending')->first();

        if (!$member) {
            return back()->with('error', 'Anggota tidak ditemukan atau sudah diproses.');
        }

        // Check if UKM has space
        if ($ukm->current_members >= $ukm->max_members) {
            return back()->with('error', 'UKM sudah mencapai kapasitas maksimal.');
        }

        // Update member status
        $ukm->members()->updateExistingPivot($memberId, [
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $user->id,
            'joined_date' => now(),
        ]);

        // Update member count to actual active count
        $ukm->updateMemberCount();

        // Send notification to the approved member
        NotificationService::createUkmApplicationApproved($member, $ukm->name);

        return back()->with('success', 'Anggota berhasil diterima dan notifikasi telah dikirim.');
    }

    /**
     * Reject member application.
     */
    public function rejectMember($memberId)
    {
        $user = Auth::user();
        $ukm = Ukm::where('leader_id', $user->id)->first();

        if (!$ukm) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengelola anggota UKM.');
        }

        // Check if member exists and is pending
        $member = $ukm->members()->wherePivot('user_id', $memberId)->wherePivot('status', 'pending')->first();

        if (!$member) {
            return back()->with('error', 'Anggota tidak ditemukan atau sudah diproses.');
        }

        // Update member status - simplified without rejection_reason
        $ukm->members()->updateExistingPivot($memberId, [
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $user->id,
        ]);

        // Send notification to the rejected member
        NotificationService::createUkmApplicationRejected($member, $ukm->name);

        return back()->with('success', 'Pendaftar berhasil ditolak dan notifikasi telah dikirim.');
    }

    /**
     * Remove member from UKM.
     */
    public function removeMember(Request $request, $memberId)
    {
        $request->validate([
            'removal_reason' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $ukm = Ukm::where('leader_id', $user->id)->first();

        if (!$ukm) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengelola anggota UKM.');
        }

        // Check if member exists and is active
        $member = $ukm->members()->wherePivot('user_id', $memberId)->wherePivot('status', 'active')->first();

        if (!$member) {
            return back()->with('error', 'Anggota tidak ditemukan atau tidak aktif.');
        }

        // Send notification to the removed member
        NotificationService::createUkmMemberRemoved($member, $ukm->name, $request->removal_reason);

        // Completely remove member from UKM (detach from pivot table)
        $ukm->members()->detach($memberId);

        // Update member count to actual active count
        $ukm->updateMemberCount();

        return back()->with('success', "Anggota {$member->name} berhasil dikeluarkan dari UKM dan notifikasi telah dikirim.");
    }

    /**
     * Get member details for modal.
     */
    public function getMemberDetails($memberId)
    {
        $user = Auth::user();
        $ukm = Ukm::where('leader_id', $user->id)->first();

        if (!$ukm) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get member details
        $member = $ukm->members()->where('ukm_members.user_id', $memberId)->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        return response()->json([
            'previous_experience' => $member->pivot->previous_experience,
            'skills_interests' => $member->pivot->skills_interests,
            'reason_joining' => $member->pivot->reason_joining,
            'preferred_division' => $member->pivot->preferred_division,
            'cv_file' => $member->pivot->cv_file,
            'applied_at' => \App\Helpers\DateHelper::tableFormat($member->pivot->applied_at),
            'approved_at' => \App\Helpers\DateHelper::tableFormat($member->pivot->approved_at),
            'joined_date' => \App\Helpers\DateHelper::dateOnly($member->pivot->joined_date),
        ]);
    }

}
