<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'student');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%");
            });
        }

        if ($request->has('faculty') && $request->get('faculty') != '') {
            $query->where('faculty', $request->get('faculty'));
        }

        if ($request->has('status') && $request->get('status') != '') {
            $query->where('users.status', $request->get('status'));
        }

        // Get per page from request, default to 20
        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $faculties = User::where('role', 'student')->distinct()->pluck('faculty');

        // Get total counts for statistics
        $totalStudents = User::where('role', 'student')->count();
        $activeStudents = User::where('role', 'student')->where('status', 'active')->count();
        $suspendedStudents = User::where('role', 'student')->where('status', 'suspended')->count();
        $pendingStudents = User::where('role', 'student')->where('status', 'pending')->count();

        return view('admin.users.index', compact(
            'users',
            'faculties',
            'totalStudents',
            'activeStudents',
            'suspendedStudents',
            'pendingStudents'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:users,nim',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'batch' => 'required|string|max:4',
        ]);

        User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'batch' => $request->batch,
            'role' => 'student',
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'ukms' => function($query) {
                $query->withPivot(['role', 'status', 'joined_date', 'left_date']);
            },
            'eventRegistrations.event.ukm',
            'attendances.event.ukm'
        ]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $oldRole = $user->role;
            $oldStatus = $user->status;

            $request->validate([
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'batch' => 'required|string|max:4',
            'status' => 'required|in:active,inactive,suspended',
            'role' => 'required|in:student,ketua_ukm,admin',
        ]);

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

        $user->update($updateData);

        // Sync role with Spatie Permission
        $user->syncRoleWithSpatie();

        // Clear any cached user data
        if (function_exists('cache')) {
            cache()->forget('user_' . $user->id);
        }

        // Clear Spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // If the current logged-in user is being updated, refresh their session
            if (Auth::check() && Auth::id() === $user->id) {
                Auth::setUser($user->fresh());
            }

            // Generate appropriate success message based on changes
            $message = "Data mahasiswa {$user->name} berhasil diperbarui.";
            $toastMessage = "Data mahasiswa telah berhasil diperbarui dengan informasi terbaru.";

            if ($oldRole !== $user->role) {
                if ($user->role === 'ketua_ukm') {
                    $message = "Berhasil mengangkat {$user->name} sebagai Ketua UKM.";
                    $toastMessage = "Mahasiswa telah berhasil diangkat sebagai Ketua UKM dengan akses penuh.";
                } elseif ($oldRole === 'ketua_ukm' && $user->role === 'student') {
                    $message = "Berhasil menurunkan {$user->name} dari Ketua UKM ke mahasiswa.";
                    $toastMessage = "Ketua UKM telah berhasil diturunkan menjadi mahasiswa biasa.";
                }
            }

            if ($oldStatus !== $user->status) {
                if ($user->status === 'suspended') {
                    $toastMessage .= " Status akun diubah menjadi suspended.";
                } elseif ($user->status === 'active') {
                    $toastMessage .= " Akun telah diaktifkan kembali.";
                }
            }

            return redirect()->route('admin.users.index')->with([
                'success' => $message,
                'toast' => [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => $toastMessage,
                    'duration' => 5000
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());

            return redirect()->back()->with([
                'error' => 'Gagal memperbarui data mahasiswa!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat memperbarui data mahasiswa. Silakan coba lagi.',
                    'duration' => 7000
                ]
            ])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if ($user->role === 'admin') {
                return redirect()->route('admin.users.index')->with([
                    'error' => 'Akses ditolak!',
                    'toast' => [
                        'type' => 'error',
                        'title' => 'Akses Ditolak!',
                        'message' => 'Tidak dapat menghapus akun admin.',
                        'duration' => 6000
                    ]
                ]);
            }

            $userName = $user->name;
            $userRole = $user->role;

            $user->delete();

            $message = "Mahasiswa {$userName} berhasil dihapus.";
            $toastMessage = "Data mahasiswa telah berhasil dihapus dari sistem.";

            if ($userRole === 'ketua_ukm') {
                $message = "Ketua UKM {$userName} berhasil dihapus.";
                $toastMessage = "Data ketua UKM telah berhasil dihapus dari sistem.";
            }

            return redirect()->route('admin.users.index')->with([
                'success' => $message,
                'toast' => [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => $toastMessage,
                    'duration' => 5000
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());

            return redirect()->back()->with([
                'error' => 'Gagal menghapus data!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.',
                    'duration' => 7000
                ]
            ]);
        }
    }

    /**
     * Activate user account
     */
    public function activate(User $user)
    {
        try {
            $user->update(['status' => 'active']);

            return redirect()->route('admin.users.index')->with([
                'success' => "Akun {$user->name} berhasil diaktifkan.",
                'toast' => [
                    'type' => 'success',
                    'title' => 'Berhasil!',
                    'message' => "Akun {$user->name} telah berhasil diaktifkan dan dapat mengakses sistem kembali.",
                    'duration' => 5000
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error activating user: ' . $e->getMessage());

            return redirect()->back()->with([
                'error' => 'Gagal mengaktifkan akun!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat mengaktifkan akun. Silakan coba lagi.',
                    'duration' => 7000
                ]
            ]);
        }
    }

    /**
     * Suspend user account
     */
    public function suspend(User $user)
    {
        try {
            // Prevent suspending admin accounts
            if ($user->role === 'admin') {
                return redirect()->route('admin.users.index')->with([
                    'error' => 'Akses ditolak!',
                    'toast' => [
                        'type' => 'error',
                        'title' => 'Akses Ditolak!',
                        'message' => 'Tidak dapat suspend akun admin.',
                        'duration' => 6000
                    ]
                ]);
            }

            $user->update(['status' => 'suspended']);

            return redirect()->route('admin.users.index')->with([
                'success' => "Akun {$user->name} berhasil disuspend.",
                'toast' => [
                    'type' => 'warning',
                    'title' => 'Akun Disuspend!',
                    'message' => "Akun {$user->name} telah disuspend dan tidak dapat mengakses sistem.",
                    'duration' => 6000
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error suspending user: ' . $e->getMessage());

            return redirect()->back()->with([
                'error' => 'Gagal suspend akun!',
                'toast' => [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat suspend akun. Silakan coba lagi.',
                    'duration' => 7000
                ]
            ]);
        }
    }
}
