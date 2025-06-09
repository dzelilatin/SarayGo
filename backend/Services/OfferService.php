<?php

require_once 'BaseService.php';
require_once(__DIR__ . '/../dao/OfferDao.php');


class OfferService extends BaseService
{

    public function __construct()
    {
        $dao = new OfferDao();

        parent::__construct($dao);
    }

    public function getAllOffers()
    {
        return $this->dao->getAllOffers();
    }

    public function getByCategoryName($category_name)
    {
        return $this->dao->getByCategoryName($category_name);
    }

    public function getByName($name)
    {
        return $this->dao->getByName($name);
    }

    public function getOfferById($id)
    {
        return $this->dao->getOfferById($id);
    }
}
