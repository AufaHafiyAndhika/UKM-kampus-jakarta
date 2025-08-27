<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        // Update all published events status before displaying
        $this->updateAllEventStatuses();

        // Include all events that are published, ongoing, or completed for filtering
        $query = Event::whereIn('status', ['published', 'ongoing', 'completed'])->with(['ukm']);

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by UKM
        if ($request->filled('ukm')) {
            $query->where('ukm_id', $request->ukm);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('start_datetime', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('start_datetime', '<=', $request->date_to);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by status - use database status instead of date-based scopes
        $status = $request->get('status', 'upcoming');
        switch ($status) {
            case 'ongoing':
                $query->where('status', 'ongoing');
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            default: // upcoming
                $query->where('status', 'published');
        }

        // Sort options
        $sort = $request->get('sort', 'date');
        switch ($sort) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'participants':
                $query->orderBy('current_participants', 'desc');
                break;
            default:
                $query->orderBy('start_datetime', 'asc');
        }

        $events = $query->paginate(12);

        // Get event types for filter
        $types = Event::select('type')
            ->distinct()
            ->pluck('type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => ucfirst($type)
                ];
            });

        // Get UKMs for filter
        $ukms = \App\Models\Ukm::active()
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('events.index', compact('events', 'types', 'ukms', 'status'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Update event status before showing
        $event->updateStatusBasedOnDates();

        $event->load(['ukm.leader', 'approvedRegistrations.user']);

        // Check if current user is registered
        $isRegistered = false;
        $userRegistration = null;

        if (Auth::check()) {
            $userRegistration = $event->registrations()
                ->where('user_id', Auth::id())
                ->first();

            // Consider as registered if status is approved or pending (but not rejected)
            if ($userRegistration && in_array($userRegistration->status, ['approved', 'pending'])) {
                $isRegistered = true;
            }
        }

        // Check if registration is open
        $canRegister = $event->isRegistrationOpen() && !$isRegistered;

        return view('events.show', compact('event', 'isRegistered', 'userRegistration', 'canRegister'));
    }

    /**
     * Show event registration form.
     */
    public function showRegistrationForm(Event $event)
    {
        $user = Auth::user();

        // Check if user is student
        if ($user->role !== 'student') {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Hanya mahasiswa yang dapat mendaftar event.');
        }

        // Check if registration is open
        if (!$event->isRegistrationOpen()) {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Pendaftaran untuk event ini sudah ditutup.');
        }

        // Check if user is already registered and approved
        $existingRegistration = $event->registrations()->where('user_id', $user->id)->first();
        if ($existingRegistration && $existingRegistration->status === 'approved') {
            return redirect()->route('events.show', $event->slug)
                           ->with('info', 'Anda sudah terdaftar untuk event ini.');
        }

        // Check if event is full (count approved registrations)
        $approvedCount = $event->registrations()->where('status', 'approved')->count();
        if ($event->max_participants && $approvedCount >= $event->max_participants) {
            return redirect()->route('events.show', $event->slug)
                           ->with('error', 'Event ini sudah penuh. Kuota peserta: ' . $event->max_participants);
        }

        return view('events.register', compact('event'));
    }

    /**
     * Register for an event.
     */
    public function register(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if registration is open
        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'Pendaftaran untuk kegiatan ini sudah ditutup.');
        }

        // Check if user is already registered and approved
        $existingRegistration = $event->registrations()->where('user_id', $user->id)->first();
        if ($existingRegistration && $existingRegistration->status === 'approved') {
            return back()->with('info', 'Anda sudah terdaftar untuk kegiatan ini.');
        }

        // Validate request
        $rules = [
            'motivation' => 'required|string|max:1000',
            'availability.full_attendance' => 'required|in:yes,no',
            'availability.transportation' => 'nullable|string',
            'availability.emergency_contact' => 'nullable|string|max:255',
            'registration_notes' => 'nullable|string|max:1000',
            'agreement' => 'required|accepted',
        ];

        // Add partial reason validation if needed
        if ($request->input('availability.full_attendance') === 'no') {
            $rules['availability.partial_reason'] = 'required|string|max:500';
        }

        // Add payment proof validation if required
        if ($event->registration_fee > 0) {
            $rules['payment_proof'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        }

        $request->validate($rules);

        // Double-check participant limit before creating registration
        $approvedCount = $event->registrations()->where('status', 'approved')->count();
        if ($event->max_participants && $approvedCount >= $event->max_participants) {
            return back()->with('error', 'Event sudah penuh. Kuota peserta: ' . $event->max_participants);
        }

        // Handle payment proof upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        // Determine registration status based on event settings
        $registrationStatus = $event->requires_approval ? 'pending' : 'approved';
        $approvedAt = $event->requires_approval ? null : now();
        $approvedBy = $event->requires_approval ? null : null; // Auto-approved if no approval required

        // Handle existing registration or create new one
        if ($existingRegistration) {
            // Update existing registration with new data
            $existingRegistration->update([
                'status' => $registrationStatus,
                'motivation' => $request->motivation,
                'availability_form' => $request->availability,
                'registration_notes' => $request->registration_notes,
                'additional_data' => $request->only(['phone', 'emergency_contact']),
                'payment_proof' => $paymentProofPath ?: $existingRegistration->payment_proof,
                'payment_status' => $event->registration_fee > 0 ? 'pending' : 'verified',
                'approved_at' => $approvedAt,
                'approved_by' => $approvedBy,
            ]);
        } else {
            // Create new registration
            EventRegistration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => $registrationStatus,
                'motivation' => $request->motivation,
                'availability_form' => $request->availability,
                'registration_notes' => $request->registration_notes,
                'additional_data' => $request->only(['phone', 'emergency_contact']),
                'payment_proof' => $paymentProofPath,
                'payment_status' => $event->registration_fee > 0 ? 'pending' : 'verified',
                'approved_at' => $approvedAt,
                'approved_by' => $approvedBy,
            ]);
        }

        // Update participant count only if auto-approved
        if (!$event->requires_approval) {
            $event->updateParticipantCount();
        }

        // Set appropriate message based on approval requirement
        if ($event->requires_approval) {
            $message = 'Pendaftaran berhasil dikirim! Menunggu persetujuan dari ketua UKM.';
        } else {
            $message = 'Pendaftaran berhasil! Anda telah terdaftar untuk kegiatan ini.';
        }

        return redirect()->route('events.index')->with('success', $message);
    }

    /**
     * Cancel event registration.
     */
    public function cancelRegistration(Request $request, Event $event)
    {
        $user = Auth::user();

        $registration = $event->registrations()
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return back()->with('error', 'Anda tidak terdaftar untuk event ini.');
        }

        // Check if cancellation is allowed using model method
        if (!$registration->canBeCancelled()) {
            return back()->with('error', 'Pembatalan tidak diizinkan kurang dari 24 jam sebelum event dimulai.');
        }

        // Validate cancellation reason
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        // Cancel registration using model method
        $registration->cancel($request->cancellation_reason);

        // Delete payment proof file if exists
        if ($registration->payment_proof) {
            Storage::disk('public')->delete($registration->payment_proof);
        }

        // Delete registration
        $registration->delete();

        // Update participant count
        $event->updateParticipantCount();

        return redirect()->route('events.index')->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    /**
     * Download event poster.
     */
    public function downloadPoster(Event $event)
    {
        if (!$event->poster || !Storage::disk('public')->exists($event->poster)) {
            abort(404, 'Poster tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($event->poster),
            $event->title . ' - Poster.jpg'
        );
    }

    /**
     * Get event participants (for AJAX requests).
     */
    public function participants(Event $event)
    {
        $participants = $event->approvedRegistrations()
            ->with('user:id,name,nim,faculty,major')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($participants);
    }

    /**
     * Update all event statuses based on current date
     */
    private function updateAllEventStatuses()
    {
        // Get all published, ongoing, and completed events that might need status updates
        $events = Event::whereIn('status', ['published', 'ongoing', 'completed'])->get();

        foreach ($events as $event) {
            $event->updateStatusBasedOnDates();
        }
    }
}
