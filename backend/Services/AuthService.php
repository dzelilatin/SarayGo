<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../Dao/AuthDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService
{


    public function __construct()
    {
        $dao = new AuthDao();
        parent::__construct($dao);
    }

    public function getUserByEmail($email)
    {
        return $this->dao->getUserByEmail($email);
    }

    public function register(array $entity)
    {
        return $this->dao->register($entity);
    }

    public function login(array $entity)
    {
        return $this->dao->login($entity);
    }
}
