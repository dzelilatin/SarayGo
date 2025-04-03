<?php
require_once __DIR__ . '/BaseDao.php';

class UserMoodDao extends BaseDao {

    public function __construct() {
        parent::__construct('user_moods'); // Set the table to 'user_moods'
    }

    // Create a new user mood record
    public function createUserMood($user_id, $mood_id, $selected_at) {
        $data = [
            'user_id' => $user_id,
            'mood_id' => $mood_id,
            'selected_at' => $selected_at
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a user mood record by ID
    public function getUserMoodById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all user moods
    public function getAllUserMoods() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Get all moods by user ID
    public function getUserMoodsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM user_moods WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all user moods for a specific mood ID
    public function getUserMoodsByMoodId($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM user_moods WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update a user mood record by ID
    public function updateUserMood($id, $user_id, $mood_id, $selected_at) {
        $data = [
            'user_id' => $user_id,
            'mood_id' => $mood_id,
            'selected_at' => $selected_at
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a user mood record by ID
    public function deleteUserMood($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
