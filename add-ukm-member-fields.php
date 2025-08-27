<?php

echo "=== ADDING UKM MEMBER REGISTRATION FIELDS ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ukmwebv', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Check current table structure
    echo "📋 CHECKING CURRENT UKM_MEMBERS TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE ukm_members");
    $columns = $stmt->fetchAll();
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
    // Define new fields to add
    $newFields = [
        'previous_experience' => "TEXT NULL COMMENT 'Pengalaman organisasi sebelumnya'",
        'skills_interests' => "TEXT NULL COMMENT 'Keahlian dan minat'",
        'reason_joining' => "TEXT NULL COMMENT 'Alasan bergabung'",
        'preferred_division' => "VARCHAR(255) NULL COMMENT 'Divisi yang diminati'",
        'cv_file' => "VARCHAR(255) NULL COMMENT 'Path file CV'",
        'applied_at' => "TIMESTAMP NULL COMMENT 'Tanggal mendaftar'",
        'approved_at' => "TIMESTAMP NULL COMMENT 'Tanggal disetujui'",
        'rejected_at' => "TIMESTAMP NULL COMMENT 'Tanggal ditolak'",
        'rejection_reason' => "TEXT NULL COMMENT 'Alasan penolakan'",
        'approved_by' => "BIGINT UNSIGNED NULL COMMENT 'User ID yang menyetujui'",
        'rejected_by' => "BIGINT UNSIGNED NULL COMMENT 'User ID yang menolak'"
    ];
    
    echo "\n🔍 CHECKING NEW FIELDS:\n";
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
                $sql = "ALTER TABLE ukm_members ADD COLUMN {$field} {$definition}";
                $pdo->exec($sql);
                echo "✅ Added field: {$field}\n";
            } catch (Exception $e) {
                echo "❌ Error adding {$field}: " . $e->getMessage() . "\n";
            }
        }
        
        // Add foreign key constraints
        echo "\n🔗 ADDING FOREIGN KEY CONSTRAINTS:\n";
        try {
            $pdo->exec("ALTER TABLE ukm_members ADD CONSTRAINT fk_approved_by FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL");
            echo "✅ Added FK constraint for approved_by\n";
        } catch (Exception $e) {
            echo "⚠️  FK constraint for approved_by: " . $e->getMessage() . "\n";
        }
        
        try {
            $pdo->exec("ALTER TABLE ukm_members ADD CONSTRAINT fk_rejected_by FOREIGN KEY (rejected_by) REFERENCES users(id) ON DELETE SET NULL");
            echo "✅ Added FK constraint for rejected_by\n";
        } catch (Exception $e) {
            echo "⚠️  FK constraint for rejected_by: " . $e->getMessage() . "\n";
        }
    } else {
        echo "\n✅ All new fields already exist!\n";
    }
    
    // Show updated structure
    echo "\n📋 UPDATED UKM_MEMBERS TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE ukm_members");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], array_keys($newFields))) {
            echo "✅ {$column['Field']} ({$column['Type']}) - {$column['Comment']}\n";
        }
    }
    
    // Update existing memberships to have proper status
    echo "\n📊 UPDATING EXISTING MEMBERSHIPS:\n";
    try {
        $stmt = $pdo->exec("UPDATE ukm_members SET status = 'active' WHERE status IS NULL OR status = ''");
        echo "✅ Updated {$stmt} existing memberships to 'active' status\n";
    } catch (Exception $e) {
        echo "❌ Error updating existing memberships: " . $e->getMessage() . "\n";
    }
    
    // Test data
    echo "\n📊 CURRENT UKM_MEMBERS DATA:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total, status, COUNT(*) as count FROM ukm_members GROUP BY status");
    $stats = $stmt->fetchAll();
    
    foreach ($stats as $stat) {
        echo "- Status '{$stat['status']}': {$stat['count']} members\n";
    }
    
    echo "\n✅ UKM member registration fields setup complete!\n";
    echo "🎯 Sekarang mahasiswa dapat:\n";
    echo "   - Mengisi form pendaftaran lengkap\n";
    echo "   - Upload CV\n";
    echo "   - Menunggu approval dari ketua UKM\n";
    echo "🎯 Ketua UKM dapat:\n";
    echo "   - Melihat detail pendaftaran\n";
    echo "   - Menerima atau menolak anggota\n";
    echo "   - Mengeluarkan anggota dari UKM\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
