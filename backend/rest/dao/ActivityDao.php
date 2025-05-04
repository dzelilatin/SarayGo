<?php
namespace Dzelitin\SarayGo\Dao;

use Dzelitin\SarayGo\Dao\BaseDao;

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
        $result = $this->getById($id);
        return $this->formatActivity($result);
    }

    // Get all activities by category
    public function getActivitiesByCategory($category_id) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE category_id = :category_id");
        $stmt->bindValue(':category_id', $category_id, \PDO::PARAM_INT);
        $stmt->execute();
        $activities = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $activities);
    }

    // Get all activities by mood
    public function getActivitiesByMood($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, \PDO::PARAM_INT);
        $stmt->execute();
        $activities = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map([$this, 'formatActivity'], $activities);
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

    private function formatActivity($activity) {
        if (!$activity) {
            return null;
        }
        
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
