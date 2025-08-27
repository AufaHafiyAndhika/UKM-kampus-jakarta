<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ukm;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Constructor removed - middleware will be applied in routes

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_ukms' => Ukm::count(),
            'total_events' => Event::count(),
            'total_members' => DB::table('ukm_members')->where('status', 'active')->count(),
        ];

        $recentStudents = User::where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentEvents = Event::with('ukm')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentEvents'));
    }

    /**
     * Get admin statistics (for AJAX requests)
     */
    public function stats()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_ukms' => Ukm::count(),
            'total_events' => Event::count(),
            'total_members' => DB::table('ukm_members')->where('status', 'active')->count(),
            'new_students_this_month' => User::where('role', 'student')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'active_events' => Event::where('status', 'published')
                ->where('start_datetime', '>', now())
                ->count(),
        ];

        return response()->json($stats);
    }
}
