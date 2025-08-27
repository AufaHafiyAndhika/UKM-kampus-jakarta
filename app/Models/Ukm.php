<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
// Import related models
use App\Models\User;
use App\Models\Event;
use App\Models\UkmMember;
use App\Models\UkmAchievement;

class Ukm extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'vision',
        'mission',
        'category',
        'logo',
        'banner',
        'background_image',
        'contact_info',
        'meeting_schedule',
        'meeting_location',
        'max_members',
        'current_members',
        'status',
        'registration_status',
        'requirements',
        'organization_structure',
        'is_recruiting',
        'established_date',
        'leader_id',
    ];

    protected function casts(): array
    {
        return [
            'contact_info' => 'array',
            'established_date' => 'date',
            'is_recruiting' => 'boolean',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
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
     * Get the leader of the UKM.
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Get the members of the UKM.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'ukm_members')
                    ->withPivot([
                        'role', 'status', 'joined_date', 'left_date', 'notes',
                        'previous_experience', 'skills_interests', 'reason_joining',
                        'preferred_division', 'cv_file', 'applied_at', 'approved_at',
                        'rejected_at', 'rejection_reason', 'approved_by', 'rejected_by'
                    ])
                    ->withTimestamps()
                    ->using(UkmMember::class);
    }

    /**
     * Get active members only.
     */
    public function activeMembers()
    {
        return $this->members()->wherePivot('status', 'active');
    }

    /**
     * Get the events organized by the UKM.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get published events only.
     */
    public function publishedEvents()
    {
        return $this->events()->where('events.status', 'published');
    }

    /**
     * Get the achievements of the UKM.
     */
    public function achievements()
    {
        return $this->hasMany(UkmAchievement::class);
    }

    /**
     * Get featured achievements only.
     */
    public function featuredAchievements()
    {
        return $this->achievements()->featured()->recent();
    }

    /**
     * Get recent achievements.
     */
    public function recentAchievements($limit = 5)
    {
        return $this->achievements()->recent($limit);
    }

    /**
     * Update member count.
     */
    public function updateMemberCount()
    {
        $this->current_members = $this->activeMembers()->count();
        $this->save();
    }

    /**
     * Check if UKM can accept new members.
     */
    public function canAcceptMembers(): bool
    {
        return $this->is_recruiting &&
               $this->status === 'active' &&
               $this->current_members < $this->max_members;
    }

    /**
     * Scope for active UKMs.
     */
    public function scopeActive($query)
    {
        return $query->where('ukms.status', 'active');
    }

    /**
     * Scope for recruiting UKMs.
     */
    public function scopeRecruiting($query)
    {
        return $query->where('is_recruiting', true);
    }

    /**
     * Scope by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
