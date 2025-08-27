<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ukm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class KetuaUkmManagementController extends Controller
{
    /**
     * Display a listing of ketua UKM.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'ketua_ukm')
                    ->with(['ledUkms']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ketuaUkms = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.ketua-ukm.index', compact('ketuaUkms'));
    }

    /**
     * Show the form for creating a new ketua UKM.
     */
    public function create()
    {
        // Get students who are not yet ketua UKM
        $students = User::where('role', 'student')
                       ->where('status', 'active')
                       ->get();

        return view('admin.ketua-ukm.create', compact('students'));
    }

    /**
     * Store a newly created ketua UKM.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Update role to ketua_ukm
        $user->update(['role' => 'ketua_ukm']);
        
        // Sync with Spatie Permission
        $user->syncRoleWithSpatie();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.ketua-ukm.index')->with([
            'success' => "Berhasil mengangkat {$user->name} sebagai Ketua UKM.",
            'toast' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => "{$user->name} telah berhasil diangkat sebagai Ketua UKM dengan akses penuh.",
                'duration' => 5000
            ]
        ]);
    }

    /**
     * Display the specified ketua UKM.
     */
    public function show(User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        $ketuaUkm->load(['ledUkms.members', 'ledUkms.events']);
        
        return view('admin.ketua-ukm.show', compact('ketuaUkm'));
    }

    /**
     * Show the form for editing the specified ketua UKM.
     */
    public function edit(User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        return view('admin.ketua-ukm.edit', compact('ketuaUkm'));
    }

    /**
     * Update the specified ketua UKM.
     */
    public function update(Request $request, User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        $request->validate([
            'nim' => 'required|string|unique:users,nim,' . $ketuaUkm->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $ketuaUkm->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'batch' => 'required|string|max:4',
            'status' => 'required|in:active,inactive,suspended',
            'role' => 'required|in:student,ketua_ukm,admin',
        ]);

        $oldRole = $ketuaUkm->role;
        $newRole = $request->role;

        $updateData = $request->only([
            'nim', 'name', 'email', 'phone', 'gender',
            'faculty', 'major', 'batch', 'status', 'role'
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        // Handle role change from ketua_ukm to student
        if ($oldRole === 'ketua_ukm' && $newRole === 'student') {
            // Remove user from all UKM leadership positions
            Ukm::where('leader_id', $ketuaUkm->id)->update(['leader_id' => null]);
        }

        $ketuaUkm->update($updateData);

        // Sync role with Spatie Permission
        $ketuaUkm->syncRoleWithSpatie();

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $message = 'Data ketua UKM berhasil diperbarui.';
        $toastMessage = "Data ketua UKM {$ketuaUkm->name} telah berhasil diperbarui.";

        if ($oldRole === 'ketua_ukm' && $newRole === 'student') {
            $message .= ' User telah diturunkan dari semua posisi ketua UKM.';
            $toastMessage = "{$ketuaUkm->name} telah diturunkan dari ketua UKM menjadi mahasiswa biasa.";
        } elseif ($oldRole !== 'ketua_ukm' && $newRole === 'ketua_ukm') {
            $message .= ' User telah diangkat sebagai ketua UKM.';
            $toastMessage = "{$ketuaUkm->name} telah diangkat sebagai ketua UKM dengan akses penuh.";
        }

        return redirect()->route('admin.ketua-ukm.index')->with([
            'success' => $message,
            'toast' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => $toastMessage,
                'duration' => 5000
            ]
        ]);
    }

    /**
     * Remove ketua UKM status (convert back to student).
     */
    public function destroy(User $ketuaUkm)
    {
        Log::info('Destroy method called for ketua UKM', [
            'user_id' => $ketuaUkm->id,
            'user_name' => $ketuaUkm->name,
            'user_role' => $ketuaUkm->role,
            'led_ukms_count' => $ketuaUkm->ledUkms()->count()
        ]);

        if ($ketuaUkm->role !== 'ketua_ukm') {
            Log::warning('Attempted to destroy non-ketua UKM user', [
                'user_id' => $ketuaUkm->id,
                'actual_role' => $ketuaUkm->role
            ]);
            return redirect()->route('admin.ketua-ukm.index')->with([
                'error' => 'User bukan ketua UKM!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'User yang dipilih bukan ketua UKM.',
                    'duration' => 6000
                ]
            ]);
        }

        // Check if user is leading any UKM
        if ($ketuaUkm->ledUkms()->count() > 0) {
            Log::warning('Attempted to destroy ketua UKM who is still leading UKMs', [
                'user_id' => $ketuaUkm->id,
                'led_ukms_count' => $ketuaUkm->ledUkms()->count()
            ]);
            return redirect()->route('admin.ketua-ukm.index')->with([
                'error' => 'Tidak dapat menurunkan ketua UKM!',
                'toast' => [
                    'type' => 'warning',
                    'title' => 'Peringatan!',
                    'message' => 'Tidak dapat menurunkan ketua UKM yang masih memimpin UKM. Hapus assignment UKM terlebih dahulu.',
                    'duration' => 8000
                ]
            ]);
        }

        // Convert back to student
        $ketuaUkm->update(['role' => 'student']);
        $ketuaUkm->syncRoleWithSpatie();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Log::info('Successfully converted ketua UKM to student', [
            'user_id' => $ketuaUkm->id,
            'user_name' => $ketuaUkm->name
        ]);

        return redirect()->route('admin.ketua-ukm.index')->with([
            'success' => "Berhasil menurunkan {$ketuaUkm->name} dari ketua UKM menjadi mahasiswa.",
            'toast' => [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => "{$ketuaUkm->name} telah berhasil diturunkan dari ketua UKM menjadi mahasiswa biasa.",
                'duration' => 5000
            ]
        ]);
    }

    /**
     * Assign UKM to ketua UKM.
     */
    public function assignUkm(Request $request, User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        $request->validate([
            'ukm_id' => 'required|exists:ukms,id',
        ]);

        $ukm = Ukm::findOrFail($request->ukm_id);
        
        // Check if UKM already has a leader
        if ($ukm->leader_id && $ukm->leader_id !== $ketuaUkm->id) {
            return redirect()->route('admin.ketua-ukm.show', $ketuaUkm)
                           ->with('error', 'UKM sudah memiliki ketua.');
        }

        // Assign UKM to ketua
        $ukm->update(['leader_id' => $ketuaUkm->id]);

        return redirect()->route('admin.ketua-ukm.show', $ketuaUkm)
                        ->with('success', "Berhasil menugaskan {$ketuaUkm->name} sebagai ketua {$ukm->name}.");
    }

    /**
     * Remove UKM assignment from ketua UKM.
     */
    public function removeUkm(User $ketuaUkm, Ukm $ukm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        if ($ukm->leader_id !== $ketuaUkm->id) {
            return redirect()->route('admin.ketua-ukm.show', $ketuaUkm)
                           ->with('error', 'UKM bukan dipimpin oleh ketua UKM ini.');
        }

        // Remove assignment
        $ukm->update(['leader_id' => null]);

        return redirect()->route('admin.ketua-ukm.show', $ketuaUkm)
                        ->with('success', "Berhasil menghapus assignment {$ukm->name} dari {$ketuaUkm->name}.");
    }

    /**
     * Suspend ketua UKM.
     */
    public function suspend(User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        if ($ketuaUkm->status === 'suspended') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'Ketua UKM sudah dalam status suspended.');
        }

        $ketuaUkm->update(['status' => 'suspended']);

        return redirect()->route('admin.ketua-ukm.index')
                        ->with('success', "Berhasil suspend ketua UKM {$ketuaUkm->name}.");
    }

    /**
     * Activate ketua UKM.
     */
    public function activate(User $ketuaUkm)
    {
        if ($ketuaUkm->role !== 'ketua_ukm') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'User bukan ketua UKM.');
        }

        if ($ketuaUkm->status === 'active') {
            return redirect()->route('admin.ketua-ukm.index')
                           ->with('error', 'Ketua UKM sudah dalam status active.');
        }

        $ketuaUkm->update(['status' => 'active']);

        return redirect()->route('admin.ketua-ukm.index')
                        ->with('success', "Berhasil mengaktifkan ketua UKM {$ketuaUkm->name}.");
    }
}
