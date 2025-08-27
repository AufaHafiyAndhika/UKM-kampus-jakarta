<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UkmAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ukm_id',
        'title',
        'description',
        'type',
        'level',
        'organizer',
        'achievement_date',
        'year',
        'certificate_file',
        'participants',
        'position',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'achievement_date' => 'date',
            'year' => 'integer',
            'position' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Get the UKM that owns the achievement.
     */
    public function ukm()
    {
        return $this->belongsTo(Ukm::class);
    }

    /**
     * Get the position text (Juara 1, Juara 2, etc.)
     */
    public function getPositionTextAttribute()
    {
        if (!$this->position) {
            return null;
        }

        $positions = [
            1 => 'Juara 1',
            2 => 'Juara 2',
            3 => 'Juara 3',
        ];

        return $positions[$this->position] ?? "Peringkat {$this->position}";
    }

    /**
     * Get the type text in Indonesian
     */
    public function getTypeTextAttribute()
    {
        $types = [
            'competition' => 'Kompetisi',
            'award' => 'Penghargaan',
            'certification' => 'Sertifikasi',
            'recognition' => 'Pengakuan',
            'other' => 'Lainnya',
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get the level text in Indonesian
     */
    public function getLevelTextAttribute()
    {
        $levels = [
            'local' => 'Lokal',
            'regional' => 'Regional',
            'national' => 'Nasional',
            'international' => 'Internasional',
        ];

        return $levels[$this->level] ?? ucfirst($this->level);
    }

    /**
     * Scope for featured achievements
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for achievements by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for recent achievements
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('achievement_date', 'desc')->limit($limit);
    }
}
