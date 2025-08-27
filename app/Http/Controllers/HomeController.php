<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\Event;
use App\Models\User;
use App\Models\Certificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_ukms' => Ukm::active()->count(),
            'total_members' => User::where('users.status', 'active')->count(),
            'total_events' => Event::count(), // Count all events ever created
            'total_achievements' => \App\Models\UkmAchievement::whereYear('created_at', date('Y'))->count(),
        ];

        // Get featured UKMs (active, recruiting, with most members)
        $featured_ukms = Ukm::active()
            ->recruiting()
            ->orderBy('current_members', 'desc')
            ->limit(6)
            ->get();

        // Get upcoming events (published, future events)
        $upcoming_events = Event::published()
            ->upcoming()
            ->with(['ukm'])
            ->orderBy('start_datetime', 'asc')
            ->limit(4)
            ->get();

        // Get featured achievements (prestasi yang ditampilkan di homepage)
        $featured_achievements = \App\Models\UkmAchievement::featured()
            ->with(['ukm'])
            ->orderBy('achievement_date', 'desc')
            ->limit(6)
            ->get();

        return view('home', compact('stats', 'featured_ukms', 'upcoming_events', 'featured_achievements'));
    }

    /**
     * Show about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Show contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can implement email sending logic
        // For now, we'll just return a success message

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }

    /**
     * Search functionality.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('home');
        }

        // Search UKMs
        $ukms = Ukm::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        // Search Events
        $events = Event::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('type', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->with(['ukm'])
            ->limit(10)
            ->get();

        return view('search-results', compact('query', 'ukms', 'events'));
    }
}
