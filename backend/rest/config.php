<?php
namespace Dzelitin\SarayGo;
use PDO;
use PDOException;

class Database {
    const HOST = '127.0.0.1';  // Use 127.0.0.1 instead of localhost
    const DB_NAME = 'SarayGo';
    const USER = 'root';
    const PASSWORD = '';  // Adjust this if you use a password for root
    private static $connection = null;

    public static function connect() {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME,
                    self::USER,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    // JWT Secret Key Definition
    public static function JWT_SECRET() {
        return 'SarayGo_2025_Secure_JWT_Key_8f7d3b2a1e6c9f4a5b8d2e7c3f6a9b4d';
    }
}
?>