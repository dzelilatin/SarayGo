<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/MoodDao.php';

class MoodService extends BaseService {
    private $minNameLength = 2;
    private $maxNameLength = 50;
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;
    private $validIcons = ['happy', 'sad', 'angry', 'excited', 'calm', 'anxious', 'tired', 'energetic'];

    public function __construct() {
        $dao = new MoodDao();
        parent::__construct($dao);
    }

    public function get_by_name($name) {
        if (empty($name)) {
            throw new Exception("Mood name cannot be empty");
        }
        return $this->dao->get_by_name($name);
    }

    public function create($data) {
        $this->validateMoodData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateMoodData($data);
        return parent::update($id, $data);
    }

    private function validateMoodData($data) {
        // Required fields validation
        $requiredFields = ['name', 'description', 'icon'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Name validation
        if (strlen($data['name']) < $this->minNameLength || 
            strlen($data['name']) > $this->maxNameLength) {
            throw new Exception("Name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Icon validation
        if (!in_array($data['icon'], $this->validIcons)) {
            throw new Exception("Invalid icon. Must be one of: " . implode(', ', $this->validIcons));
        }

        // Check for duplicate mood name
        if ($this->dao->getByName($data['name'])) {
            throw new Exception("Mood name already exists");
        }
    }

    public function getPopularMoods($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularMoods($limit);
    }

    public function getMoodsByActivity($activityId) {
        if (!is_numeric($activityId)) {
            throw new Exception("Invalid activity ID");
        }
        return $this->dao->getMoodsByActivity($activityId);
    }

    public function searchMoods($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchMoods($query);
    }

    public function getMoodStatistics() {
        return $this->dao->getMoodStatistics();
    }
}
?>
