<?php


require_once 'BaseService.php';
require_once __DIR__ . '/../Dao/UserDao.php';


class UserService extends BaseService
{


    public function __construct()
    {
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function getUserCart($user_ID)
    {
        return $this->dao->getUserCart($user_ID);
    }

    public function getUserOrders($user_ID)
    {
        return $this->dao->getUserOrders($user_ID);
    }

    public function createCart($user_ID)
    {
        return $this->dao->createCart($user_ID);
    }

    public function checkOut($user_ID)
    {
        return $this->dao->checkOut($user_ID);
    }
}
