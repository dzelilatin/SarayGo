<?php
namespace Dzelitin\SarayGo\dao;
require_once __DIR__ . '/BaseDao.php';
require_once(__DIR__ . '/../../data/roles.php');

use Dzelitin\SarayGo\Roles;

class AuthDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }

    protected function query_unique($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = Roles::USER;
        }

        $sql = "INSERT INTO users (username, email, password, role, created_at) 
                VALUES (:username, :email, :password, :role, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role']
        ]);

        return $this->conn->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET 
                username = :username,
                email = :email,
                role = :role";
        
        // Only update password if provided
        if (!empty($data['password'])) {
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        
        $params = [
            'id' => $id,
            'username' => $data['username'],
            'email' => $data['email'],
            'role' => $data['role']
        ];
        
        if (!empty($data['password'])) {
            $params['password'] = $data['password'];
        }
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}
?> 