<?php

echo "=== ADDING FINAL UKM FIELDS ===\n\n";

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
    
    // Update existing UKM with sample data
    echo "\n📊 UPDATING EXISTING UKM WITH SAMPLE DATA:\n";
    try {
        $stmt = $pdo->prepare("
            UPDATE ukms 
            SET achievements = ?, updated_at = NOW() 
            WHERE id = 1 AND (achievements IS NULL OR achievements = '')
        ");
        
        $sampleAchievements = "- Juara 1 Lomba Programming 2023\n- Juara 2 Hackathon Nasional 2024\n- Best Innovation Award 2024";
        
        $stmt->execute([$sampleAchievements]);
        echo "✅ Sample achievements added to existing UKM\n";
    } catch (Exception $e) {
        echo "❌ Error updating sample data: " . $e->getMessage() . "\n";
    }
    
    // Show updated structure
    echo "\n📋 UPDATED UKM TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE ukms");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['achievements', 'organization_structure', 'registration_status', 'requirements'])) {
            echo "✅ {$column['Field']} ({$column['Type']}) - {$column['Comment']}\n";
        }
    }
    
    // Test data
    echo "\n📊 CURRENT UKM DATA:\n";
    $stmt = $pdo->query("SELECT id, name, achievements, organization_structure FROM ukms LIMIT 3");
    $ukms = $stmt->fetchAll();
    
    foreach ($ukms as $ukm) {
        $achievements = $ukm['achievements'] ? 'Has achievements' : 'No achievements';
        $orgStructure = $ukm['organization_structure'] ? 'Has structure image' : 'No structure image';
        echo "- UKM {$ukm['id']}: {$ukm['name']} - {$achievements}, {$orgStructure}\n";
    }
    
    echo "\n✅ UKM fields setup complete!\n";
    echo "🎯 Admin dan Ketua UKM sekarang dapat:\n";
    echo "   - Menambahkan prestasi UKM\n";
    echo "   - Upload gambar struktur organisasi\n";
    echo "   - Mengedit informasi UKM dengan field baru\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
