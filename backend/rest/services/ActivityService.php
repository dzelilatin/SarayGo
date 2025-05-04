<?php
namespace Dzelitin\SarayGo\Services;

use Dzelitin\SarayGo\Dao\ActivityDao;

class ActivityService extends BaseService {
    private $minNameLength = 3;
    private $maxNameLength = 100;
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 1000;

    public function __construct() {
        parent::__construct(new ActivityDao());
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getActivityById($id);
    }

    public function create($data) {
        $this->validateActivityData($data);
        return $this->dao->createActivity(
            $data['activity_name'],
            $data['description'],
            $data['category_id'],
            $data['mood_id'],
            $data['location'] ?? null
        );
    }

    public function update($id, $data) {
        $this->validateActivityData($data);
        return $this->dao->updateActivity(
            $id,
            $data['activity_name'],
            $data['description'],
            $data['category_id'],
            $data['mood_id'],
            $data['location'] ?? null
        );
    }

    public function delete($id) {
        return $this->dao->deleteActivity($id);
    }

    public function getByCategory($categoryId) {
        if (!is_numeric($categoryId)) {
            throw new \Exception("Invalid category ID");
        }
        return $this->dao->getActivitiesByCategory($categoryId);
    }

    public function getByMood($moodId) {
        if (!is_numeric($moodId)) {
            throw new \Exception("Invalid mood ID");
        }
        return $this->dao->getActivitiesByMood($moodId);
    }

    public function getPopularActivities($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularActivities($limit);
    }

    public function searchActivities($query, $categoryId = null, $difficulty = null) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        if ($difficulty && !in_array(strtolower($difficulty), $this->validDifficulties)) {
            throw new Exception("Invalid difficulty level");
        }
        return $this->dao->searchActivities($query, $categoryId, $difficulty);
    }

    private function validateActivityData($data) {
        // Required fields validation
        $requiredFields = ['activity_name', 'description', 'category_id', 'mood_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // Activity name validation
        if (strlen($data['activity_name']) < $this->minNameLength || 
            strlen($data['activity_name']) > $this->maxNameLength) {
            throw new \Exception("Activity name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new \Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Category ID validation
        if (!is_numeric($data['category_id'])) {
            throw new \Exception("Invalid category ID");
        }

        // Mood ID validation
        if (!is_numeric($data['mood_id'])) {
            throw new \Exception("Invalid mood ID");
        }

        // Location validation (optional)
        if (isset($data['location']) && !empty($data['location'])) {
            if (strlen($data['location']) > 255) {
                throw new \Exception("Location must not exceed 255 characters");
            }
        }
    }
}
?>
