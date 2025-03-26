<?php
require_once __DIR__ . '/BaseDao.php'; // Correct path to BaseDao.php

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }
 
 
    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
 }
 ?>
 
 