<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate {--save : Save sitemap to storage}';

    /**
     * The console command description.
     */
    protected $description = 'Generate XML sitemap for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        try {
            // Generate main sitemap
            $response = Http::get(route('sitemap.index'));
            
            if ($response->successful()) {
                if ($this->option('save')) {
                    Storage::disk('public')->put('sitemap.xml', $response->body());
                    $this->info('✅ Main sitemap saved to storage/app/public/sitemap.xml');
                }
                
                $this->info('✅ Main sitemap generated successfully');
                $this->line('URL: ' . route('sitemap.index'));
            } else {
                $this->error('❌ Failed to generate main sitemap');
                return 1;
            }

            // Generate sub-sitemaps
            $sitemaps = [
                'pages' => route('sitemap.pages'),
                'ukms' => route('sitemap.ukms'),
                'events' => route('sitemap.events'),
            ];

            foreach ($sitemaps as $name => $url) {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    if ($this->option('save')) {
                        Storage::disk('public')->put("sitemap-{$name}.xml", $response->body());
                        $this->info("✅ {$name} sitemap saved");
                    }
                    $this->info("✅ {$name} sitemap generated: {$url}");
                } else {
                    $this->error("❌ Failed to generate {$name} sitemap");
                }
            }

            $this->newLine();
            $this->info('🎉 Sitemap generation completed!');
            $this->line('Submit your sitemap to search engines:');
            $this->line('• Google Search Console: https://search.google.com/search-console');
            $this->line('• Bing Webmaster Tools: https://www.bing.com/webmasters');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error generating sitemap: ' . $e->getMessage());
            return 1;
        }
    }
}
