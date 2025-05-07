<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/RecommendationDao.php';
use Dzelitin\SarayGo\dao\RecommendationDao;

class RecommendationService extends BaseService {
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;
    private $maxReasonLength = 500;

    public function __construct() {
        parent::__construct(new RecommendationDao());
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

    public function createRecommendation($userId, $moodId) {
        $this->validateIds($userId, $moodId);
        return $this->dao->createRecommendation($userId, $moodId);
    }

    public function getByMoodId($moodId) {
        if (!is_numeric($moodId)) {
            throw new \Exception("Invalid mood ID");
        }
        return $this->dao->getRecommendationsByMoodId($moodId);
    }

    public function create($data) {
        $this->validateRecommendationData($data);
        return $this->dao->createRecommendation($data['user_id'], $data['mood_id']);
    }

    public function update($id, $data) {
        $this->validateRecommendationData($data);
        return parent::update($id, $data);
    }

    private function validateRecommendationData($data) {
        // Required fields validation
        $requiredFields = ['user_id', 'mood_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // ID validations
        $this->validateIds($data['user_id'], $data['mood_id']);

        // Recommendation reason validation (if provided)
        if (isset($data['recommendation_reason']) && strlen($data['recommendation_reason']) > $this->maxReasonLength) {
            throw new \Exception("Recommendation reason must not exceed {$this->maxReasonLength} characters");
        }

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Check if mood exists
        if (!$this->dao->moodExists($data['mood_id'])) {
            throw new Exception("Mood does not exist");
        }
    }

    private function validateIds($userId, $moodId) {
        if (!is_numeric($userId)) {
            throw new \Exception("Invalid user ID");
        }
        if (!is_numeric($moodId)) {
            throw new \Exception("Invalid mood ID");
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
