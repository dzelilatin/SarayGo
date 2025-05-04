<?php
namespace Dzelitin\SarayGo\Dao;
require_once __DIR__ . '/BaseDao.php';
use Dzelitin\SarayGo\Dao\BaseDao;

class RecommendationDao extends BaseDao {

    public function __construct() {
        parent::__construct('recommendations');
    }

    // Create a new recommendation based on user mood
    public function createRecommendation($user_id, $mood_id) {
        echo "Creating recommendation for user ID: $user_id and mood ID: $mood_id\n";  // Debugging
    
        // Fetch the mood by ID
        $mood = $this->getMoodById($mood_id);
        if (!$mood) {
            throw new Exception("Invalid mood ID: $mood_id.");
        }

        // Fetch activities associated with this mood from the activities table
        $activities = $this->getActivitiesByMood($mood_id);

        if (!$activities) {
            throw new Exception("No activities found for mood ID: $mood_id.");
        }

        // Create recommendations based on the mood and activities
        foreach ($activities as $activity) {
            $data = [
                'user_id' => $user_id,
                'activity_id' => $activity['id'],
                'mood_id' => $mood_id,
                'recommendation_reason' => "Perfect activity for your mood: {$activity['activity_name']}"
            ];
            $this->insert($data);  // Insert each recommendation into the database
        }
        return true;  // Successfully created recommendations
    }

    // Helper function to get activities associated with a mood
    private function getActivitiesByMood($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM activities WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Debugging: Check if activities are being returned
        $activities = $stmt->fetchAll();
    
        if (!$activities) {
            echo "No activities found for mood_id: $mood_id\n"; // Debugging output
        }
    
        return $activities;
    }

    // Get mood by ID
    private function getMoodById($mood_id) {
        echo "Fetching mood for ID: $mood_id\n";  // Debugging
    
        $stmt = $this->connection->prepare("SELECT * FROM moods WHERE id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
        $mood = $stmt->fetch();
    
        if (!$mood) {
            echo "Mood not found for ID: $mood_id\n";  // Debugging
        }
    
        return $mood;
    }

    // Get all recommendations by mood ID
    public function getRecommendationsByMoodId($mood_id) {
        $stmt = $this->connection->prepare("SELECT * FROM recommendations WHERE mood_id = :mood_id");
        $stmt->bindValue(':mood_id', $mood_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
