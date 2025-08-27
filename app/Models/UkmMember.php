<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UkmMember extends Pivot
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ukm_members';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'applied_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'joined_date' => 'date',
        'left_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ukm_id',
        'user_id',
        'role',
        'status',
        'joined_date',
        'left_date',
        'notes',
        'previous_experience',
        'skills_interests',
        'reason_joining',
        'preferred_division',
        'cv_file',
        'applied_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'approved_by',
        'rejected_by',
    ];
}
