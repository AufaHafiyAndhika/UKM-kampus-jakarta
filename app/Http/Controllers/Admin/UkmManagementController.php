<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ukm;
use App\Models\UkmAchievement;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UkmManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ukm::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->get('category') != '') {
            $query->where('category', $request->get('category'));
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('ukms.status', $request->get('status'));
        }

        $ukms = $query->with('leader')->orderBy('created_at', 'desc')->paginate(15);

        $categories = ['academic', 'sports', 'arts', 'religion', 'social', 'technology', 'entrepreneurship', 'other'];

        return view('admin.ukms.index', compact('ukms', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ['academic', 'sports', 'arts', 'religion', 'social', 'technology', 'entrepreneurship', 'other'];
        $leaders = User::where('role', 'student')->where('users.status', 'active')->get();
        $ketuaUkmUsers = User::where('role', 'ketua_ukm')->where('users.status', 'active')->get();

        return view('admin.ukms.create', compact('categories', 'leaders', 'ketuaUkmUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'category' => 'required|in:academic,sports,arts,religion,social,technology,entrepreneurship,other',
            'max_members' => 'required|integer|min:1',
            'meeting_schedule' => 'nullable|string',
            'meeting_location' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'established_date' => 'nullable|date',
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
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organization_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_instagram' => 'nullable|string',
            'contact_website' => 'nullable|url',
        ]);

        $contactInfo = [];
        if ($request->contact_email) $contactInfo['email'] = $request->contact_email;
        if ($request->contact_phone) $contactInfo['phone'] = $request->contact_phone;
        if ($request->contact_instagram) $contactInfo['instagram'] = $request->contact_instagram;
        if ($request->contact_website) $contactInfo['website'] = $request->contact_website;

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('ukms/logos', 'public');
        }

        // Handle background image upload
        $backgroundImagePath = null;
        if ($request->hasFile('background_image')) {
            $backgroundImagePath = $request->file('background_image')->store('ukms/backgrounds', 'public');
        }

        // Handle organization structure upload
        $organizationStructurePath = null;
        if ($request->hasFile('organization_structure')) {
            $organizationStructurePath = $request->file('organization_structure')->store('ukms/organization_structures', 'public');
        }

        $ukm = Ukm::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'category' => $request->category,
            'max_members' => $request->max_members,
            'current_members' => 0,
            'meeting_schedule' => $request->meeting_schedule,
            'meeting_location' => $request->meeting_location,
            'leader_id' => $request->leader_id,
            'established_date' => $request->established_date,
            'logo' => $logoPath,
            'background_image' => $backgroundImagePath,
            'organization_structure' => $organizationStructurePath,
            'contact_info' => json_encode($contactInfo),
            'status' => 'active',
            'is_recruiting' => true,
        ]);

        // Handle achievements
        if ($request->has('achievements') && is_array($request->achievements)) {
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

        return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ukm $ukm)
    {
        // Load relationships with proper pivot data
        $ukm->load([
            'leader',
            'events' => function($query) {
                $query->orderBy('start_datetime', 'desc')->limit(5);
            }
        ]);

        // Load members with pivot data manually to ensure proper relationship
        $members = $ukm->members()->withPivot([
            'role', 'status', 'joined_date', 'left_date', 'notes',
            'previous_experience', 'skills_interests', 'reason_joining',
            'preferred_division', 'cv_file', 'applied_at', 'approved_at',
            'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
        ])->get();

        // Set the members relationship
        $ukm->setRelation('members', $members);

        // Add counts
        $ukm->members_count = $members->count();
        $ukm->events_count = $ukm->events->count();

        return view('admin.ukms.show', compact('ukm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ukm $ukm)
    {
        $ukm->load('achievements');
        // Ensure achievements is a collection
        if (!($ukm->achievements instanceof \Illuminate\Database\Eloquent\Collection)) {
            $ukm->achievements = collect();
        }
        $categories = ['academic', 'sports', 'arts', 'religion', 'social', 'technology', 'entrepreneurship', 'other'];
        $leaders = User::where('role', 'student')->where('users.status', 'active')->get();
        $ketuaUkmUsers = User::where('role', 'ketua_ukm')->where('users.status', 'active')->get();

        return view('admin.ukms.edit', compact('ukm', 'categories', 'leaders', 'ketuaUkmUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ukm $ukm)
    {
        try {
            $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:ukms,slug,' . $ukm->id,
            'description' => 'required|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'category' => 'required|in:academic,sports,arts,religion,social,technology,entrepreneurship,other',
            'meeting_schedule' => 'nullable|string',
            'meeting_location' => 'nullable|string',
            'leader_id' => 'nullable|exists:users,id',
            'established_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended',
            'registration_status' => 'required|in:open,closed',
            'requirements' => 'nullable|string',
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
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organization_structure' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_instagram' => 'nullable|string',
            'contact_website' => 'nullable|url',
        ]);

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

        // Handle background image upload
        $backgroundImagePath = $ukm->background_image; // Keep existing background by default
        if ($request->hasFile('background_image')) {
            // Delete old background image if exists
            if ($ukm->background_image && Storage::disk('public')->exists($ukm->background_image)) {
                Storage::disk('public')->delete($ukm->background_image);
            }

            // Store new background image
            $backgroundImagePath = $request->file('background_image')->store('ukms/backgrounds', 'public');
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

        $contactInfo = [];
        if ($request->contact_email) $contactInfo['email'] = $request->contact_email;
        if ($request->contact_phone) $contactInfo['phone'] = $request->contact_phone;
        if ($request->contact_instagram) $contactInfo['instagram'] = $request->contact_instagram;
        if ($request->contact_website) $contactInfo['website'] = $request->contact_website;

        // Handle leader change
        $oldLeaderId = $ukm->leader_id;
        $newLeaderId = $request->leader_id;

        // If leader is being changed
        if ($oldLeaderId != $newLeaderId) {
            // Remove ketua_ukm role from old leader if they exist and don't lead other UKMs
            if ($oldLeaderId) {
                $oldLeader = User::find($oldLeaderId);
                if ($oldLeader && $oldLeader->role === 'ketua_ukm') {
                    $otherUkmsCount = Ukm::where('leader_id', $oldLeaderId)->where('id', '!=', $ukm->id)->count();
                    if ($otherUkmsCount === 0) {
                        $oldLeader->update(['role' => 'student']);
                        $oldLeader->syncRoleWithSpatie();
                    }
                }
            }

            // Assign ketua_ukm role to new leader if they exist
            if ($newLeaderId) {
                $newLeader = User::find($newLeaderId);
                if ($newLeader && $newLeader->role === 'student') {
                    $newLeader->update(['role' => 'ketua_ukm']);
                    $newLeader->syncRoleWithSpatie();
                }
            }
        }

        $ukm->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'category' => $request->category,
            'meeting_schedule' => $request->meeting_schedule,
            'meeting_location' => $request->meeting_location,
            'leader_id' => $request->leader_id,
            'established_date' => $request->established_date,
            'contact_info' => json_encode($contactInfo),
            'status' => $request->status,
            'registration_status' => $request->registration_status,
            'requirements' => $request->requirements,
            'logo' => $logoPath,
            'background_image' => $backgroundImagePath,
            'organization_structure' => $organizationStructurePath,
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

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('admin.ukms.index')->with([
                'success' => 'UKM berhasil diperbarui!',
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
     * Remove leader from UKM and convert back to student.
     */
    public function removeLeader(Ukm $ukm)
    {
        if (!$ukm->leader_id) {
            return redirect()->route('admin.ukms.show', $ukm)
                           ->with('error', 'UKM ini tidak memiliki ketua.');
        }

        $leader = User::find($ukm->leader_id);

        if ($leader) {
            // Check if leader leads other UKMs
            $otherUkmsCount = Ukm::where('leader_id', $leader->id)->where('id', '!=', $ukm->id)->count();

            // If this is the only UKM they lead, convert back to student
            if ($otherUkmsCount === 0) {
                $leader->update(['role' => 'student']);
                $leader->syncRoleWithSpatie();
            }
        }

        // Remove leader from UKM
        $ukm->update(['leader_id' => null]);

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.ukms.show', $ukm)
                        ->with('success', 'Ketua UKM berhasil diturunkan dan role dikembalikan ke mahasiswa.');
    }

    /**
     * Show all members of a specific UKM.
     */
    public function members(Request $request, Ukm $ukm)
    {
        $query = $ukm->members()->withPivot([
            'role', 'status', 'joined_date', 'left_date', 'notes',
            'previous_experience', 'skills_interests', 'reason_joining',
            'preferred_division', 'cv_file', 'applied_at', 'approved_at',
            'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
        ]);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('ukm_members.status', $request->status);
        }

        // Apply role filter
        if ($request->filled('role')) {
            $query->where('ukm_members.role', $request->role);
        }

        $members = $query->orderBy('ukm_members.joined_date', 'desc')
                        ->paginate(20);

        // Get statistics
        $totalMembers = $ukm->members()->count();
        $activeMembers = $ukm->members()->where('ukm_members.status', 'active')->count();
        $pendingMembers = $ukm->members()->where('ukm_members.status', 'pending')->count();
        $rejectedMembers = $ukm->members()->where('ukm_members.status', 'rejected')->count();

        return view('admin.ukms.members', compact(
            'ukm',
            'members',
            'totalMembers',
            'activeMembers',
            'pendingMembers',
            'rejectedMembers'
        ));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ukm $ukm)
    {
        $ukm->delete();
        return redirect()->route('admin.ukms.index')->with('success', 'UKM berhasil dihapus.');
    }
}
