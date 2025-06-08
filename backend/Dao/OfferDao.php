<?php

require_once 'BaseDao.php';

class OfferDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct('offers');
    }

    public function getAllOffers()
    {
        $sql = "SELECT * FROM offers";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getByCategoryName($categoryName)
    {
        $sql = "SELECT * FROM offers WHERE offer_type = :categoryName";

        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':categoryName', $categoryName);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getByName($name)
    {
        $sql = 'SELECT * FROM offers WHERE offer_name LIKE :offer_name';
        $statement = $this->connection->prepare($sql);

        $likeTitle = '%' . $name . '%';

        $statement->bindParam(':offer_name', $likeTitle, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function getOfferById($id)
    {
        $sql = 'SELECT * FROM offers WHERE offer_id = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}
