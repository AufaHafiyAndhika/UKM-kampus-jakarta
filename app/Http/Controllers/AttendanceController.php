<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Show attendance form for an event
     */
    public function showForm(Event $event)
    {
        $user = Auth::user();

        // Check if user is student
        if ($user->role !== 'student') {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Hanya mahasiswa yang dapat mengisi absensi.');
        }

        // Check if event has ended
        if (!$event->canSubmitAttendance()) {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Event belum berakhir. Absensi hanya dapat diisi setelah event selesai.');
        }

        // Check if user was registered and approved for this event
        $registration = $event->registrations()
                             ->where('user_id', $user->id)
                             ->where('status', 'approved')
                             ->first();

        if (!$registration) {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Anda tidak terdaftar atau belum disetujui untuk event ini.');
        }

        // Get or create attendance record
        $attendance = EventAttendance::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'event_registration_id' => $registration->id,
        ], [
            'status' => 'pending',
            'verification_status' => 'pending',
        ]);

        // Check if already submitted
        if ($attendance->status !== 'pending') {
            return redirect()->route('events.show', $event->slug)
                           ->with('info', 'Anda sudah mengisi absensi untuk event ini.');
        }

        return view('events.attendance', compact('event', 'attendance'));
    }

    /**
     * Submit attendance
     */
    public function submit(Request $request, Event $event)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'proof_file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get attendance record
        $attendance = EventAttendance::where('event_id', $event->id)
                                   ->where('user_id', $user->id)
                                   ->first();

        if (!$attendance) {
            return back()->with('error', 'Data absensi tidak ditemukan.');
        }

        if ($attendance->status !== 'pending') {
            return back()->with('error', 'Absensi sudah pernah disubmit.');
        }

        // Upload proof file
        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('attendances/proofs', 'public');
        }

        // Submit attendance
        $attendance->submitAttendance($proofPath, $request->notes);

        return redirect()->route('events.show', $event->slug)
                        ->with('success', 'Absensi berhasil disubmit! Menunggu verifikasi dari admin/ketua UKM.');
    }

    /**
     * Download certificate
     */
    public function downloadCertificate(Event $event)
    {
        $user = Auth::user();

        // Get attendance record
        $attendance = EventAttendance::where('event_id', $event->id)
                                   ->where('user_id', $user->id)
                                   ->first();

        if (!$attendance) {
            return back()->with('error', 'Data absensi tidak ditemukan.');
        }

        if (!$attendance->canDownloadCertificate()) {
            return back()->with('error', 'Sertifikat belum dapat didownload. Pastikan absensi sudah diverifikasi.');
        }

        try {
            return $this->certificateService->downloadCertificate($attendance);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendownload sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Ketua UKM: View attendance list
     */
    public function index(Event $event)
    {
        $user = Auth::user();

        // Check permission
        if (!($user->role === 'admin' || ($user->role === 'ketua_ukm' && $event->ukm_id === $user->ukm_id))) {
            abort(403, 'Tidak memiliki akses untuk melihat data absensi.');
        }

        $attendances = $event->attendances()
                            ->with(['user', 'registration'])
                            ->orderBy('submitted_at', 'desc')
                            ->paginate(20);

        return view('admin.events.attendances', compact('event', 'attendances'));
    }

    /**
     * Admin/Ketua UKM: Verify attendance
     */
    public function verify(Request $request, Event $event, EventAttendance $attendance)
    {
        $user = Auth::user();

        // Check permission
        if (!($user->role === 'admin' || ($user->role === 'ketua_ukm' && $event->ukm_id === $user->ukm_id))) {
            abort(403, 'Tidak memiliki akses untuk verifikasi absensi.');
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
     * Admin/Ketua UKM: Bulk verify attendances
     */
    public function bulkVerify(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check permission
        if (!($user->role === 'admin' || ($user->role === 'ketua_ukm' && $event->ukm_id === $user->ukm_id))) {
            abort(403, 'Tidak memiliki akses untuk verifikasi absensi.');
        }

        $request->validate([
            'attendance_ids' => 'required|array',
            'attendance_ids.*' => 'exists:event_attendances,id',
            'action' => 'required|in:verify,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendances = EventAttendance::whereIn('id', $request->attendance_ids)
                                    ->where('event_id', $event->id)
                                    ->get();

        $count = 0;
        foreach ($attendances as $attendance) {
            if ($request->action === 'verify') {
                $attendance->verify($user->id, $request->notes);
            } else {
                $attendance->reject($user->id, $request->notes);
            }
            $count++;
        }

        $action = $request->action === 'verify' ? 'diverifikasi' : 'ditolak';
        return back()->with('success', "{$count} absensi berhasil {$action}.");
    }

    /**
     * Generate certificate manually (admin only)
     */
    public function generateCertificate(Event $event, EventAttendance $attendance)
    {
        $user = Auth::user();

        // Check permission (admin only)
        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat generate sertifikat manual.');
        }

        try {
            $filename = $this->certificateService->generateCertificate($attendance);
            return back()->with('success', 'Sertifikat berhasil digenerate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate sertifikat: ' . $e->getMessage());
        }
    }
}
