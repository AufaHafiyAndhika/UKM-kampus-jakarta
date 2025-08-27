<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'motivation',
        'additional_data',
        'payment_proof',
        'payment_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'availability_form',
        'registration_notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'additional_data' => 'array',
            'availability_form' => 'array',
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Get the user who registered.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event being registered for.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who approved the registration.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if registration is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if registration is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is required.
     */
    public function requiresPayment(): bool
    {
        return $this->event->registration_fee > 0;
    }

    /**
     * Check if payment is verified.
     */
    public function isPaymentVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    /**
     * Approve the registration.
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);

        // Update event participant count
        $this->event->updateParticipantCount();
    }

    /**
     * Reject the registration.
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Cancel the registration.
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        // Update event participant count
        $this->event->updateParticipantCount();
    }

    /**
     * Check if registration can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        // Can only cancel if approved and event hasn't started yet
        // Must be at least 1 day before event starts
        if ($this->status !== 'approved') {
            return false;
        }

        $oneDayBeforeEvent = $this->event->start_datetime->subDay();
        return now() <= $oneDayBeforeEvent;
    }

    /**
     * Scope for approved registrations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected registrations.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
