<?php
namespace Dzelitin\SarayGo\dao;
require_once __DIR__ . '/../config.php';
use Dzelitin\SarayGo\Database;
use PDO;
use PDOException;

if (!class_exists('Dzelitin\SarayGo\dao\BaseDao')) {
    class BaseDao {
        protected $conn;
        protected $table_name;
        private static $allowedTables = ['users', 'moods', 'user_moods', 'categories', 'activities', 'recommendations', 'reviews', 'blogs', 'contact'];

        public function __construct($table_name) {
            if (!in_array($table_name, self::$allowedTables)) {
                throw new Exception("Invalid table name: $table_name");
            }
            $this->table_name = $table_name;
            $this->conn = $this->getConnection();
        }

        protected function getConnection() {
            try {
                return Database::connect();
            } catch(PDOException $e) {
                error_log("Connection failed: " . $e->getMessage());
                throw $e;
            }
        }

        public function getAll() {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name}");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getByEmail($email) {
            if ($this->table_name !== 'users') {
                throw new Exception("getByEmail method is only allowed for the 'users' table.");
            }
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getByCategory($category) {
            if (!in_array($this->table_name, ['activities', 'recommendations'])) {
                throw new Exception("getByCategory method is only allowed for the 'activities' or 'recommendations' tables.");
            }
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table_name} WHERE category = :category");
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insert($data) {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO {$this->table_name} ($columns) VALUES ($placeholders)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($data);
        }

        public function update($id, $data) {
            $fields = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $sql = "UPDATE {$this->table_name} SET $fields WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $data['id'] = $id;
            return $stmt->execute($data);
        }

        public function delete($id) {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table_name} WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }
}
?>
