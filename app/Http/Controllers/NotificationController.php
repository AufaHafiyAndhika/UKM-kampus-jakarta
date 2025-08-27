<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);
        $unreadCount = $user->unreadNotificationsCount();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = NotificationService::markAsRead($id);
        
        if (!$notification) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        NotificationService::markAllAsRead($user);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread notifications count (for AJAX).
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotificationsCount();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown).
     */
    public function getRecent()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->limit(5)->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotificationsCount()
        ]);
    }
}
