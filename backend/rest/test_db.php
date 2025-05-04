<?php
$host = 'localhost';        // or 127.0.0.1
$db   = 'SarayGo';    // your database name
$user = 'root';    // your DB username (e.g., root)
$pass = '';    // your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
