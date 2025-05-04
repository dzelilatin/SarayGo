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
}
?>