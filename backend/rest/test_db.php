<?php
require_once __DIR__ . '/config.php';

try {
    $conn = \Dzelitin\SarayGo\Database::connect();
    echo "Database connection successful!\n";

    // Check if activities table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'activities'");
    if ($stmt->rowCount() > 0) {
        echo "Activities table exists!\n";
        
        // Get table structure
        $stmt = $conn->query("DESCRIBE activities");
        echo "\nTable structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
        
        // Count records
        $stmt = $conn->query("SELECT COUNT(*) as count FROM activities");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "\nNumber of records: {$count}\n";
    } else {
        echo "Activities table does not exist!\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
