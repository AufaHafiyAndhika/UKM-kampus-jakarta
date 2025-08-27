<?php

namespace App\Http\Controllers;

use App\Models\Ukm;
use App\Models\Event;
use App\Models\UkmAchievement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate main sitemap index
     */
    public function index()
    {
        $sitemaps = [
            [
                'loc' => route('sitemap.pages'),
                'lastmod' => now()->toISOString(),
            ],
            [
                'loc' => route('sitemap.ukms'),
                'lastmod' => Ukm::latest('updated_at')->first()?->updated_at?->toISOString() ?? now()->toISOString(),
            ],
            [
                'loc' => route('sitemap.events'),
                'lastmod' => Event::latest('updated_at')->first()?->updated_at?->toISOString() ?? now()->toISOString(),
            ],
        ];

        $content = view('sitemaps.index', compact('sitemaps'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate static pages sitemap
     */
    public function pages()
    {
        $pages = [
            [
                'loc' => route('home'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('ukms.index'),
                'lastmod' => Ukm::latest('updated_at')->first()?->updated_at?->toISOString() ?? now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('events.index'),
                'lastmod' => Event::latest('updated_at')->first()?->updated_at?->toISOString() ?? now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('achievements.index'),
                'lastmod' => UkmAchievement::latest('updated_at')->first()?->updated_at?->toISOString() ?? now()->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => route('about'),
                'lastmod' => now()->subDays(30)->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('contact'),
                'lastmod' => now()->subDays(30)->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
        ];

        $content = view('sitemaps.urlset', compact('pages'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate UKMs sitemap
     */
    public function ukms()
    {
        $ukms = Ukm::active()
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(function ($ukm) {
                return [
                    'loc' => route('ukms.show', $ukm->slug),
                    'lastmod' => $ukm->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            });

        $content = view('sitemaps.urlset', ['pages' => $ukms])->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate events sitemap
     */
    public function events()
    {
        $events = Event::published()
            ->select(['slug', 'updated_at'])
            ->get()
            ->map(function ($event) {
                return [
                    'loc' => route('events.show', $event->slug),
                    'lastmod' => $event->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        $content = view('sitemaps.urlset', ['pages' => $events])->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
