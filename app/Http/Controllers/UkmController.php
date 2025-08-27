<?php

namespace App\Http\Controllers;

use App\Models\Ukm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UkmController extends Controller
{
    /**
     * Display a listing of UKMs.
     */
    public function index(Request $request)
    {
        $query = Ukm::active()->with(['leader']);

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by recruiting status
        if ($request->filled('recruiting')) {
            $query->recruiting();
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'members':
                $query->orderBy('current_members', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('established_date', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $ukms = $query->with('achievements')->paginate(12);

        // Get categories for filter
        $categories = Ukm::select('category')
            ->distinct()
            ->pluck('category')
            ->map(function ($category) {
                return [
                    'value' => $category,
                    'label' => ucfirst($category)
                ];
            });

        // Generate SEO data
        $seoData = [
            'title' => 'Daftar UKM - Unit Kegiatan Mahasiswa Telkom Jakarta',
            'description' => 'Temukan dan bergabung dengan ' . $ukms->total() . ' Unit Kegiatan Mahasiswa aktif di Telkom Jakarta. Pilih UKM sesuai minat dan bakat Anda untuk mengembangkan potensi diri.',
            'keywords' => 'daftar UKM, unit kegiatan mahasiswa, telkom jakarta, organisasi mahasiswa, ekstrakurikuler',
            'canonical' => route('ukms.index'),
        ];

        return view('ukms.index', compact('ukms', 'categories', 'seoData'));
    }

    /**
     * Display the specified UKM.
     */
    public function show(Ukm $ukm)
    {
        // Load relationships using eager loading
        $ukm->load(['leader', 'activeMembers']);

        // Load achievements and events separately to avoid null issues
        $achievements = $ukm->achievements()->get();
        $publishedEvents = $ukm->publishedEvents()->upcoming()->limit(5)->get();

        // Check if current user is a member
        $isMember = false;
        $membershipStatus = null;

        if (Auth::check()) {
            $membership = $ukm->members()
                ->where('ukm_members.user_id', Auth::id())
                ->first();

            if ($membership) {
                $isMember = true;
                $membershipStatus = $membership->pivot->status;
            }
        }

        // Generate SEO data
        $seoData = [
            'title' => $ukm->name . ' - UKM Telkom Jakarta',
            'description' => \App\Helpers\SeoHelper::cleanText($ukm->description, 160),
            'keywords' => \App\Helpers\SeoHelper::generateKeywords($ukm->description, [$ukm->name, $ukm->category]),
            'image' => $ukm->logo ? asset('storage/' . $ukm->logo) : asset('images/default-ukm.png'),
            'canonical' => route('ukms.show', $ukm->slug),
        ];

        return view('ukms.show', compact('ukm', 'achievements', 'publishedEvents', 'isMember', 'membershipStatus', 'seoData'));
    }

    /**
     * Show registration form for UKM.
     */
    public function showRegistrationForm($slug)
    {
        $ukm = Ukm::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Check if user is student
        if ($user->role !== 'student') {
            return redirect()->route('ukms.show', $ukm->slug)
                           ->with('error', 'Hanya mahasiswa yang dapat mendaftar UKM.');
        }

        // Check if UKM can accept new members
        if ($ukm->status !== 'active' || $ukm->registration_status !== 'open') {
            return redirect()->route('ukms.show', $ukm->slug)
                           ->with('error', 'Pendaftaran UKM ini sedang ditutup.');
        }

        if ($ukm->current_members >= $ukm->max_members) {
            return redirect()->route('ukms.show', $ukm->slug)
                           ->with('error', 'UKM ini sudah mencapai kapasitas maksimal.');
        }

        // Check if user is already a member or has pending application
        $existingMembership = $ukm->members()->where('ukm_members.user_id', $user->id)->first();
        if ($existingMembership) {
            $status = $existingMembership->pivot->status;
            if ($status === 'active') {
                return redirect()->route('ukms.show', $ukm->slug)
                               ->with('error', 'Anda sudah menjadi anggota UKM ini.');
            } elseif ($status === 'pending') {
                return redirect()->route('ukms.show', $ukm->slug)
                               ->with('info', 'Pendaftaran Anda sedang dalam proses review. Mohon tunggu keputusan dari ketua UKM.');
            }
            // If status is 'rejected' or 'inactive', allow re-registration by continuing to show form
        }
        // If no existing membership (member was completely removed), allow registration

        return view('ukms.registration-form', compact('ukm'));
    }

    /**
     * Submit UKM registration.
     */
    public function submitRegistration(Request $request, $slug)
    {
        $ukm = Ukm::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Validate request
        $request->validate([
            'previous_experience' => 'nullable|string|max:2000',
            'skills_interests' => 'required|string|max:1000',
            'reason_joining' => 'required|string|max:1000',
            'preferred_division' => 'required|string|max:255',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'agreement' => 'required|accepted',
        ]);

        // Check if user is student
        if ($user->role !== 'student') {
            return back()->with('error', 'Hanya mahasiswa yang dapat mendaftar UKM.');
        }

        // Check if UKM can accept new members
        if ($ukm->status !== 'active' || $ukm->registration_status !== 'open') {
            return back()->with('error', 'Pendaftaran UKM ini sedang ditutup.');
        }

        // Check if user is already a member or has pending application
        $existingMembership = $ukm->members()->where('ukm_members.user_id', $user->id)->first();
        if ($existingMembership) {
            $status = $existingMembership->pivot->status;
            if ($status === 'active') {
                return back()->with('error', 'Anda sudah menjadi anggota UKM ini.');
            } elseif ($status === 'pending') {
                return back()->with('error', 'Pendaftaran Anda sedang dalam proses review. Mohon tunggu keputusan dari ketua UKM.');
            }
            // If status is 'rejected' or 'inactive', allow re-registration by updating existing record
        }

        // Handle CV upload
        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('ukm_registrations/cvs', 'public');
        }

        // Create or update membership with pending status
        if ($existingMembership && ($existingMembership->pivot->status === 'inactive' || $existingMembership->pivot->status === 'rejected')) {
            // Update existing membership (for removed members or rejected applicants who want to re-apply)
            $ukm->members()->updateExistingPivot($user->id, [
                'status' => 'pending',
                'previous_experience' => $request->previous_experience,
                'skills_interests' => $request->skills_interests,
                'reason_joining' => $request->reason_joining,
                'preferred_division' => $request->preferred_division,
                'cv_file' => $cvPath,
                'applied_at' => now(),
                'left_date' => null,
                'notes' => null,
                'rejected_at' => null,
                'rejected_by' => null,
                'rejection_reason' => null,
            ]);
            $isReapplication = true;
        } else {
            // Create new membership (first time application or completely removed member)
            $ukm->members()->attach($user->id, [
                'role' => 'member',
                'status' => 'pending',
                'previous_experience' => $request->previous_experience,
                'skills_interests' => $request->skills_interests,
                'reason_joining' => $request->reason_joining,
                'preferred_division' => $request->preferred_division,
                'cv_file' => $cvPath,
                'applied_at' => now(),
            ]);
            $isReapplication = $existingMembership ? false : false; // No existing membership means new application
        }

        $message = $isReapplication
                   ? 'Pendaftaran ulang berhasil dikirim! Anda akan mendapat notifikasi hasil seleksi.'
                   : 'Pendaftaran berhasil dikirim! Anda akan mendapat notifikasi hasil seleksi.';

        return redirect()->route('ukms.show', $ukm->slug)
                        ->with('success', $message);
    }

    /**
     * Join a UKM (redirect to registration form).
     */
    public function join($slug)
    {
        // Redirect to registration form instead of direct join
        return redirect()->route('ukms.registration-form', $slug);
    }

    /**
     * Leave a UKM.
     */
    public function leave(Request $request, $slug)
    {
        $ukm = Ukm::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Check if user is a member
        $existingMembership = $ukm->members()->where('ukm_members.user_id', $user->id)->wherePivot('status', 'active')->first();
        if (!$existingMembership) {
            return back()->with('error', 'Anda bukan anggota UKM ini.');
        }

        // Validate request
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Update membership status
        $ukm->members()->updateExistingPivot($user->id, [
            'status' => 'inactive',
            'left_date' => now(),
            'reason_for_leaving' => $request->reason,
        ]);

        // Update member count to actual active count
        $ukm->updateMemberCount();

        return back()->with('success', 'Anda telah keluar dari UKM ini.');
    }

    /**
     * Get UKM members (for AJAX requests).
     */
    public function members(Ukm $ukm)
    {
        $members = $ukm->activeMembers()
            ->withPivot(['role', 'joined_date'])
            ->orderBy('ukm_members.role', 'desc')
            ->orderBy('ukm_members.joined_date', 'asc')
            ->get();

        return response()->json($members);
    }

    /**
     * Get UKM events (for AJAX requests).
     */
    public function events(Ukm $ukm)
    {
        $events = $ukm->publishedEvents()
            ->orderBy('start_datetime', 'desc')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    /**
     * Show student's UKM application status.
     */
    public function myApplications()
    {
        $user = Auth::user();

        // Check if user is student
        if ($user->role !== 'student') {
            return redirect()->route('dashboard')->with('error', 'Halaman ini hanya untuk mahasiswa.');
        }

        // Get all UKM memberships with different statuses
        $applications = $user->ukms()
            ->withPivot([
                'role', 'status', 'joined_date', 'left_date', 'notes',
                'previous_experience', 'skills_interests', 'reason_joining',
                'preferred_division', 'cv_file', 'applied_at', 'approved_at',
                'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
            ])
            ->orderBy('ukm_members.applied_at', 'desc')
            ->get();

        // Group applications by status
        $activeApplications = $applications->where('pivot.status', 'active');
        $pendingApplications = $applications->where('pivot.status', 'pending');
        $rejectedApplications = $applications->where('pivot.status', 'rejected');
        $inactiveApplications = $applications->where('pivot.status', 'inactive');

        return view('ukms.my-applications', compact(
            'applications',
            'activeApplications',
            'pendingApplications',
            'rejectedApplications',
            'inactiveApplications'
        ));
    }
}
