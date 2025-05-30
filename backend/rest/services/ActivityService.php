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
        $result = $this->dao->createActivity(
            $data['activity_name'],
            $data['description'],
            $data['category_id'],
            $data['mood_id'],
            $data['location'] ?? null
        );
        
        if ($result) {
            $lastId = $this->dao->getLastInsertId();
            return $this->getById($lastId);
        }
        
        return false;
    }

    public function update($id, $data) {
        // First check if the activity exists
        $existingActivity = $this->getById($id);
        if (!$existingActivity) {
            throw new \Exception("Activity not found", 404);
        }

        $this->validateActivityData($data);
        $result = $this->dao->updateActivity(
            $id,
            $data['activity_name'],
            $data['description'],
            $data['category_id'],
            $data['mood_id'],
            $data['location'] ?? null
        );
        
        if ($result) {
            return $this->getById($id);
        }
        
        throw new \Exception("Failed to update activity", 500);
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

    public function searchActivities($query, $categoryId = null) {
        if (empty($query)) {
            throw new \Exception("Search query cannot be empty", 400);
        }
        $results = $this->dao->searchActivities($query, $categoryId);
        if (empty($results)) {
            throw new \Exception("No activities found", 404);
        }
        return $results;
    }

    public function getByLocation($location) {
        if (empty($location)) {
            throw new \Exception("Location parameter cannot be empty", 400);
        }
        return $this->dao->getActivitiesByLocation($location);
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
    }
}
?>
