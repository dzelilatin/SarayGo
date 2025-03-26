<?php
require_once __DIR__ . '/../dao/UserDao.php'; // Relative path to UserDao.php
require_once __DIR__ . '/BaseService.php';    // Relative path to BaseService.php

class UserService extends BaseService {
    public function __construct() {
    parent::__construct(new UserDao());
}

    public function registerUser($data) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->dao->insert($data);
    }

    public function loginUser($email, $password) {
        if (!method_exists($this->dao, 'getByEmail')) {
            throw new Exception("Method getByEmail() does not exist in UserDao");
        }

        $user = $this->dao->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        throw new Exception("Invalid credentials");
    }
}
?>
