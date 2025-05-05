<?php
require_once __DIR__ . '/config.php';

try {
    $conn = \Dzelitin\SarayGo\Database::connect();
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>