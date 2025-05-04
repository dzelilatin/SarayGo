<?php
namespace Dzelitin\SarayGo\Dao;
require_once __DIR__ . '/BaseDao.php';
use Dzelitin\SarayGo\Dao\BaseDao;

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
            'role' => 'user', // Default role
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

    // Get users by role
    public function getUsersByRole($role) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE role = :role");
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
        $stmt = $this->connection->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $stats['total_users'] = $stmt->fetch()['total'];

        // Get users by role
        $stmt = $this->connection->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $stmt->execute();
        $stats['users_by_role'] = $stmt->fetchAll();

        // Get active users (users who logged in within the last 30 days)
        $stmt = $this->connection->prepare("SELECT COUNT(*) as active FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->execute();
        $stats['active_users'] = $stmt->fetch()['active'];

        // Get new users this month
        $stmt = $this->connection->prepare("SELECT COUNT(*) as new_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
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
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY last_login DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Search users
    public function searchUsers($query) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username LIKE :query OR email LIKE :query");
        $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
