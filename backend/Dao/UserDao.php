<?php


class UserDao extends BaseDao
{

    protected $table = 'users';


    public function __construct($table = 'users')
    {
        parent::__construct($table);
    }


    public function getOrders($id)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :id";

        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':id', $id);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function getUserCart($user_ID)
    {



        $sql = "SELECT *

                 FROM carts c

              

                 WHERE c.user_ID = :user_ID 

                ";


        $statement = $this->connection->prepare($sql);

        $statement->bindValue("user_ID", $user_ID);

        $statement->execute();

        return $statement->fetch();
    }



    public function getUserOrders($user_ID)
    {
        $sql = "SELECT * FROM cart_items ci JOIN saraygo.carts c ON ci.cart_ID = c.cart_ID join products p on ci.product_ID = p.product_id WHERE user_id = :user_id";



        $statement = $this->connection->prepare($sql);

        $statement->bindValue("user_id", $user_ID);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function createCart($user_ID)
    {
        $validUser = $this->getById($user_ID);

        if ($validUser) {
            $sql = "INSERT INTO carts (user_ID) VALUES (:user_ID)";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue("user_ID", $user_ID);

            $statement->execute();

            if ($statement) {
                return ['success' => true, 'Message' => 'Created a cart successfully!'];
            }

            return ['success' => false, 'Message ' => 'User CANNOT have more than one cart!'];
        }



        return ['success' => false, 'Message ' => 'Invalid User ID!'];
    }




    public function checkOut($user_ID)
    {
        $validUser = $this->getById($user_ID);

        if (!$validUser) return ['Success' => 'False', 'Message ' => 'User is NOT valid!'];

        try {

            $statement = "UPDATE carts c SET c.status = 'ordered' WHERE user_id = :user_ID";
            $statement = $this->connection->prepare($statement);
            $statement->bindValue(":user_ID", $user_ID);
            $statement->execute();

            if ($statement) {
                return ["Success: " => 'True', "Message: " => "Ordered item successfully!"];
            }
        } catch (PDOException $e) {
            return ['Success' => 'False', 'Message: ' => $e->getMessage()];
        }
    }
}
