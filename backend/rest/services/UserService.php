<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
use Dzelitin\SarayGo\dao\UserDao;

class UserService extends BaseService {
    private $minUsernameLength = 3;
    private $maxUsernameLength = 50;
    private $minPasswordLength = 6;
    private $maxPasswordLength = 50;
    private $validRoles = ['user', 'admin'];

    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        $this->validateUserData($data);
        $this->checkDuplicateUser($data);
        return $this->dao->insert($data);
    }

    public function update($id, $data) {
        $this->validateUserData($data);
        $this->checkDuplicateUser($data, $id);
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }

    public function getByEmail($email) {
        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        return $this->dao->getByEmail($email);
    }

    public function getByUsername($username) {
        if (empty($username)) {
            throw new Exception("Username cannot be empty");
        }
        return $this->dao->getByUsername($username);
    }

    public function register($data) {
        $this->validateUserData($data, true);
        $this->checkDuplicateUser($data);
        return $this->dao->createUser($data['username'], $data['email'], $data['password']);
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new \Exception("Email and password are required");
        }

        $user = $this->dao->getUserByEmail($email);
        if (!$user) {
            throw new \Exception("User not found");
        }

        if (!$this->dao->verifyPassword($user, $password)) {
            throw new \Exception("Invalid password");
        }

        // Return clean user data without password
        return $this->dao->getCleanUserData($user);
    }

    public function changePassword($user_id, $current_password, $new_password) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        if (strlen($new_password) < $this->minPasswordLength || 
            strlen($new_password) > $this->maxPasswordLength) {
            throw new Exception("Password must be between {$this->minPasswordLength} and {$this->maxPasswordLength} characters");
        }
        return $this->dao->changePassword($user_id, $current_password, $new_password);
    }

    public function resetPassword($email) {
        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        return $this->dao->resetPassword($email);
    }

    public function getActiveUsers($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getActiveUsers($limit);
    }

    public function searchUsers($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchUsers($query);
    }

    public function updateProfile($id, $data) {
        $this->validateUserData($data, false);
        $this->checkDuplicateUser($data, $id);
        return $this->dao->updateUser($id, $data['username'], $data['email'], $data['password'] ?? null);
    }

    public function getUsersByRole($role) {
        if (!in_array($role, $this->validRoles)) {
            throw new \Exception("Invalid role. Must be one of: " . implode(', ', $this->validRoles));
        }
        return $this->dao->getUsersByRole($role);
    }

    public function updateUserRole($id, $role) {
        if (!in_array($role, $this->validRoles)) {
            throw new \Exception("Invalid role. Must be one of: " . implode(', ', $this->validRoles));
        }
        return $this->dao->updateUserRole($id, $role);
    }

    public function getUserStatistics() {
        return $this->dao->getUserStatistics();
    }

    private function validateUserData($data, $isNewUser = true) {
        // Username validation
        if (!isset($data['username']) || empty($data['username'])) {
            throw new \Exception("Username is required");
        }
        if (strlen($data['username']) < $this->minUsernameLength || 
            strlen($data['username']) > $this->maxUsernameLength) {
            throw new \Exception("Username must be between {$this->minUsernameLength} and {$this->maxUsernameLength} characters");
        }

        // Email validation
        if (!isset($data['email']) || empty($data['email'])) {
            throw new \Exception("Email is required");
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }

        // Password validation (only for new users or if password is being updated)
        if ($isNewUser || (isset($data['password']) && !empty($data['password']))) {
            if (!isset($data['password']) || empty($data['password'])) {
                throw new \Exception("Password is required");
            }
            if (strlen($data['password']) < $this->minPasswordLength) {
                throw new \Exception("Password must be at least {$this->minPasswordLength} characters");
            }
        }

        // Role validation (if provided)
        if (isset($data['role']) && !empty($data['role'])) {
            if (!in_array($data['role'], $this->validRoles)) {
                throw new \Exception("Invalid role. Must be one of: " . implode(', ', $this->validRoles));
            }
        }
    }

    private function checkDuplicateUser($data, $excludeId = null) {
        // Check for duplicate username
        $existingUser = $this->dao->getUserByUsername($data['username']);
        if ($existingUser && (!$excludeId || $existingUser['id'] != $excludeId)) {
            throw new \Exception("Username already exists");
        }

        // Check for duplicate email
        $existingUser = $this->dao->getUserByEmail($data['email']);
        if ($existingUser && (!$excludeId || $existingUser['id'] != $excludeId)) {
            throw new \Exception("Email already exists");
        }
    }

    public function getUserByEmail($email) {
        if (empty($email)) {
            throw new \Exception("Email is required");
        }
        return $this->dao->getUserByEmail($email);
    }

    public function getUserByUsername($username) {
        if (empty($username)) {
            throw new \Exception("Username is required");
        }
        return $this->dao->getUserByUsername($username);
    }
}
?>
