<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';

class ReviewService extends BaseService {
    private $minRating = 1;
    private $maxRating = 5;
    private $minCommentLength = 10;
    private $maxCommentLength = 500;

    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function get_by_user($user_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        return $this->dao->get_by_user($user_id);
    }

    public function get_by_recommendation($recommendation_id) {
        if (!is_numeric($recommendation_id)) {
            throw new Exception("Invalid recommendation ID");
        }
        return $this->dao->get_by_recommendation($recommendation_id);
    }

    public function get_average_rating($recommendation_id) {
        if (!is_numeric($recommendation_id)) {
            throw new Exception("Invalid recommendation ID");
        }
        return $this->dao->get_average_rating($recommendation_id);
    }

    public function create($data) {
        $this->validateReviewData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateReviewData($data);
        return parent::update($id, $data);
    }

    private function validateReviewData($data) {
        // Required fields validation
        $requiredFields = ['user_id', 'activity_id', 'rating', 'comment'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Rating validation
        if (!is_numeric($data['rating']) || 
            $data['rating'] < $this->minRating || 
            $data['rating'] > $this->maxRating) {
            throw new Exception("Rating must be between {$this->minRating} and {$this->maxRating}");
        }

        // Comment validation
        if (strlen($data['comment']) < $this->minCommentLength || 
            strlen($data['comment']) > $this->maxCommentLength) {
            throw new Exception("Comment must be between {$this->minCommentLength} and {$this->maxCommentLength} characters");
        }

        // User ID validation
        if (!is_numeric($data['user_id'])) {
            throw new Exception("Invalid user ID");
        }

        // Activity ID validation
        if (!is_numeric($data['activity_id'])) {
            throw new Exception("Invalid activity ID");
        }

        // Check if user has already reviewed this activity
        if ($this->dao->hasUserReviewedActivity($data['user_id'], $data['activity_id'])) {
            throw new Exception("User has already reviewed this activity");
        }
    }

    public function getRecentReviews($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getRecentReviews($limit);
    }

    public function getTopRatedActivities($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getTopRatedActivities($limit);
    }

    public function getReviewStatistics() {
        return $this->dao->getReviewStatistics();
    }

    public function searchReviews($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchReviews($query);
    }
}
?>
