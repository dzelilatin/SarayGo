<?php

require_once 'BaseService.php';
require_once __DIR__ . '/../Dao/CartDao.php';


class CartService extends BaseService
{

    public function __construct()
    {
        $dao = new CartDao();

        parent::__construct($dao);
    }

    public function getCartByUserID($user_ID)
    {
        return $this->dao->getCartByUserID($user_ID);
    }

    public function deleteCartByUserID($user_ID)
    {
        return $this->dao->deleteCartByUserID($user_ID);
    }

    public function getPriceTotal($user_ID)
    {
        return $this->dao->getPriceTotal($user_ID);
    }

    public function bookOffer($offer_ID, $user_ID)
    {
        return $this->dao->bookOffer($offer_ID, $user_ID);
    }
}
