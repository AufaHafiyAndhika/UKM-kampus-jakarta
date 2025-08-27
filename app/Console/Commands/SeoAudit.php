<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Ukm;
use App\Models\Event;

class SeoAudit extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:audit {--url=http://localhost:8000 : Base URL to audit}';

    /**
     * The console command description.
     */
    protected $description = 'Perform SEO audit on the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = $this->option('url');
        $this->info("🔍 Starting SEO audit for: {$baseUrl}");
        $this->newLine();

        $issues = [];
        $recommendations = [];

        // 1. Check robots.txt
        $this->checkRobotsTxt($baseUrl, $issues, $recommendations);

        // 2. Check sitemap
        $this->checkSitemap($baseUrl, $issues, $recommendations);

        // 3. Check main pages
        $this->checkMainPages($baseUrl, $issues, $recommendations);

        // 4. Check UKM pages
        $this->checkUkmPages($baseUrl, $issues, $recommendations);

        // 5. Check database content
        $this->checkDatabaseContent($issues, $recommendations);

        // Display results
        $this->displayResults($issues, $recommendations);

        return empty($issues) ? 0 : 1;
    }

    private function checkRobotsTxt($baseUrl, &$issues, &$recommendations)
    {
        $this->info('📄 Checking robots.txt...');
        
        try {
            $response = Http::get("{$baseUrl}/robots.txt");
            
            if ($response->successful()) {
                $content = $response->body();
                
                if (str_contains($content, 'Sitemap:')) {
                    $this->line('  ✅ robots.txt contains sitemap reference');
                } else {
                    $issues[] = 'robots.txt missing sitemap reference';
                    $recommendations[] = 'Add sitemap URL to robots.txt';
                }
                
                if (str_contains($content, 'Disallow: /admin')) {
                    $this->line('  ✅ Admin areas properly disallowed');
                } else {
                    $issues[] = 'Admin areas not disallowed in robots.txt';
                }
            } else {
                $issues[] = 'robots.txt not accessible';
                $recommendations[] = 'Create and configure robots.txt file';
            }
        } catch (\Exception $e) {
            $issues[] = 'Error checking robots.txt: ' . $e->getMessage();
        }
    }

    private function checkSitemap($baseUrl, &$issues, &$recommendations)
    {
        $this->info('🗺️  Checking sitemap...');
        
        try {
            $response = Http::get("{$baseUrl}/sitemap.xml");
            
            if ($response->successful()) {
                $this->line('  ✅ Main sitemap accessible');
                
                // Check if it's a sitemap index
                if (str_contains($response->body(), '<sitemapindex')) {
                    $this->line('  ✅ Using sitemap index structure');
                } else {
                    $recommendations[] = 'Consider using sitemap index for better organization';
                }
            } else {
                $issues[] = 'Main sitemap not accessible';
                $recommendations[] = 'Generate and publish sitemap.xml';
            }
        } catch (\Exception $e) {
            $issues[] = 'Error checking sitemap: ' . $e->getMessage();
        }
    }

    private function checkMainPages($baseUrl, &$issues, &$recommendations)
    {
        $this->info('🏠 Checking main pages...');
        
        $pages = [
            '/' => 'Homepage',
            '/ukm' => 'UKM Index',
            '/events' => 'Events Index',
            '/about' => 'About Page',
            '/contact' => 'Contact Page',
        ];

        foreach ($pages as $path => $name) {
            try {
                $response = Http::get("{$baseUrl}{$path}");
                
                if ($response->successful()) {
                    $content = $response->body();
                    
                    // Check title
                    if (preg_match('/<title>(.*?)<\/title>/i', $content, $matches)) {
                        $title = trim($matches[1]);
                        if (strlen($title) > 60) {
                            $issues[] = "{$name}: Title too long (" . strlen($title) . " chars)";
                        } elseif (strlen($title) < 30) {
                            $issues[] = "{$name}: Title too short (" . strlen($title) . " chars)";
                        } else {
                            $this->line("  ✅ {$name}: Title length OK");
                        }
                    } else {
                        $issues[] = "{$name}: Missing title tag";
                    }
                    
                    // Check meta description
                    if (preg_match('/<meta name="description" content="(.*?)"/i', $content, $matches)) {
                        $description = trim($matches[1]);
                        if (strlen($description) > 160) {
                            $issues[] = "{$name}: Meta description too long";
                        } elseif (strlen($description) < 120) {
                            $issues[] = "{$name}: Meta description too short";
                        } else {
                            $this->line("  ✅ {$name}: Meta description length OK");
                        }
                    } else {
                        $issues[] = "{$name}: Missing meta description";
                    }
                    
                    // Check H1 tag
                    if (preg_match('/<h1[^>]*>(.*?)<\/h1>/i', $content, $matches)) {
                        $this->line("  ✅ {$name}: H1 tag present");
                    } else {
                        $issues[] = "{$name}: Missing H1 tag";
                    }
                    
                } else {
                    $issues[] = "{$name}: Page not accessible (HTTP {$response->status()})";
                }
            } catch (\Exception $e) {
                $issues[] = "{$name}: Error checking page - " . $e->getMessage();
            }
        }
    }

    private function checkUkmPages($baseUrl, &$issues, &$recommendations)
    {
        $this->info('🏢 Checking UKM pages...');
        
        $ukms = Ukm::active()->limit(5)->get();
        
        foreach ($ukms as $ukm) {
            try {
                $response = Http::get("{$baseUrl}/ukm/{$ukm->slug}");
                
                if ($response->successful()) {
                    $this->line("  ✅ {$ukm->name}: Page accessible");
                } else {
                    $issues[] = "{$ukm->name}: UKM page not accessible";
                }
            } catch (\Exception $e) {
                $issues[] = "{$ukm->name}: Error checking UKM page";
            }
        }
    }

    private function checkDatabaseContent(&$issues, &$recommendations)
    {
        $this->info('💾 Checking database content...');
        
        // Check UKMs without descriptions
        $ukmsWithoutDesc = Ukm::whereNull('description')->orWhere('description', '')->count();
        if ($ukmsWithoutDesc > 0) {
            $issues[] = "{$ukmsWithoutDesc} UKMs missing descriptions";
            $recommendations[] = 'Add descriptions to all UKMs for better SEO';
        }
        
        // Check events without descriptions
        $eventsWithoutDesc = Event::whereNull('description')->orWhere('description', '')->count();
        if ($eventsWithoutDesc > 0) {
            $issues[] = "{$eventsWithoutDesc} events missing descriptions";
            $recommendations[] = 'Add descriptions to all events for better SEO';
        }
        
        $this->line("  ✅ Database content checked");
    }

    private function displayResults($issues, $recommendations)
    {
        $this->newLine();
        
        if (empty($issues)) {
            $this->info('🎉 SEO audit completed successfully! No issues found.');
        } else {
            $this->error('❌ SEO Issues Found:');
            foreach ($issues as $issue) {
                $this->line("  • {$issue}");
            }
        }
        
        if (!empty($recommendations)) {
            $this->newLine();
            $this->warn('💡 Recommendations:');
            foreach ($recommendations as $recommendation) {
                $this->line("  • {$recommendation}");
            }
        }
        
        $this->newLine();
        $this->info('📊 SEO Audit Summary:');
        $this->line("  Issues found: " . count($issues));
        $this->line("  Recommendations: " . count($recommendations));
    }
}
