<?php
namespace Dzelitin\SarayGo\Dao;

use Dzelitin\SarayGo\Dao\BaseDao;

class ActivityDao extends BaseDao {

    public function __construct() {
        parent::__construct('activities'); // Set the table to 'activities'
    }

    // Create a new activity
    public function createActivity($name, $description, $categoryId, $moodId, $location = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO activities (activity_name, description, category_id, mood_id, location)
            VALUES (:name, :description, :category_id, :mood_id, :location)
        ");
        
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':mood_id', $moodId, \PDO::PARAM_INT);
        $stmt->bindValue(':location', $location);
        
        return $stmt->execute();
    }

    // Get activity by ID
    public function getActivityById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM activities WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $this->formatActivity($result) : null;
    }

    // Get all activities by category
    public function getActivitiesByCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT * FROM activities WHERE category_id = :category_id");
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $results);
    }

    // Get all activities by mood
    public function getActivitiesByMood($moodId) {
        $stmt = $this->conn->prepare("SELECT * FROM activities WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $moodId, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $results);
    }

    // Update activity information
    public function updateActivity($id, $name, $description, $categoryId, $moodId, $location = null) {
        $stmt = $this->conn->prepare("
            UPDATE activities 
            SET activity_name = :name,
                description = :description,
                category_id = :category_id,
                mood_id = :mood_id,
                location = :location
            WHERE id = :id
        ");
        
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':mood_id', $moodId, \PDO::PARAM_INT);
        $stmt->bindValue(':location', $location);
        
        return $stmt->execute();
    }

    // Delete an activity by ID
    public function deleteActivity($id) {
        $stmt = $this->conn->prepare("DELETE FROM activities WHERE id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    // Search activities by name or description
    public function searchActivities($query, $categoryId = null, $difficulty = null) {
        $sql = "SELECT * FROM activities WHERE 
                (activity_name LIKE :query OR description LIKE :query)";
        
        if ($categoryId) {
            $sql .= " AND category_id = :category_id";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        
        if ($categoryId) {
            $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $results);
    }

    public function getActivitiesByLocation($location) {
        $stmt = $this->conn->prepare("SELECT * FROM activities WHERE location LIKE :location");
        $stmt->bindValue(':location', '%' . $location . '%');
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $results);
    }

    private function formatActivity($activity) {
        return [
            'id' => $activity['id'],
            'activity_name' => $activity['activity_name'],
            'description' => $activity['description'],
            'category_id' => $activity['category_id'],
            'mood_id' => $activity['mood_id'],
            'location' => $activity['location']
        ];
    }
}
?>
