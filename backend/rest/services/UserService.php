<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    private $minUsernameLength = 3;
    private $maxUsernameLength = 20;
    private $minPasswordLength = 8;
    private $maxPasswordLength = 50;
    private $validRoles = ['user', 'admin'];

    public function __construct() {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function register($data) {
        $this->validateRegistrationData($data);
        return $this->dao->register($data);
    }

    public function login($data) {
        $this->validateLoginData($data);
        return $this->dao->login($data);
    }

    public function create($data) {
        $this->validateUserData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateUserData($data);
        return parent::update($id, $data);
    }

    private function validateRegistrationData($data) {
        // Required fields validation
        $requiredFields = ['username', 'email', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Username validation
        if (strlen($data['username']) < $this->minUsernameLength || 
            strlen($data['username']) > $this->maxUsernameLength) {
            throw new Exception("Username must be between {$this->minUsernameLength} and {$this->maxUsernameLength} characters");
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Password validation
        if (strlen($data['password']) < $this->minPasswordLength || 
            strlen($data['password']) > $this->maxPasswordLength) {
            throw new Exception("Password must be between {$this->minPasswordLength} and {$this->maxPasswordLength} characters");
        }

        // Check if username exists
        if ($this->dao->usernameExists($data['username'])) {
            throw new Exception("Username already exists");
        }

        // Check if email exists
        if ($this->dao->emailExists($data['email'])) {
            throw new Exception("Email already exists");
        }
    }

    private function validateLoginData($data) {
        // Required fields validation
        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
    }

    private function validateUserData($data) {
        // Required fields validation
        $requiredFields = ['username', 'email', 'role'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Username validation
        if (strlen($data['username']) < $this->minUsernameLength || 
            strlen($data['username']) > $this->maxUsernameLength) {
            throw new Exception("Username must be between {$this->minUsernameLength} and {$this->maxUsernameLength} characters");
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Role validation
        if (!in_array($data['role'], $this->validRoles)) {
            throw new Exception("Invalid role. Must be one of: " . implode(', ', $this->validRoles));
        }

        // Check if username exists (excluding current user)
        if (isset($data['id']) && $this->dao->usernameExists($data['username'], $data['id'])) {
            throw new Exception("Username already exists");
        }

        // Check if email exists (excluding current user)
        if (isset($data['id']) && $this->dao->emailExists($data['email'], $data['id'])) {
            throw new Exception("Email already exists");
        }
    }

    public function getByUsername($username) {
        if (empty($username)) {
            throw new Exception("Username cannot be empty");
        }
        return $this->dao->getByUsername($username);
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
}
?>
