<?php
require_once __DIR__ . '/../config.php';

class BaseDao {
   protected $table;
   protected $connection;
   private static $allowedTables = ['users', 'moods', 'user_moods', 'categories', 'activities', 'recommendations', 'reviews', 'blogs', 'contact'];

   public function __construct($table) {
       if (!in_array($table, self::$allowedTables)) {
           throw new Exception("Invalid table name: $table");
       }
       $this->table = $table;
       $this->connection = Database::connect();
   }

   public function getAll() {
       $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getById($id) {
       $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
       $stmt->bindValue(':id', $id, PDO::PARAM_INT);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function getByEmail($email) {
      if ($this->table !== 'users') {
         throw new Exception("getByEmail method is only allowed for the 'users' table.");
       }
       $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
       $stmt->bindValue(':email', $email, PDO::PARAM_STR);
       $stmt->execute();
       return $stmt->fetch();
    }

   public function insert($data) {
       $columns = implode(", ", array_keys($data));
       $placeholders = ":" . implode(", :", array_keys($data));
       $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
       $stmt = $this->connection->prepare($sql);
       return $stmt->execute($data);
   }

   public function update($id, $data) {
       $fields = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
       $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
       $stmt = $this->connection->prepare($sql);
       $data['id'] = $id;
       return $stmt->execute($data);
   }

   public function delete($id) {
       $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
       $stmt->bindValue(':id', $id, PDO::PARAM_INT);
       return $stmt->execute();
   }
}
?>
