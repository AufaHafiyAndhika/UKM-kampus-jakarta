<?php

echo "=== ADDING ROLE COLUMN TO USERS TABLE ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ukmwebv', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Check if role column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Role column already exists\n";
    } else {
        echo "🔧 Adding role column...\n";
        
        // Add role column
        $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('admin', 'student', 'ketua_ukm') NOT NULL DEFAULT 'student' AFTER status");
        
        echo "✅ Role column added successfully\n";
    }
    
    // Update status enum to include more options
    echo "🔧 Updating status enum...\n";
    $pdo->exec("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive', 'suspended', 'pending', 'graduated') NOT NULL DEFAULT 'pending'");
    echo "✅ Status enum updated\n";
    
    // Show updated structure
    echo "\n📋 UPDATED TABLE STRUCTURE:\n";
    $stmt = $pdo->query("DESCRIBE users");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        if (in_array($column['Field'], ['role', 'status'])) {
            echo "✅ {$column['Field']} ({$column['Type']}) - {$column['Null']} - Default: {$column['Default']}\n";
        }
    }
    
    echo "\n🎯 Table is now ready for user insertion with roles!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
