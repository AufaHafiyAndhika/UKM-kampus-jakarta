<?php

namespace App\Http\Controllers;

use App\Models\UkmAchievement;
use App\Models\Ukm;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    /**
     * Display a listing of all achievements.
     */
    public function index(Request $request)
    {
        $query = UkmAchievement::with(['ukm'])
            ->orderBy('achievement_date', 'desc');

        // Filter by UKM
        if ($request->filled('ukm')) {
            $query->whereHas('ukm', function ($q) use ($request) {
                $q->where('slug', $request->ukm);
            });
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(12);

        // Get filter options
        $ukms = Ukm::active()->orderBy('name')->get();
        $years = UkmAchievement::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $types = [
            'competition' => 'Kompetisi',
            'award' => 'Penghargaan',
            'certification' => 'Sertifikasi',
            'recognition' => 'Pengakuan',
            'other' => 'Lainnya',
        ];

        $levels = [
            'local' => 'Lokal',
            'regional' => 'Regional',
            'national' => 'Nasional',
            'international' => 'Internasional',
        ];

        return view('achievements.index', compact(
            'achievements', 
            'ukms', 
            'years', 
            'types', 
            'levels'
        ));
    }

    /**
     * Display achievements for a specific UKM.
     */
    public function byUkm(Ukm $ukm, Request $request)
    {
        $query = $ukm->achievements()
            ->orderBy('achievement_date', 'desc');

        // Filter by year
        if ($request->filled('year')) {
            $query->byYear($request->year);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(12);

        // Get filter options for this UKM
        $years = $ukm->achievements()
            ->selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        $types = [
            'competition' => 'Kompetisi',
            'award' => 'Penghargaan',
            'certification' => 'Sertifikasi',
            'recognition' => 'Pengakuan',
            'other' => 'Lainnya',
        ];

        $levels = [
            'local' => 'Lokal',
            'regional' => 'Regional',
            'national' => 'Nasional',
            'international' => 'Internasional',
        ];

        return view('achievements.by-ukm', compact(
            'ukm',
            'achievements', 
            'years', 
            'types', 
            'levels'
        ));
    }

    /**
     * Display the specified achievement.
     */
    public function show(UkmAchievement $achievement)
    {
        $achievement->load(['ukm']);
        
        // Get related achievements from the same UKM
        $relatedAchievements = $achievement->ukm
            ->achievements()
            ->where('id', '!=', $achievement->id)
            ->recent(4)
            ->get();

        return view('achievements.show', compact('achievement', 'relatedAchievements'));
    }
}
