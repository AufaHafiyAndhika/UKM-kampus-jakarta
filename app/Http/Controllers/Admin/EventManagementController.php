<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventManagementController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::with(['ukm']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('ukm', function ($ukmQuery) use ($search) {
                      $ukmQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // UKM filter
        if ($request->filled('ukm_id')) {
            $query->where('ukm_id', $request->ukm_id);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('start_datetime', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('start_datetime', '<=', $request->date_to . ' 23:59:59');
        }

        $events = $query->orderBy('start_datetime', 'desc')->paginate(15);

        // Get filter options
        $ukms = Ukm::orderBy('name')->get();
        $statuses = ['waiting', 'published', 'ongoing', 'completed', 'cancelled'];
        $types = ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other'];

        return view('admin.events.index', compact('events', 'ukms', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $ukms = Ukm::orderBy('name')->get();
        $types = ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other'];
        $statuses = ['draft', 'published', 'ongoing', 'completed', 'cancelled'];

        return view('admin.events.create', compact('ukms', 'types', 'statuses'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'type' => 'required|in:workshop,seminar,competition,meeting,social,other',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date', // Removed after:now for testing
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_start' => 'nullable|date|before:start_datetime',
            'registration_end' => 'nullable|date|before:start_datetime',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'requires_approval' => 'boolean',
            'certificate_available' => 'boolean',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $eventData = $request->only([
            'ukm_id', 'title', 'description', 'requirements', 'type', 'location',
            'start_datetime', 'end_datetime', 'registration_start', 'registration_end',
            'max_participants', 'registration_fee', 'status', 'notes'
        ]);

        // Generate slug
        $eventData['slug'] = Str::slug($request->title);

        // Handle boolean fields
        $eventData['requires_approval'] = $request->has('requires_approval');
        $eventData['certificate_available'] = $request->has('certificate_available');

        // Handle contact person
        $contactPerson = [];
        if ($request->filled('contact_person_name')) {
            $contactPerson['name'] = $request->contact_person_name;
        }
        if ($request->filled('contact_person_phone')) {
            $contactPerson['phone'] = $request->contact_person_phone;
        }
        if ($request->filled('contact_person_email')) {
            $contactPerson['email'] = $request->contact_person_email;
        }
        $eventData['contact_person'] = $contactPerson;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('events/posters', 'public');
            $eventData['poster'] = $posterPath;
        }

        Event::create($eventData);

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load(['ukm', 'registrations.user']);
        
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $ukms = Ukm::orderBy('name')->get();
        $types = ['workshop', 'seminar', 'competition', 'meeting', 'social', 'other'];
        $statuses = ['draft', 'published', 'ongoing', 'completed', 'cancelled'];

        return view('admin.events.edit', compact('event', 'ukms', 'types', 'statuses'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'type' => 'required|in:workshop,seminar,competition,meeting,social,other',
            'location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_start' => 'nullable|date|before:start_datetime',
            'registration_end' => 'nullable|date|before:start_datetime',
            'max_participants' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'requires_approval' => 'boolean',
            'certificate_available' => 'boolean',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'contact_person_email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $eventData = $request->only([
            'ukm_id', 'title', 'description', 'requirements', 'type', 'location',
            'start_datetime', 'end_datetime', 'registration_start', 'registration_end',
            'max_participants', 'registration_fee', 'status', 'notes'
        ]);

        // Update slug if title changed
        if ($event->title !== $request->title) {
            $eventData['slug'] = Str::slug($request->title);
        }

        // Handle boolean fields
        $eventData['requires_approval'] = $request->has('requires_approval');
        $eventData['certificate_available'] = $request->has('certificate_available');

        // Handle contact person
        $contactPerson = [];
        if ($request->filled('contact_person_name')) {
            $contactPerson['name'] = $request->contact_person_name;
        }
        if ($request->filled('contact_person_phone')) {
            $contactPerson['phone'] = $request->contact_person_phone;
        }
        if ($request->filled('contact_person_email')) {
            $contactPerson['email'] = $request->contact_person_email;
        }
        $eventData['contact_person'] = $contactPerson;

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            
            $posterPath = $request->file('poster')->store('events/posters', 'public');
            $eventData['poster'] = $posterPath;
        }

        $event->update($eventData);

        // Force update status based on new dates if it's a published event
        if ($event->status === 'published') {
            $event->updateStatusBasedOnDates();
        }

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        // Delete poster if exists
        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Event berhasil dihapus.');
    }

    /**
     * Publish event.
     */
    public function publish(Event $event)
    {
        $event->update(['status' => 'published']);

        return redirect()->back()
                        ->with('success', 'Event berhasil dipublikasikan.');
    }

    /**
     * Cancel event.
     */
    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);

        return redirect()->back()
                        ->with('success', 'Event berhasil dibatalkan.');
    }

    /**
     * Approve event (change status from draft to published).
     */
    public function approve(Request $request, Event $event)
    {
        // Allow approval for 'draft' or 'waiting' status
        if (!in_array($event->status, ['draft', 'waiting'])) {
            return back()->with('error', 'Hanya event dengan status draft atau waiting yang dapat disetujui.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $event->update([
            'status' => 'published',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'approval_notes' => $request->notes,
        ]);

        return back()->with('success', 'Event berhasil disetujui dan dipublikasikan.');
    }

    /**
     * Reject event (change status from draft to rejected).
     */
    public function reject(Request $request, Event $event)
    {
        // Allow rejection for 'draft' or 'waiting' status
        if (!in_array($event->status, ['draft', 'waiting'])) {
            return back()->with('error', 'Hanya event dengan status draft atau waiting yang dapat ditolak.');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $event->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->notes,
        ]);

        return back()->with('success', 'Event berhasil ditolak.');
    }

    /**
     * Cancel event (change status to cancelled).
     */
    public function cancelEvent(Request $request, Event $event)
    {
        if (in_array($event->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Event yang sudah selesai atau dibatalkan tidak dapat dibatalkan lagi.');
        }

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $event->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancellation_reason' => $request->notes,
        ]);

        return back()->with('success', 'Event berhasil dibatalkan.');
    }

    /**
     * Update event status automatically for all events.
     */
    public function updateAllStatuses()
    {
        $events = Event::whereIn('status', ['published', 'ongoing'])->get();

        $updated = 0;
        foreach ($events as $event) {
            $oldStatus = $event->status;
            $event->updateStatusAutomatically();

            if ($event->status !== $oldStatus) {
                $updated++;
            }
        }

        return back()->with('success', "Status $updated event berhasil diperbarui.");
    }
}
