<?php

require_once 'Database.php';

class BaseDao
{
    protected $connection;
    protected $table_name;

    protected $idColumn;

    public function __construct($table_name)
    {
        $this->connection = Database::connect();
        $this->table_name = $table_name;
    }


    protected function query($query, $params)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name . " WHERE " . self::getTableName() . " = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function add(array $entity)
    {

        $query = "INSERT INTO " . $this->table_name . " (";
        foreach ($entity as $column => $value) {
            $query .= $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":" . $column . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt = $this->connection->prepare($query);
        $stmt->execute($entity);


        $entity['id'] = $this->connection->lastInsertId();
        return $entity;
    }


    public function update(array $data, int $id): bool
    {
        if (empty($data)) {
            throw new \Exception('Update data array cannot be empty.');
        }

        $setParts = [];
        $params = [];

        foreach ($data as $column => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
                error_log("Skipping invalid column name in update data: " . $column);
                continue;
            }

            $placeholder = ":" . $column;
            $setParts[] = "`{$column}` = {$placeholder}";
            $params[$placeholder] = $value;
        }

        if (empty($setParts)) {
            throw new \Exception('No valid columns provided for update.');
        }

        $setClause = implode(', ', $setParts);

        $sql = "UPDATE `{$this->table_name}` SET {$setClause} WHERE " . self::getTableName() . " = :id;";

        $params[':id'] = $id;

        try {
            $stmt = $this->connection->prepare($sql);
            $success = $stmt->execute($params);

            if ($success) {
                $result = ['Message' => 'Update successful', 'ID' => $id];

                return true;
            }

            return $success;
        } catch (\PDOException $e) {
            error_log("Database update error in {$this->table_name} (ID: {$id}): " . $e->getMessage());
            return false;
        }
    }


    public function delete(int $id)
    {
        $stmt = $this->connection->prepare("DELETE FROM " . $this->table_name . " WHERE " . self::getTableName() .  " = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function getTableName()
    {
        $name = $this->table_name;


        if ($this->table_name == "users") return 'user_id';
        if ($this->table_name == "carts") return 'cart_id';
        if ($this->table_name == "cart_items") return 'cart_item_id';
        if ($this->table_name == "order_details") return 'orderdetail_id';
        if ($this->table_name == "orders") return 'order_id';
        if ($this->table_name == "users") return 'user_id';
        if ($this->table_name == "categories") return 'category_id';


        return "ERROR::Error - Invalid Table Name!";
    }


    public function getByIDD($id)
    {

        if (!$id) throw new Exception("ERROR::No_Data");

        try {
            $stmt = $this->connection->prepare("SELECT * FROM " . $this->table_name . " WHERE " . $this->idColumn . " = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }


}
