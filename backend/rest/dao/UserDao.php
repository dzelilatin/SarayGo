<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {

    public function __construct() {
        parent::__construct('users'); // Set the table to 'users'
    }

    // Create a new user
    public function createUser($username, $email, $password) {
        // Check if username or email already exists
        if ($this->getUserByUsername($username)) {
            throw new Exception("Username already exists");
        }
        if ($this->getUserByEmail($email)) {
            throw new Exception("Email already exists");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get user by username
    public function getUserByUsername($username) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user ?: null; // Return null if no user found
    }

    // Get user by email
    public function getUserByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
        return $user ?: null; // Return null if no user found
    }

    // Verify password for user
    public function verifyPassword($user, $password) {
        return password_verify($password, $user['password']);
    }

    // Update user information (password included)
    public function updateUser($id, $username, $email, $password = null) {
        $data = [
            'username' => $username,
            'email' => $email,
        ];

        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this->update($id, $data); // Use BaseDao's update method
    }
}
?>
