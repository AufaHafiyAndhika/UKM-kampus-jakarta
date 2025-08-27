<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
// Import related models
use App\Models\Ukm;
use App\Models\Notification;
use App\Models\EventRegistration;
use App\Models\Attendance;
use App\Models\Certificate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nim',
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'faculty',
        'major',
        'batch',
        'bio',
        'avatar',
        'status',
        'role',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student' || $this->hasRole('student');
    }

    /**
     * Check if user is ketua UKM.
     */
    public function isKetuaUkm(): bool
    {
        return $this->role === 'ketua_ukm' || $this->hasRole('ketua_ukm');
    }

    /**
     * Sync role with Spatie Permission
     */
    public function syncRoleWithSpatie()
    {
        try {
            // Clear existing roles first
            $this->syncRoles([]);

            // Assign new role if exists
            if ($this->role) {
                // Check if role exists in Spatie Permission
                $spatieRole = \Spatie\Permission\Models\Role::where('name', $this->role)->first();

                if (!$spatieRole) {
                    // Create role if it doesn't exist
                    $spatieRole = \Spatie\Permission\Models\Role::create(['name' => $this->role]);
                }

                // Assign the role
                $this->assignRole($this->role);
            }
        } catch (\Exception $e) {
            // Log error but don't throw to prevent breaking the update
            Log::warning("Failed to sync Spatie role for user {$this->id}: " . $e->getMessage());
        }
    }

    /**
     * Get UKMs that this user leads (for ketua_ukm role)
     */
    public function getLeadingUkms()
    {
        if (!$this->isKetuaUkm()) {
            return collect();
        }

        return $this->ledUkms()->get();
    }

    /**
     * Get the UKMs that the user is a member of.
     */
    public function ukms()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_members')
                    ->withPivot([
                        'role', 'status', 'joined_date', 'left_date', 'notes',
                        'previous_experience', 'skills_interests', 'reason_joining',
                        'preferred_division', 'cv_file', 'applied_at', 'approved_at',
                        'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get active UKM memberships
     */
    public function activeUkms()
    {
        return $this->belongsToMany(Ukm::class, 'ukm_members')
                    ->wherePivot('status', 'active')
                    ->withPivot([
                        'role', 'status', 'joined_date', 'left_date', 'notes',
                        'previous_experience', 'skills_interests', 'reason_joining',
                        'preferred_division', 'cv_file', 'applied_at', 'approved_at',
                        'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
                    ])
                    ->withTimestamps();
    }

    /**
     * Get the UKMs that the user leads.
     */
    public function ledUkms()
    {
        return $this->hasMany(Ukm::class, 'leader_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get the events registered by the user.
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the events attended by the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the certificates earned by the user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Check if user is a member of a specific UKM.
     */
    public function isMemberOf($ukmId)
    {
        return $this->ukms()->where('ukm_members.ukm_id', $ukmId)->wherePivot('status', 'active')->exists();
    }



    /**
     * Check if user is a leader of a specific UKM.
     */
    public function isLeaderOf($ukmId)
    {
        return $this->ukms()
                    ->where('ukm_id', $ukmId)
                    ->wherePivot('role', 'leader')
                    ->exists();
    }
}
