<?php

require_once __DIR__ . '/../Dao/AdminDao.php';

require_once 'BaseService.php';

class AdminService extends BaseService
{



    public function __construct()
    {
        $dao = new AdminDao();

        parent::__construct($dao);
    }


    public function getTotalSales()
    {
        return $this->dao->getTotalSales();
    }

    public function getSalesByCategory()
    {
        return $this->dao->getSalesByCategory();
    }

    public function getUsersByName($name)
    {
        return $this->dao->getUsersByName($name);
    }

    public function getUserByID($id)
    {
        return $this->dao->getUserByID($id);
    }

    public function getUserOrderHistory($user_ID)
    {
        return $this->dao->getUserOrderHistory($user_ID);
    }

    public function getUserCart($user_ID)
    {
        return $this->dao->getUserCart($user_ID);
    }
}
