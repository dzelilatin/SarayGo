<?php
require_once __DIR__ . '/BaseDao.php';

class RecommendationDao extends BaseDao {

    public function __construct() {
        parent::__construct('recommendations'); // Set the table to 'recommendations'
    }

    // Create a new recommendation
    public function createRecommendation($user_id, $activity_id, $mood_id, $recommendation_reason) {
        $data = [
            'user_id' => $user_id,
            'activity_id' => $activity_id,
            'mood_id' => $mood_id,
            'recommendation_reason' => $recommendation_reason
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a recommendation by ID
    public function getRecommendationById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all recommendations
    public function getAllRecommendations() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Get all recommendations by user ID
    public function getRecommendationsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM recommendations WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all recommendations by activity ID
    public function getRecommendationsByActivityId($activity_id) {
        $stmt = $this->connection->prepare("SELECT * FROM recommendations WHERE activity_id = :activity_id");
        $stmt->bindValue(':activity_id', $activity_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all recommendations by mood ID
    public function getRecommendationsByMoodId($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM recommendations WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update a recommendation by ID
    public function updateRecommendation($id, $user_id, $activity_id, $mood_id, $recommendation_reason) {
        $data = [
            'user_id' => $user_id,
            'activity_id' => $activity_id,
            'mood_id' => $mood_id,
            'recommendation_reason' => $recommendation_reason
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a recommendation by ID
    public function deleteRecommendation($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
