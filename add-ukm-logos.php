<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ADDING UKM LOGOS ===\n\n";

use App\Models\Ukm;
use Illuminate\Support\Facades\Storage;

try {
    // Create logos directory if it doesn't exist
    $logoDir = storage_path('app/public/ukms/logos');
    if (!file_exists($logoDir)) {
        mkdir($logoDir, 0755, true);
        echo "✅ Created logos directory: {$logoDir}\n";
    }
    
    // Sample logo URLs (you can replace these with actual logo files)
    $logoMappings = [
        'badminton' => 'https://via.placeholder.com/200x200/3B82F6/FFFFFF?text=BADMINTON',
        'dpm' => 'https://via.placeholder.com/200x200/1F2937/FFFFFF?text=DPM',
        'esport' => 'https://via.placeholder.com/200x200/8B5CF6/FFFFFF?text=ESPORT',
        'futsal' => 'https://via.placeholder.com/200x200/10B981/FFFFFF?text=FUTSAL',
        'imma' => 'https://via.placeholder.com/200x200/059669/FFFFFF?text=IMMA',
        'mapala' => 'https://via.placeholder.com/200x200/92400E/FFFFFF?text=MAPALA',
        'pmk' => 'https://via.placeholder.com/200x200/DC2626/FFFFFF?text=PMK',
        'seni-budaya' => 'https://via.placeholder.com/200x200/F59E0B/FFFFFF?text=SENI',
        'sistem-informasi' => 'https://via.placeholder.com/200x200/6366F1/FFFFFF?text=SI'
    ];
    
    echo "Downloading and assigning logos to UKMs...\n\n";
    
    $updated = 0;
    $failed = 0;
    
    foreach ($logoMappings as $slug => $logoUrl) {
        try {
            $ukm = Ukm::where('slug', $slug)->first();
            
            if (!$ukm) {
                echo "   ⚠️  UKM not found: {$slug}\n";
                continue;
            }
            
            // Download logo
            $logoContent = file_get_contents($logoUrl);
            if ($logoContent === false) {
                echo "   ❌ Failed to download logo for: {$ukm->name}\n";
                $failed++;
                continue;
            }
            
            // Save logo file
            $logoFilename = $slug . '-logo.png';
            $logoPath = 'ukms/logos/' . $logoFilename;
            $fullPath = storage_path('app/public/' . $logoPath);
            
            if (file_put_contents($fullPath, $logoContent)) {
                // Update UKM with logo path
                $ukm->update(['logo' => $logoPath]);
                
                echo "   ✅ {$ukm->name}\n";
                echo "      📁 Logo saved: {$logoPath}\n";
                echo "      🌐 URL: " . asset('storage/' . $logoPath) . "\n\n";
                
                $updated++;
            } else {
                echo "   ❌ Failed to save logo for: {$ukm->name}\n";
                $failed++;
            }
            
        } catch (Exception $e) {
            echo "   ❌ Error processing {$slug}: " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "=== RESULT ===\n";
    echo "✅ Successfully updated: {$updated} UKMs\n";
    echo "❌ Failed: {$failed} UKMs\n";
    
    // Show UKMs with logos
    echo "\n📊 UKMs with Logos:\n";
    $ukmsWithLogos = Ukm::whereNotNull('logo')->get();
    foreach ($ukmsWithLogos as $ukm) {
        echo "   • {$ukm->name}: {$ukm->logo}\n";
    }
    
    echo "\n🌐 Test URLs:\n";
    echo "   Admin UKMs: http://localhost:8000/admin/ukms\n";
    echo "   Public UKMs: http://localhost:8000/ukm\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== LOGO ASSIGNMENT COMPLETE ===\n";
?>
