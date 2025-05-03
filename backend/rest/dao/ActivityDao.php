<?php
require_once __DIR__ . '/BaseDao.php';

class ActivityDao extends BaseDao {

    public function __construct() {
        parent::__construct('activities'); // Set the table to 'activities'
    }

    // Create a new activity
    public function createActivity($activity_name, $description, $category_id, $mood_id, $location) {
        $data = [
            'activity_name' => $activity_name,
            'description' => $description,
            'category_id' => $category_id,
            'mood_id' => $mood_id,
            'location' => $location
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get activity by ID
    public function getActivityById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all activities by category
    public function getActivitiesByCategory($category_id) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE category_id = :category_id");
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all activities by mood
    public function getActivitiesByMood($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update activity information
    public function updateActivity($id, $activity_name, $description, $category_id, $mood_id, $location) {
        $data = [
            'activity_name' => $activity_name,
            'description' => $description,
            'category_id' => $category_id,
            'mood_id' => $mood_id,
            'location' => $location
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete an activity by ID
    public function deleteActivity($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
