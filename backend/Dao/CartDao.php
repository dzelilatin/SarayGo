<?php

require_once 'BaseDao.php';

class CartDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct('carts');
    }

    public function getCartByUserID($user_ID)
    {
        $sql = 'SELECT cart_ID FROM carts WHERE user_id = :user_ID';

        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':user_ID', $user_ID);

        $statement->execute();

        return $statement->fetch();
    }

    public function deleteCartByUserID($user_ID)
    {
        $sql = 'DELETE FROM carts WHERE user_id = :user_ID';
        $statement = $this->connection->prepare($sql);

        $statement->bindValue(':user_ID', $user_ID);

        $statement->execute();

        if (!$statement) {
            return ['Success: ' => "False", "Message:" => "Error deleting cart!"];
        }


        return ['Success: ' => "True", "Message:" => "Deleted cart succ3essfully."];
    }

    public function getPriceTotal($user_ID)
    {
        $totalPrice = "SELECT price_total FROM carts WHERE user_id = :user_ID";
        $statement = $this->connection->prepare($totalPrice);
        $statement->bindValue("user_ID", $user_ID);
        $statement->execute();

        $total = $statement->fetch();

        $r = $total["price_total"];
        return $r;
    }

    public function updateCartOffer($cart_ID, $offer_id)
    {
        $sql = 'UPDATE carts
                   SET offer_id   = :offer_id
                 WHERE cart_ID    = :cart_ID';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':offer_id', $offer_id);
        $stmt->bindValue(':cart_ID',   $cart_ID);
        $stmt->execute();

        return [
            'success' => true,
            'message' => "Cart {$cart_ID} updated to offer {$offer_id}"
        ];
    }
}
