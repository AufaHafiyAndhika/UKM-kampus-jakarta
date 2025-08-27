<?php
echo "=== ASSIGNING UKM LEADERS ===\n\n";

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ukmwebv', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Get available students
    echo "📊 Available Students:\n";
    $result = $pdo->query("SELECT id, name, email, nim FROM users WHERE role = 'student' ORDER BY name");
    $students = [];
    $count = 0;
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $row;
        $count++;
        echo "   {$count}. {$row['name']} (NIM: {$row['nim']})\n";
    }
    
    if (count($students) == 0) {
        echo "❌ No students found! Please create students first.\n";
        exit;
    }
    
    echo "\n📋 Assigning leaders to UKMs...\n\n";
    
    // Assign leaders to UKMs (randomly from available students)
    $leaderAssignments = [
        'badminton' => 0,        // Rehan Dwiandra
        'dpm' => 1,              // Ryemius Marghareta Siregar
        'esport' => 2,           // Amanda Riski Agustian
        'futsal' => 3,           // Najla Ramadina Sulistyowati
        'imma' => 4,             // Nabilla Alyvia
        'mapala' => 5,           // Aras Agita Fasya
        // Leave some UKMs without leaders to show the fallback message
        // 'pmk' => 6,
        // 'seni-budaya' => 7,
        // 'sistem-informasi' => 8,
    ];
    
    $sql = "UPDATE ukms SET leader_id = ?, updated_at = NOW() WHERE slug = ?";
    $stmt = $pdo->prepare($sql);
    
    $assigned = 0;
    $failed = 0;
    
    foreach ($leaderAssignments as $slug => $studentIndex) {
        try {
            if (isset($students[$studentIndex])) {
                $student = $students[$studentIndex];
                
                if ($stmt->execute([$student['id'], $slug])) {
                    echo "   ✅ {$slug}: {$student['name']} (NIM: {$student['nim']})\n";
                    $assigned++;
                } else {
                    echo "   ❌ Failed to assign leader to: {$slug}\n";
                    $failed++;
                }
            } else {
                echo "   ❌ Student index {$studentIndex} not found for: {$slug}\n";
                $failed++;
            }
        } catch (Exception $e) {
            echo "   ❌ Error assigning leader to {$slug}: " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "\n=== RESULT ===\n";
    echo "✅ Successfully assigned: {$assigned} leaders\n";
    echo "❌ Failed: {$failed} assignments\n";
    
    // Show UKMs with and without leaders
    echo "\n📊 UKM Leadership Status:\n";
    $result = $pdo->query("
        SELECT u.name as ukm_name, u.slug, us.name as leader_name, us.nim as leader_nim
        FROM ukms u
        LEFT JOIN users us ON u.leader_id = us.id
        ORDER BY u.name
    ");
    
    $withLeader = 0;
    $withoutLeader = 0;
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['leader_name']) {
            echo "   ✅ {$row['ukm_name']}: {$row['leader_name']} (NIM: {$row['leader_nim']})\n";
            $withLeader++;
        } else {
            echo "   ❌ {$row['ukm_name']}: Belum ada ketua\n";
            $withoutLeader++;
        }
    }
    
    echo "\n📈 Summary:\n";
    echo "   UKMs with Leader: {$withLeader}\n";
    echo "   UKMs without Leader: {$withoutLeader}\n";
    
    echo "\n🌐 Test URLs:\n";
    echo "   Public UKM: http://localhost:8000/ukm/badminton\n";
    echo "   Admin UKM: http://localhost:8000/admin/ukms\n";
    echo "   UKM List: http://localhost:8000/ukm\n";
    
    echo "\n📋 Expected Results:\n";
    echo "   • UKMs with leaders will show leader name and NIM\n";
    echo "   • UKMs without leaders will show: 'Ketua belum ada, mungkin pendaftaran anggota akan tertunda'\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== LEADER ASSIGNMENT COMPLETE ===\n";
?>
