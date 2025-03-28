<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/BaseService.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function registerUser($data) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if the email already exists
        if ($this->dao->getByEmail($data['email'])) {
            throw new Exception("Email already in use");
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        
        return $this->dao->insert($data);
    }

    public function loginUser($email, $password) {
        $user = $this->dao->getByEmail($email);
        if (!$user) {
            throw new Exception("User not found");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Invalid email or password");
        }

        // Remove password before returning the user
        unset($user['password']);
        return $user;
    }
}
?>
