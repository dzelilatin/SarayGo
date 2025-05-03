<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/RecommendationDao.php';

class RecommendationService extends BaseService {
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;

    public function __construct() {
        $dao = new RecommendationDao();
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

    public function get_by_category($category_id) {
        if (!is_numeric($category_id)) {
            throw new Exception("Invalid category ID");
        }
        return $this->dao->get_by_category($category_id);
    }

    public function search($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->search($query);
    }

    public function create($data) {
        $this->validateRecommendationData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateRecommendationData($data);
        return parent::update($id, $data);
    }

    private function validateRecommendationData($data) {
        // Required fields validation
        $requiredFields = ['mood_id', 'activity_id', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Mood ID validation
        if (!is_numeric($data['mood_id'])) {
            throw new Exception("Invalid mood ID");
        }

        // Activity ID validation
        if (!is_numeric($data['activity_id'])) {
            throw new Exception("Invalid activity ID");
        }

        // Check if mood exists
        if (!$this->dao->moodExists($data['mood_id'])) {
            throw new Exception("Mood does not exist");
        }

        // Check if activity exists
        if (!$this->dao->activityExists($data['activity_id'])) {
            throw new Exception("Activity does not exist");
        }
    }

    public function getPersonalizedRecommendations($user_id, $mood_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        if (!is_numeric($mood_id)) {
            throw new Exception("Invalid mood ID");
        }
        return $this->dao->getPersonalizedRecommendations($user_id, $mood_id);
    }

    public function getPopularRecommendations($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularRecommendations($limit);
    }

    public function getRecommendationStatistics() {
        return $this->dao->getRecommendationStatistics();
    }
}
?>
