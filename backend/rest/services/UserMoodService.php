<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/UserMoodDao.php';

class UserMoodService extends BaseService {
    private $maxDailyMoodEntries = 5;

    public function __construct() {
        $dao = new UserMoodDao();
        parent::__construct($dao);
    }

    public function get_by_user($user_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        return $this->dao->get_by_user($user_id);
    }

    public function get_by_mood($mood_id) {
        if (!is_numeric($mood_id)) {
            throw new Exception("Invalid mood ID");
        }
        return $this->dao->get_by_mood($mood_id);
    }

    public function get_current_mood($user_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        return $this->dao->get_current_mood($user_id);
    }

    public function create($data) {
        $this->validateUserMoodData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateUserMoodData($data);
        return parent::update($id, $data);
    }

    private function validateUserMoodData($data) {
        // Required fields validation
        $requiredFields = ['user_id', 'mood_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // User ID validation
        if (!is_numeric($data['user_id'])) {
            throw new Exception("Invalid user ID");
        }

        // Mood ID validation
        if (!is_numeric($data['mood_id'])) {
            throw new Exception("Invalid mood ID");
        }

        // Check if user exists
        if (!$this->dao->userExists($data['user_id'])) {
            throw new Exception("User does not exist");
        }

        // Check if mood exists
        if (!$this->dao->moodExists($data['mood_id'])) {
            throw new Exception("Mood does not exist");
        }

        // Check daily mood entry limit
        if ($this->dao->getDailyMoodCount($data['user_id']) >= $this->maxDailyMoodEntries) {
            throw new Exception("Maximum daily mood entries reached");
        }
    }

    public function getMoodHistory($user_id, $limit = 30) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getMoodHistory($user_id, $limit);
    }

    public function getMoodTrends($user_id, $days = 7) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        if (!is_numeric($days) || $days < 1) {
            throw new Exception("Invalid days value");
        }
        return $this->dao->getMoodTrends($user_id, $days);
    }

    public function getMoodStatistics($user_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        return $this->dao->getMoodStatistics($user_id);
    }

    public function searchUserMoods($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchUserMoods($query);
    }
}
?>
