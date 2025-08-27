<?php
echo "=== ADDING UKM CONTACT INFO ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ukmwebv', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Contact info for each UKM
    $contactData = [
        'badminton' => [
            'email' => 'badminton@ukm.telkomuniversity.ac.id',
            'phone' => '081234567801',
            'instagram' => '@badminton_telkomjkt',
            'website' => 'https://badminton.telkomuniversity.ac.id'
        ],
        'dpm' => [
            'email' => 'dpm@ukm.telkomuniversity.ac.id',
            'phone' => '081234567802',
            'instagram' => '@dpm_telkomjkt',
            'website' => 'https://dpm.telkomuniversity.ac.id'
        ],
        'esport' => [
            'email' => 'esport@ukm.telkomuniversity.ac.id',
            'phone' => '081234567803',
            'instagram' => '@esport_telkomjkt',
            'website' => 'https://esport.telkomuniversity.ac.id'
        ],
        'futsal' => [
            'email' => 'futsal@ukm.telkomuniversity.ac.id',
            'phone' => '081234567804',
            'instagram' => '@futsal_telkomjkt',
            'website' => 'https://futsal.telkomuniversity.ac.id'
        ],
        'imma' => [
            'email' => 'imma@ukm.telkomuniversity.ac.id',
            'phone' => '081234567805',
            'instagram' => '@imma_telkomjkt',
            'website' => 'https://imma.telkomuniversity.ac.id'
        ],
        'mapala' => [
            'email' => 'mapala@ukm.telkomuniversity.ac.id',
            'phone' => '081234567806',
            'instagram' => '@mapala_telkomjkt',
            'website' => 'https://mapala.telkomuniversity.ac.id'
        ],
        'pmk' => [
            'email' => 'pmk@ukm.telkomuniversity.ac.id',
            'phone' => '081234567807',
            'instagram' => '@pmk_telkomjkt',
            'website' => 'https://pmk.telkomuniversity.ac.id'
        ],
        'seni-budaya' => [
            'email' => 'senibudaya@ukm.telkomuniversity.ac.id',
            'phone' => '081234567808',
            'instagram' => '@senibudaya_telkomjkt',
            'website' => 'https://senibudaya.telkomuniversity.ac.id'
        ],
        'sistem-informasi' => [
            'email' => 'si@ukm.telkomuniversity.ac.id',
            'phone' => '081234567809',
            'instagram' => '@si_telkomjkt',
            'website' => 'https://si.telkomuniversity.ac.id'
        ]
    ];
    
    echo "📋 Adding contact information to UKMs...\n\n";
    
    $sql = "UPDATE ukms SET contact_info = ?, updated_at = NOW() WHERE slug = ?";
    $stmt = $pdo->prepare($sql);
    
    $updated = 0;
    $failed = 0;
    
    foreach ($contactData as $slug => $contact) {
        try {
            $contactJson = json_encode($contact);
            
            if ($stmt->execute([$contactJson, $slug])) {
                echo "   ✅ Updated {$slug}:\n";
                echo "      📧 Email: {$contact['email']}\n";
                echo "      📱 Phone: {$contact['phone']}\n";
                echo "      📷 Instagram: {$contact['instagram']}\n";
                echo "      🌐 Website: {$contact['website']}\n\n";
                $updated++;
            } else {
                echo "   ❌ Failed to update: {$slug}\n";
                $failed++;
            }
        } catch (Exception $e) {
            echo "   ❌ Error updating {$slug}: " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "=== RESULT ===\n";
    echo "✅ Successfully updated: {$updated} UKMs\n";
    echo "❌ Failed: {$failed} UKMs\n";
    
    // Verify the updates
    echo "\n📊 UKM Contact Information:\n";
    $result = $pdo->query("SELECT name, slug, contact_info FROM ukms WHERE contact_info IS NOT NULL ORDER BY name");
    $count = 0;
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        $contactInfo = json_decode($row['contact_info'], true);
        
        echo "   {$count}. {$row['name']}\n";
        echo "      📧 Email: " . ($contactInfo['email'] ?? 'N/A') . "\n";
        echo "      📱 Phone: " . ($contactInfo['phone'] ?? 'N/A') . "\n";
        echo "      📷 Instagram: " . ($contactInfo['instagram'] ?? 'N/A') . "\n";
        echo "      🌐 Website: " . ($contactInfo['website'] ?? 'N/A') . "\n\n";
    }
    
    echo "✅ Total UKMs with contact info: {$count}\n";
    
    echo "\n🌐 Test URLs:\n";
    echo "   Public UKM: http://localhost:8000/ukm/dpm\n";
    echo "   Admin UKM Detail: http://localhost:8000/admin/ukms/dpm\n";
    echo "   Admin UKM List: http://localhost:8000/admin/ukms\n";
    
    echo "\n📋 Expected Results:\n";
    echo "   • Contact info (email, phone, instagram, website) will be displayed\n";
    echo "   • Leader info will be displayed separately\n";
    echo "   • Both old contact info and new leader info will be visible\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== CONTACT INFO UPDATE COMPLETE ===\n";
?>
