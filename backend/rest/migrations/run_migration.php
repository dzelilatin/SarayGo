<?php
require_once __DIR__ . '/../config.php';

try {
    $host = 'localhost';
    $dbname = 'saraygo';
    $username = 'root';
    $password = '';
    
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read and execute the migration file
    $sql = file_get_contents(__DIR__ . '/001_add_activity_columns.sql');
    $conn->exec($sql);
    
    echo "Migration completed successfully!\n";
} catch(PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
} 