<?php
require_once __DIR__ . '/../dao/ActivityDao.php';
require_once __DIR__ . '/BaseService.php';

class ActivityService extends BaseService {
    private $validDifficulties = ['easy', 'medium', 'hard'];
    private $minDuration = 5; // minutes
    private $maxDuration = 240; // minutes

    public function __construct() {
        parent::__construct(new ActivityDao());
    }

    public function getActivitiesByCategory($categoryId) {
        if (!is_numeric($categoryId)) {
            throw new Exception("Invalid category ID");
        }
        return $this->dao->getByCategory($categoryId);
    }

    public function getActivitiesByMood($moodId) {
        if (!is_numeric($moodId)) {
            throw new Exception("Invalid mood ID");
        }
        return $this->dao->getActivitiesByMood($moodId);
    }

    public function create($data) {
        $this->validateActivityData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateActivityData($data);
        return parent::update($id, $data);
    }

    private function validateActivityData($data) {
        // Required fields validation
        $requiredFields = ['title', 'description', 'category_id', 'difficulty', 'duration'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Difficulty validation
        if (!in_array(strtolower($data['difficulty']), $this->validDifficulties)) {
            throw new Exception("Invalid difficulty level. Must be one of: " . implode(', ', $this->validDifficulties));
        }

        // Duration validation
        if (!is_numeric($data['duration']) || 
            $data['duration'] < $this->minDuration || 
            $data['duration'] > $this->maxDuration) {
            throw new Exception("Duration must be between {$this->minDuration} and {$this->maxDuration} minutes");
        }

        // Category existence validation
        if (!is_numeric($data['category_id'])) {
            throw new Exception("Invalid category ID");
        }

        // Optional location validation
        if (isset($data['location']) && strlen($data['location']) > 255) {
            throw new Exception("Location must be less than 255 characters");
        }
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
}
?>
