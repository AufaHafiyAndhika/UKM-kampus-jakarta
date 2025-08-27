<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'event_registration_id',
        'status',
        'proof_file',
        'notes',
        'submitted_at',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'certificate_generated',
        'certificate_file',
        'certificate_downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'certificate_downloaded_at' => 'datetime',
            'certificate_generated' => 'boolean',
        ];
    }

    /**
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if attendance can be submitted
     */
    public function canSubmitAttendance(): bool
    {
        // Can only submit after event has ended
        return $this->event->end_datetime < now() && $this->status === 'pending';
    }

    /**
     * Check if certificate can be downloaded
     */
    public function canDownloadCertificate(): bool
    {
        return $this->verification_status === 'verified' && 
               $this->status === 'present' && 
               $this->event->certificate_template;
    }

    /**
     * Submit attendance
     */
    public function submitAttendance($proofFile = null, $notes = null)
    {
        $this->update([
            'status' => 'present',
            'proof_file' => $proofFile,
            'notes' => $notes,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Verify attendance
     */
    public function verify($verifiedBy, $notes = null)
    {
        $this->update([
            'verification_status' => 'verified',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Reject attendance
     */
    public function reject($verifiedBy, $notes = null)
    {
        $this->update([
            'verification_status' => 'rejected',
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Mark certificate as downloaded
     */
    public function markCertificateDownloaded()
    {
        $this->update([
            'certificate_downloaded_at' => now(),
        ]);
    }

    /**
     * Get proof file URL
     */
    public function getProofFileUrlAttribute()
    {
        return $this->proof_file ? Storage::disk('public')->url($this->proof_file) : null;
    }

    /**
     * Get certificate file URL
     */
    public function getCertificateFileUrlAttribute()
    {
        return $this->certificate_file ? Storage::disk('public')->url($this->certificate_file) : null;
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }
}
