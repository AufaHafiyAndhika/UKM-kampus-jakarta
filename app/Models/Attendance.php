<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'check_in_time',
        'check_out_time',
        'check_in_method',
        'qr_token',
        'ip_address',
        'user_agent',
        'latitude',
        'longitude',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the user who attended.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event attended.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who recorded the attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Check if user is present.
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if user is late.
     */
    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    /**
     * Check if user checked out.
     */
    public function hasCheckedOut(): bool
    {
        return $this->check_out_time !== null;
    }

    /**
     * Calculate attendance duration.
     */
    public function getDuration()
    {
        if (!$this->check_out_time) {
            return null;
        }

        return $this->check_in_time->diffInMinutes($this->check_out_time);
    }

    /**
     * Generate QR token for attendance.
     */
    public static function generateQrToken($eventId, $userId = null)
    {
        $data = [
            'event_id' => $eventId,
            'user_id' => $userId,
            'timestamp' => now()->timestamp,
            'random' => str()->random(10),
        ];

        return encrypt(json_encode($data));
    }

    /**
     * Validate QR token.
     */
    public static function validateQrToken($token, $eventId)
    {
        try {
            $data = json_decode(decrypt($token), true);
            
            // Check if token is for the correct event
            if ($data['event_id'] != $eventId) {
                return false;
            }

            // Check if token is not expired (2 hours)
            $tokenTime = $data['timestamp'];
            $currentTime = now()->timestamp;
            
            return ($currentTime - $tokenTime) <= 7200; // 2 hours
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Scope for present attendances.
     */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /**
     * Scope for late attendances.
     */
    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    /**
     * Scope for absent attendances.
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }
}
