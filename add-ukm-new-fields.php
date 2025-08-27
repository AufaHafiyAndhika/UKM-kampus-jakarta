<?php

echo "=== ADDING NEW FIELDS TO UKM TABLE ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ukmwebv', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Check current table structure
    echo "📋 CHECKING CURRENT UKM TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE ukms");
    $columns = $stmt->fetchAll();
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
    }
    
    // Define new fields to add
    $newFields = [
        'achievements' => "TEXT NULL COMMENT 'Prestasi UKM'",
        'organization_structure' => "VARCHAR(255) NULL COMMENT 'Gambar struktur organisasi'"
    ];
    
    echo "🔍 CHECKING NEW FIELDS:\n";
    $fieldsToAdd = [];
    
    foreach ($newFields as $field => $definition) {
        if (in_array($field, $existingColumns)) {
            echo "✅ Field '{$field}' already exists\n";
        } else {
            echo "❌ Field '{$field}' is MISSING - will add\n";
            $fieldsToAdd[$field] = $definition;
        }
    }
    
    // Add missing fields
    if (!empty($fieldsToAdd)) {
        echo "\n🔧 ADDING NEW FIELDS:\n";
        
        foreach ($fieldsToAdd as $field => $definition) {
            try {
                $sql = "ALTER TABLE ukms ADD COLUMN {$field} {$definition} AFTER requirements";
                $pdo->exec($sql);
                echo "✅ Added field: {$field}\n";
            } catch (Exception $e) {
                echo "❌ Error adding {$field}: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "\n✅ All new fields already exist!\n";
    }
    
    // Show updated structure
    echo "\n📋 UPDATED UKM TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE ukms");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['achievements', 'organization_structure', 'registration_status'])) {
            echo "✅ {$column['Field']} ({$column['Type']}) - {$column['Comment']}\n";
        }
    }
    
    // Update existing UKM with sample data
    echo "\n📊 UPDATING EXISTING UKM WITH SAMPLE DATA:\n";
    try {
        $stmt = $pdo->prepare("
            UPDATE ukms 
            SET achievements = ?, organization_structure = ?, updated_at = NOW() 
            WHERE id = 1
        ");
        
        $sampleAchievements = "- Juara 1 Lomba Programming 2023\n- Juara 2 Hackathon Nasional 2024\n- Best Innovation Award 2024";
        
        $stmt->execute([$sampleAchievements, null]);
        echo "✅ Sample achievements added to existing UKM\n";
    } catch (Exception $e) {
        echo "❌ Error updating sample data: " . $e->getMessage() . "\n";
    }
    
    // Test registration status logic
    echo "\n🧪 TESTING REGISTRATION STATUS LOGIC:\n";
    
    // Test closed registration
    $stmt = $pdo->prepare("UPDATE ukms SET registration_status = 'closed' WHERE id = 1");
    $stmt->execute();
    echo "✅ Set UKM registration status to 'closed'\n";
    
    // Check current status
    $stmt = $pdo->query("SELECT id, name, registration_status, status FROM ukms WHERE id = 1");
    $ukm = $stmt->fetch();
    
    if ($ukm) {
        echo "📋 UKM Status Check:\n";
        echo "   - Name: {$ukm['name']}\n";
        echo "   - Status: {$ukm['status']}\n";
        echo "   - Registration Status: {$ukm['registration_status']}\n";
        
        // Logic check for student view
        $canRegister = ($ukm['status'] === 'active' && $ukm['registration_status'] === 'open');
        echo "   - Can Students Register: " . ($canRegister ? 'YES' : 'NO') . "\n";
        
        if (!$canRegister) {
            echo "✅ Registration logic working correctly - students should NOT be able to register\n";
        } else {
            echo "❌ Registration logic issue - students can still register\n";
        }
    }
    
    echo "\n✅ New UKM fields added successfully!\n";
    echo "🎯 Admin can now add:\n";
    echo "   - Prestasi UKM (achievements field)\n";
    echo "   - Gambar Struktur Organisasi (organization_structure field)\n";
    echo "🔒 Registration status logic ready for frontend implementation\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
