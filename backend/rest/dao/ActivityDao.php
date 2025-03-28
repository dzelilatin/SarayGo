<?php
require_once __DIR__ . '/BaseDao.php';

class ActivityDao extends BaseDao {
    public function __construct() {
        parent::__construct("activities");
    }

    public function getByCategory($categoryId) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
