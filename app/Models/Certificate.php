<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'certificate_number',
        'title',
        'description',
        'file_path',
        'issued_date',
        'issued_by',
        'verification_code',
        'is_verified',
        'downloaded_at',
        'download_count',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'datetime',
            'downloaded_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the user who owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event for which the certificate was issued.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who issued the certificate.
     */
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Generate unique certificate number.
     */
    public static function generateCertificateNumber($eventId)
    {
        $event = Event::find($eventId);
        $year = now()->year;
        $month = now()->format('m');
        
        // Format: CERT-YEAR-MONTH-EVENTID-SEQUENCE
        $prefix = "CERT-{$year}-{$month}-{$eventId}";
        
        // Get the last certificate number for this event
        $lastCert = self::where('certificate_number', 'like', "{$prefix}-%")
                       ->orderBy('certificate_number', 'desc')
                       ->first();
        
        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_number, strrpos($lastCert->certificate_number, '-') + 1);
            $sequence = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }
        
        return "{$prefix}-{$sequence}";
    }

    /**
     * Generate unique verification code.
     */
    public static function generateVerificationCode()
    {
        do {
            $code = strtoupper(str()->random(8));
        } while (self::where('verification_code', $code)->exists());
        
        return $code;
    }

    /**
     * Get the download URL.
     */
    public function getDownloadUrl()
    {
        return route('certificates.download', $this->verification_code);
    }

    /**
     * Get the verification URL.
     */
    public function getVerificationUrl()
    {
        return route('certificates.verify', $this->verification_code);
    }

    /**
     * Record download.
     */
    public function recordDownload()
    {
        $this->increment('download_count');
        $this->update(['downloaded_at' => now()]);
    }

    /**
     * Check if certificate file exists.
     */
    public function fileExists()
    {
        return file_exists(storage_path('app/' . $this->file_path));
    }

    /**
     * Scope for verified certificates.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope by verification code.
     */
    public function scopeByVerificationCode($query, $code)
    {
        return $query->where('verification_code', $code);
    }
}
