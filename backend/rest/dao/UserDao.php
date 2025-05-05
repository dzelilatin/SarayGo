<?php
namespace Dzelitin\SarayGo\Dao;
require_once __DIR__ . '/BaseDao.php';
use Dzelitin\SarayGo\Dao\BaseDao;
use PDO;
use Exception;

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

        // Always hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user', // Default role
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        error_log("Creating user with email: " . $email);
        error_log("Original password: " . $password);
        error_log("Hashed password: " . $hashedPassword);
        
        $result = $this->insert($data);
        if ($result) {
            error_log("User created successfully");
        } else {
            error_log("Failed to create user");
        }
        return $result;
    }

    // Get user by username
    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    // Get user by email
    public function getUserByEmail($email) {
        error_log("Getting user by email: " . $email);
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            error_log("User found: " . json_encode($user));
        } else {
            error_log("No user found with email: " . $email);
        }
        return $user ?: null;
    }

    // Verify password for user
    public function verifyPassword($user, $password) {
        error_log("Verifying password for user: " . $user['email']);
        error_log("Stored hash: " . $user['password']);
        error_log("Input password: " . $password);
        
        // If the stored password is not hashed, hash it first
        if (strlen($user['password']) < 60) { // bcrypt hashes are always 60 characters
            error_log("Password in database is not hashed, hashing it now");
            $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
            $this->update($user['id'], ['password' => $hashedPassword]);
            $user['password'] = $hashedPassword;
        }
        
        $result = password_verify($password, $user['password']);
        error_log("Password verification result: " . ($result ? "true" : "false"));
        return $result;
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

    // Get users by role
    public function getUsersByRole($role) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role = :role");
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update user role
    public function updateUserRole($id, $role) {
        $data = ['role' => $role];
        return $this->update($id, $data);
    }

    // Get user statistics
    public function getUserStatistics() {
        $stats = [];

        // Get total users
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch()['total'];

        // Get users by role
        $stmt = $this->conn->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $stmt->execute();
        $stats['users_by_role'] = $stmt->fetchAll();

        // Get active users (users who logged in within the last 30 days)
        $stmt = $this->conn->prepare("SELECT COUNT(*) as active FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $stats['active_users'] = $stmt->fetch()['active'];

        // Get new users this month
        $stmt = $this->conn->prepare("SELECT COUNT(*) as new_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $stats['new_users'] = $stmt->fetch()['new_users'];

        return $stats;
    }

    // Change user password
    public function changePassword($user_id, $current_password, $new_password) {
        $user = $this->getById($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        if (!$this->verifyPassword($user, $current_password)) {
            throw new Exception("Current password is incorrect");
        }

        $data = ['password' => password_hash($new_password, PASSWORD_DEFAULT)];
        return $this->update($user_id, $data);
    }

    // Reset user password
    public function resetPassword($email) {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Generate a temporary password
        $tempPassword = bin2hex(random_bytes(8));
        $data = ['password' => password_hash($tempPassword, PASSWORD_DEFAULT)];
        
        if ($this->update($user['id'], $data)) {
            return $tempPassword; // Return the temporary password to be sent to the user
        }
        return false;
    }

    // Get active users
    public function getActiveUsers($limit) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY last_login DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Search users
    public function searchUsers($query) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username LIKE :query OR email LIKE :query");
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get clean user data (without password)
    public function getCleanUserData($user) {
        if (!$user) return null;
        $cleanUser = $user;
        unset($cleanUser['password']);
        return $cleanUser;
    }
}
?>
