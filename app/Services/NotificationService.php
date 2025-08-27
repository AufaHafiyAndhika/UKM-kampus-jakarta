<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for UKM application approval.
     */
    public static function createUkmApplicationApproved(User $user, $ukmName)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'ukm_application_approved',
            'title' => 'Pendaftaran UKM Diterima',
            'message' => "Selamat! Pendaftaran Anda ke UKM {$ukmName} telah diterima. Selamat bergabung!",
            'data' => [
                'ukm_name' => $ukmName,
                'action' => 'approved'
            ]
        ]);
    }

    /**
     * Create a notification for UKM application rejection.
     */
    public static function createUkmApplicationRejected(User $user, $ukmName)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'ukm_application_rejected',
            'title' => 'Pendaftaran UKM Ditolak',
            'message' => "Mohon maaf, pendaftaran Anda ke UKM {$ukmName} tidak dapat diterima saat ini. Jangan berkecil hati, Anda dapat mencoba mendaftar ke UKM lain atau mencoba lagi di periode berikutnya.",
            'data' => [
                'ukm_name' => $ukmName,
                'action' => 'rejected'
            ]
        ]);
    }

    /**
     * Create a notification for UKM member removal.
     */
    public static function createUkmMemberRemoved(User $user, $ukmName, $reason = null)
    {
        $message = "Anda telah dikeluarkan dari UKM {$ukmName}.";
        if ($reason) {
            $message .= " Pesan dari ketua UKM: {$reason}";
        }
        $message .= " Anda dapat mendaftar ulang jika ingin bergabung kembali.";

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'ukm_member_removed',
            'title' => 'Dikeluarkan dari UKM',
            'message' => $message,
            'data' => [
                'ukm_name' => $ukmName,
                'action' => 'removed',
                'reason' => $reason
            ]
        ]);
    }

    /**
     * Create a notification for event registration.
     */
    public static function createEventRegistration(User $user, $eventTitle)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event_registration',
            'title' => 'Pendaftaran Event Berhasil',
            'message' => "Anda telah berhasil mendaftar untuk event '{$eventTitle}'. Silakan cek email untuk informasi lebih lanjut.",
            'data' => [
                'event_title' => $eventTitle,
                'action' => 'registered'
            ]
        ]);
    }

    /**
     * Create a notification for event approval.
     */
    public static function createEventApproved(User $user, $eventTitle)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event_approved',
            'title' => 'Event Disetujui',
            'message' => "Event '{$eventTitle}' yang Anda ajukan telah disetujui oleh admin.",
            'data' => [
                'event_title' => $eventTitle,
                'action' => 'approved'
            ]
        ]);
    }

    /**
     * Create a notification for event rejection.
     */
    public static function createEventRejected(User $user, $eventTitle, $reason = null)
    {
        $message = "Event '{$eventTitle}' yang Anda ajukan tidak dapat disetujui.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event_rejected',
            'title' => 'Event Ditolak',
            'message' => $message,
            'data' => [
                'event_title' => $eventTitle,
                'action' => 'rejected',
                'reason' => $reason
            ]
        ]);
    }

    /**
     * Create a notification for event registration approval.
     */
    public static function sendEventRegistrationApproved(User $user, $event)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event_registration_approved',
            'title' => 'Pendaftaran Event Diterima',
            'message' => "Selamat! Pendaftaran Anda untuk event '{$event->title}' telah diterima. Silakan cek detail event untuk informasi lebih lanjut.",
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_slug' => $event->slug,
                'action' => 'approved'
            ]
        ]);
    }

    /**
     * Create a notification for event registration rejection.
     */
    public static function sendEventRegistrationRejected(User $user, $event, $reason = null)
    {
        $message = "Pendaftaran Anda untuk event '{$event->title}' tidak dapat diterima.";
        if ($reason) {
            $message .= " Alasan: {$reason}";
        }
        $message .= " Anda dapat mencoba mendaftar untuk event lain yang tersedia.";

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'event_registration_rejected',
            'title' => 'Pendaftaran Event Ditolak',
            'message' => $message,
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_slug' => $event->slug,
                'action' => 'rejected',
                'reason' => $reason
            ]
        ]);
    }

    /**
     * Get unread notifications for a user.
     */
    public static function getUnreadNotifications(User $user)
    {
        return $user->notifications()->unread()->get();
    }

    /**
     * Mark notification as read.
     */
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        return $notification;
    }

    /**
     * Mark all notifications as read for a user.
     */
    public static function markAllAsRead(User $user)
    {
        return $user->notifications()->unread()->update(['read_at' => now()]);
    }
}
