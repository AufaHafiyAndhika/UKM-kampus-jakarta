<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ukm;
use App\Models\Event;
use App\Models\UkmAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SeoController extends Controller
{
    /**
     * Show SEO dashboard
     */
    public function dashboard()
    {
        // SEO Statistics
        $stats = [
            'total_pages' => $this->getTotalIndexablePages(),
            'ukms_with_descriptions' => Ukm::whereNotNull('description')->where('description', '!=', '')->count(),
            'ukms_without_descriptions' => Ukm::whereNull('description')->orWhere('description', '')->count(),
            'events_with_descriptions' => Event::whereNotNull('description')->where('description', '!=', '')->count(),
            'events_without_descriptions' => Event::whereNull('description')->orWhere('description', '')->count(),
            'total_ukms' => Ukm::count(),
            'total_events' => Event::count(),
        ];

        // SEO Issues
        $issues = $this->getSeoIssues();

        // Recent content updates
        $recentUpdates = [
            'ukms' => Ukm::latest('updated_at')->limit(5)->get(['name', 'updated_at', 'slug']),
            'events' => Event::latest('updated_at')->limit(5)->get(['title', 'updated_at', 'slug']),
        ];

        return view('admin.seo.dashboard', compact('stats', 'issues', 'recentUpdates'));
    }

    /**
     * Generate sitemap
     */
    public function generateSitemap()
    {
        try {
            // Test sitemap generation
            $response = Http::get(route('sitemap.index'));
            
            if ($response->successful()) {
                return redirect()->route('admin.seo.dashboard')
                    ->with('success', 'Sitemap generated successfully! URL: ' . route('sitemap.index'));
            } else {
                return redirect()->route('admin.seo.dashboard')
                    ->with('error', 'Failed to generate sitemap. Please check your configuration.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.seo.dashboard')
                ->with('error', 'Error generating sitemap: ' . $e->getMessage());
        }
    }

    /**
     * Run SEO audit
     */
    public function runAudit()
    {
        $baseUrl = config('app.url', 'http://localhost:8000');
        $auditResults = [];

        // Check main pages
        $pages = [
            '/' => 'Homepage',
            '/ukm' => 'UKM Index',
            '/events' => 'Events Index',
            '/about' => 'About Page',
            '/contact' => 'Contact Page',
        ];

        foreach ($pages as $path => $name) {
            try {
                $response = Http::timeout(10)->get($baseUrl . $path);
                
                $result = [
                    'name' => $name,
                    'url' => $baseUrl . $path,
                    'status' => $response->status(),
                    'accessible' => $response->successful(),
                    'issues' => []
                ];

                if ($response->successful()) {
                    $content = $response->body();
                    
                    // Check title
                    if (preg_match('/<title>(.*?)<\/title>/i', $content, $matches)) {
                        $title = trim($matches[1]);
                        $titleLength = strlen($title);
                        
                        if ($titleLength > 60) {
                            $result['issues'][] = "Title too long ({$titleLength} chars)";
                        } elseif ($titleLength < 30) {
                            $result['issues'][] = "Title too short ({$titleLength} chars)";
                        }
                    } else {
                        $result['issues'][] = 'Missing title tag';
                    }
                    
                    // Check meta description
                    if (preg_match('/<meta name="description" content="(.*?)"/i', $content, $matches)) {
                        $description = trim($matches[1]);
                        $descLength = strlen($description);
                        
                        if ($descLength > 160) {
                            $result['issues'][] = "Meta description too long ({$descLength} chars)";
                        } elseif ($descLength < 120) {
                            $result['issues'][] = "Meta description too short ({$descLength} chars)";
                        }
                    } else {
                        $result['issues'][] = 'Missing meta description';
                    }
                    
                    // Check H1 tag
                    if (!preg_match('/<h1[^>]*>/i', $content)) {
                        $result['issues'][] = 'Missing H1 tag';
                    }
                }

                $auditResults[] = $result;

            } catch (\Exception $e) {
                $auditResults[] = [
                    'name' => $name,
                    'url' => $baseUrl . $path,
                    'status' => 0,
                    'accessible' => false,
                    'issues' => ['Error: ' . $e->getMessage()]
                ];
            }
        }

        return view('admin.seo.audit-results', compact('auditResults'));
    }

    /**
     * Show content optimization suggestions
     */
    public function contentOptimization()
    {
        // UKMs without proper SEO
        $ukmsNeedingSeo = Ukm::where(function($query) {
            $query->whereNull('description')
                  ->orWhere('description', '')
                  ->orWhereRaw('LENGTH(description) < 100');
        })->get();

        // Events without proper SEO
        $eventsNeedingSeo = Event::where(function($query) {
            $query->whereNull('description')
                  ->orWhere('description', '')
                  ->orWhereRaw('LENGTH(description) < 100');
        })->get();

        return view('admin.seo.content-optimization', compact('ukmsNeedingSeo', 'eventsNeedingSeo'));
    }

    private function getTotalIndexablePages()
    {
        return 5 + // Static pages (home, ukm index, events index, about, contact)
               Ukm::active()->count() + // UKM detail pages
               Event::published()->count(); // Event detail pages
    }

    private function getSeoIssues()
    {
        $issues = [];

        // Check for content without descriptions
        $ukmsWithoutDesc = Ukm::whereNull('description')->orWhere('description', '')->count();
        if ($ukmsWithoutDesc > 0) {
            $issues[] = [
                'type' => 'warning',
                'message' => "{$ukmsWithoutDesc} UKMs missing descriptions",
                'action' => 'Add descriptions to improve SEO'
            ];
        }

        $eventsWithoutDesc = Event::whereNull('description')->orWhere('description', '')->count();
        if ($eventsWithoutDesc > 0) {
            $issues[] = [
                'type' => 'warning',
                'message' => "{$eventsWithoutDesc} events missing descriptions",
                'action' => 'Add descriptions to improve SEO'
            ];
        }

        // Check for missing images
        $ukmsWithoutLogos = Ukm::whereNull('logo')->orWhere('logo', '')->count();
        if ($ukmsWithoutLogos > 0) {
            $issues[] = [
                'type' => 'info',
                'message' => "{$ukmsWithoutLogos} UKMs missing logos",
                'action' => 'Add logos for better social media sharing'
            ];
        }

        return $issues;
    }
}
