<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MoodDao.php';
use Dzelitin\SarayGo\dao\MoodDao;

class MoodService extends BaseService {
    private $minNameLength = 2;
    private $maxNameLength = 100;
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;
    private $validIcons = ['happy', 'sad', 'angry', 'excited', 'calm', 'anxious', 'tired', 'energetic'];
    private $validTypes = ['positive', 'negative', 'neutral'];

    public function __construct() {
        parent::__construct(new MoodDao());
    }

    public function get_by_name($name) {
        if (empty($name)) {
            throw new Exception("Mood name cannot be empty");
        }
        return $this->dao->get_by_name($name);
    }

    public function getByType($type) {
        if (empty($type)) {
            throw new Exception("Mood type cannot be empty");
        }
        if (!in_array(strtolower($type), $this->validTypes)) {
            throw new Exception("Invalid mood type. Must be one of: " . implode(', ', $this->validTypes));
        }
        return $this->dao->getByType($type);
    }

    public function create($data) {
        $this->validateMoodData($data);
        return $this->dao->createMood($data['mood_name']);
    }

    public function update($id, $data) {
        $this->validateMoodData($data);
        return $this->dao->updateMood($id, $data['mood_name']);
    }

    public function getAllMoods() {
        return $this->dao->getAllMoods();
    }

    private function validateMoodData($data) {
        // Required fields validation
        if (!isset($data['mood_name']) || empty($data['mood_name'])) {
            throw new \Exception("Mood name is required");
        }

        // Name validation
        if (strlen($data['mood_name']) < $this->minNameLength || 
            strlen($data['mood_name']) > $this->maxNameLength) {
            throw new \Exception("Mood name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Type validation
        if (!in_array(strtolower($data['type']), $this->validTypes)) {
            throw new Exception("Invalid mood type. Must be one of: " . implode(', ', $this->validTypes));
        }

        // Description validation
        if (strlen($data['description']) > 500) {
            throw new Exception("Description must be less than 500 characters");
        }

        // Icon validation
        if (!in_array($data['icon'], $this->validIcons)) {
            throw new Exception("Invalid icon. Must be one of: " . implode(', ', $this->validIcons));
        }

        // Check for duplicate mood name
        if ($this->dao->getByName($data['mood_name'])) {
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
