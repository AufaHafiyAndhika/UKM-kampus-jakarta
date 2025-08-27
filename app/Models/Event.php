<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'ukm_id',
        'title',
        'slug',
        'description',
        'requirements',
        'poster',
        'gallery',
        'type',
        'location',
        'start_datetime',
        'end_datetime',
        'registration_start',
        'registration_end',
        'max_participants',
        'current_participants',
        'registration_fee',
        'status',
        'requires_approval',
        'registration_open',
        'certificate_available',
        'certificate_template',
        'contact_person',
        'notes',
        'proposal_file',
        'rab_file',
        'lpj_file',
        // Approval tracking fields
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'contact_person' => 'array',
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'approved_at' => 'datetime',
            'registration_fee' => 'decimal:2',
            'requires_approval' => 'boolean',
            'registration_open' => 'boolean',
            'certificate_available' => 'boolean',
        ];
    }

    protected $attributes = [
        'requires_approval' => false, // Changed to auto-approve by default
        'registration_open' => true,
        'certificate_available' => false,
        'current_participants' => 0,
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-update status when event dates are changed
        static::saving(function ($event) {
            // Only auto-update if the event is published and dates have changed
            if ($event->status === 'published' && $event->isDirty(['start_datetime', 'end_datetime'])) {
                $event->updateStatusBasedOnDates();
            }
        });

        // Also update status after saving
        static::saved(function ($event) {
            // Update status if it's a published event
            if ($event->status === 'published') {
                $event->updateStatusBasedOnDates();
            }
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the UKM that organizes this event.
     */
    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    /**
     * Get the registrations for this event.
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }



    /**
     * Create attendance records for all approved registrations
     */
    public function createAttendanceRecords()
    {
        $approvedRegistrations = $this->registrations()->where('status', 'approved')->get();

        foreach ($approvedRegistrations as $registration) {
            EventAttendance::firstOrCreate([
                'event_id' => $this->id,
                'user_id' => $registration->user_id,
                'event_registration_id' => $registration->id,
            ]);
        }
    }

    /**
     * Get approved registrations only.
     */
    public function approvedRegistrations()
    {
        return $this->registrations()->where('status', 'approved');
    }



    /**
     * Get the certificates issued for this event.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the admin who approved this event.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the current status of the event based on dates
     */
    public function getCurrentStatus(): string
    {
        $now = now();

        // If manually cancelled, always return cancelled
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        // If waiting, return waiting (waiting for admin approval)
        if ($this->status === 'waiting') {
            return 'waiting';
        }

        // If not published yet, return current status
        if ($this->status !== 'published') {
            return $this->status;
        }

        // Auto-determine status based on dates for published events
        if ($now < $this->start_datetime) {
            return 'published'; // Upcoming event
        } elseif ($now >= $this->start_datetime && $now <= $this->end_datetime) {
            return 'ongoing'; // Event is happening now
        } else {
            return 'completed'; // Event has ended
        }
    }

    /**
     * Update event status automatically based on current date
     */
    public function updateStatusAutomatically(): void
    {
        $currentStatus = $this->getCurrentStatus();

        // Only update if status has changed and it's an auto-status
        if ($this->status !== $currentStatus && in_array($currentStatus, ['ongoing', 'completed'])) {
            $this->update(['status' => $currentStatus]);

            // Create attendance records when event becomes completed
            if ($currentStatus === 'completed') {
                $this->createAttendanceRecords();
            }
        }
    }

    /**
     * Update status and registration based on dates (called when dates are changed)
     */
    public function updateStatusBasedOnDates(): void
    {
        $now = now();
        $newStatus = $this->status;
        $registrationOpen = $this->registration_open;

        // Skip if event is cancelled or draft
        if (in_array($this->status, ['cancelled', 'draft'])) {
            return;
        }

        // Determine new status based on dates
        if ($now < $this->start_datetime) {
            // Event is in the future - keep as published
            $newStatus = 'published';
            // Registration is open if we're within registration period
            $registrationOpen = $this->isRegistrationOpen();
        } elseif ($now >= $this->start_datetime && $now <= $this->end_datetime) {
            // Event is happening now - set to ongoing
            $newStatus = 'ongoing';
            // Close registration when event starts
            $registrationOpen = false;
        } else {
            // Event has ended - set to completed
            $newStatus = 'completed';
            // Close registration
            $registrationOpen = false;
        }

        // Update if anything changed
        $updates = [];
        if ($this->status !== $newStatus) {
            $updates['status'] = $newStatus;
        }
        if ($this->registration_open !== $registrationOpen) {
            $updates['registration_open'] = $registrationOpen;
        }

        if (!empty($updates)) {
            // Use updateQuietly to avoid triggering the boot events again
            $this->updateQuietly($updates);

            // Create attendance records when event becomes completed
            if ($newStatus === 'completed' && $this->status !== 'completed') {
                $this->createAttendanceRecords();
            }
        }
    }

    /**
     * Check if event can submit attendance (event has ended)
     */
    public function canSubmitAttendance(): bool
    {
        return $this->getCurrentStatus() === 'completed';
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen(): bool
    {
        $now = now();
        $currentStatus = $this->getCurrentStatus();

        return $this->registration_open && // Check the registration_open flag
               in_array($currentStatus, ['published', 'waiting']) && // Allow registration for published or waiting events
               $now < $this->start_datetime && // Event hasn't started yet
               ($this->registration_start === null || $now >= $this->registration_start) &&
               ($this->registration_end === null || $now <= $this->registration_end) &&
               ($this->max_participants === null || $this->current_participants < $this->max_participants);
    }

    /**
     * Check if event is ongoing.
     */
    public function isOngoing(): bool
    {
        $now = now();
        return $now >= $this->start_datetime && $now <= $this->end_datetime;
    }

    /**
     * Check if event is completed.
     */
    public function isCompleted(): bool
    {
        return now() > $this->end_datetime;
    }

    /**
     * Update participant count.
     */
    public function updateParticipantCount()
    {
        $this->current_participants = $this->approvedRegistrations()->count();
        $this->save();
    }

    /**
     * Scope for published events.
     */
    public function scopePublished($query)
    {
        return $query->where('events.status', 'published');
    }

    /**
     * Scope for upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>', now());
    }

    /**
     * Scope for ongoing events.
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_datetime', '<=', now())
                    ->where('end_datetime', '>=', now());
    }

    /**
     * Scope by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
