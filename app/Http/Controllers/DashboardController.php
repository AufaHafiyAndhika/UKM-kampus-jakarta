<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Ukm;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's UKM memberships
        $ukmMemberships = $user->ukms()
            ->withPivot(['role', 'status', 'joined_date'])
            ->wherePivot('status', 'active')
            ->get();

        // Get user's event registrations
        $eventRegistrations = $user->eventRegistrations()
            ->with(['event.ukm'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming events user is registered for
        $upcomingEvents = $user->eventRegistrations()
            ->with(['event.ukm'])
            ->whereHas('event', function ($query) {
                $query->where('start_datetime', '>', now())
                      ->where('status', 'published');
            })
            ->where('event_registrations.status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get user's certificates
        $certificates = $user->certificates()
            ->with(['event.ukm'])
            ->orderBy('issued_date', 'desc')
            ->limit(5)
            ->get();

        // Get user's attendance records
        $attendances = $user->attendances()
            ->with(['event.ukm'])
            ->orderBy('check_in_time', 'desc')
            ->limit(5)
            ->get();

        // Statistics
        $stats = [
            'total_ukms' => $ukmMemberships->count(),
            'total_events_registered' => $user->eventRegistrations()->count(),
            'total_events_attended' => $user->attendances()->count(),
            'total_certificates' => $certificates->count(),
        ];

        // Recent activities (combine different types)
        $activities = collect();

        // Add UKM memberships
        foreach ($ukmMemberships as $membership) {
            $activities->push([
                'type' => 'ukm_joined',
                'title' => 'Bergabung dengan ' . $membership->name,
                'description' => 'Sebagai ' . ucfirst($membership->pivot->role),
                'date' => $membership->pivot->joined_date,
                'icon' => 'users',
                'color' => 'blue',
            ]);
        }

        // Add event registrations
        foreach ($eventRegistrations as $registration) {
            $activities->push([
                'type' => 'event_registered',
                'title' => 'Mendaftar ' . $registration->event->title,
                'description' => 'Oleh ' . $registration->event->ukm->name,
                'date' => $registration->created_at,
                'icon' => 'calendar',
                'color' => 'green',
            ]);
        }

        // Add certificates
        foreach ($certificates as $certificate) {
            $activities->push([
                'type' => 'certificate_earned',
                'title' => 'Mendapat sertifikat ' . $certificate->title,
                'description' => 'Dari ' . $certificate->event->title,
                'date' => $certificate->issued_date,
                'icon' => 'award',
                'color' => 'yellow',
            ]);
        }

        // Sort activities by date (newest first) and limit to 10
        $recentActivities = $activities->sortByDesc('date')->take(10);

        return view('dashboard', compact(
            'user',
            'ukmMemberships',
            'eventRegistrations',
            'upcomingEvents',
            'certificates',
            'attendances',
            'stats',
            'recentActivities'
        ));
    }

    /**
     * Get dashboard statistics (for AJAX requests).
     */
    public function stats()
    {
        $user = Auth::user();

        $stats = [
            'ukms' => $user->ukms()->wherePivot('status', 'active')->count(),
            'events_registered' => $user->eventRegistrations()->where('status', 'approved')->count(),
            'events_attended' => $user->attendances()->where('status', 'present')->count(),
            'certificates' => $user->certificates()->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get user's upcoming events (for AJAX requests).
     */
    public function upcomingEvents()
    {
        $user = Auth::user();

        $events = $user->eventRegistrations()
            ->with(['event.ukm'])
            ->whereHas('event', function ($query) {
                $query->where('start_datetime', '>', now())
                      ->where('status', 'published');
            })
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->event->id,
                    'title' => $registration->event->title,
                    'ukm' => $registration->event->ukm->name,
                    'start_datetime' => $registration->event->start_datetime,
                    'location' => $registration->event->location,
                    'url' => route('events.show', $registration->event->slug),
                ];
            });

        return response()->json($events);
    }

    /**
     * Get user's recent certificates (for AJAX requests).
     */
    public function certificates()
    {
        $user = Auth::user();

        $certificates = $user->certificates()
            ->with(['event.ukm'])
            ->orderBy('issued_date', 'desc')
            ->get()
            ->map(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'title' => $certificate->title,
                    'event' => $certificate->event->title,
                    'ukm' => $certificate->event->ukm->name,
                    'issued_date' => $certificate->issued_date,
                    'verification_code' => $certificate->verification_code,
                    'download_url' => $certificate->getDownloadUrl(),
                ];
            });

        return response()->json($certificates);
    }
}
