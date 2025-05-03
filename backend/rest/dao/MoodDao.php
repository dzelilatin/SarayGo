<?php
require_once __DIR__ . '/BaseDao.php';

class MoodDao extends BaseDao {

    public function __construct() {
        parent::__construct('moods'); // Set the table to 'moods'
    }

    // Create a new mood
    public function createMood($mood_name) {
        $data = [
            'mood_name' => $mood_name
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a mood by ID
    public function getMoodById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all moods
    public function getAllMoods() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Update a mood by ID
    public function updateMood($id, $mood_name) {
        $data = [
            'mood_name' => $mood_name
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a mood by ID
    public function deleteMood($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
