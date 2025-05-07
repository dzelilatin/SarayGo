<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
use Dzelitin\SarayGo\dao\ReviewDao;

class ReviewService extends BaseService {
    private $minReviewLength = 10;
    private $maxReviewLength = 1000;
    private $minRating = 1;
    private $maxRating = 5;

    public function __construct() {
        parent::__construct(new ReviewDao());
    }

    public function create($data) {
        $this->validateReviewData($data);
        return $this->dao->createReview(
            $data['user_id'],
            $data['activity_id'],
            $data['rating'],
            $data['review_text']
        );
    }

    public function update($id, $data) {
        $this->validateReviewData($data);
        return $this->dao->updateReview(
            $id,
            $data['user_id'],
            $data['activity_id'],
            $data['rating'],
            $data['review_text']
        );
    }

    public function getByUserId($userId) {
        if (!is_numeric($userId)) {
            throw new \Exception("Invalid user ID");
        }
        return $this->dao->getReviewsByUserId($userId);
    }

    public function getByActivityId($activityId) {
        if (!is_numeric($activityId)) {
            throw new \Exception("Invalid activity ID");
        }
        return $this->dao->getReviewsByActivityId($activityId);
    }

    public function getAllReviews() {
        return $this->dao->getAllReviews();
    }

    private function validateReviewData($data) {
        // Required fields validation
        $requiredFields = ['user_id', 'activity_id', 'rating', 'review_text'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // Rating validation
        if (!is_numeric($data['rating']) || 
            $data['rating'] < $this->minRating || 
            $data['rating'] > $this->maxRating) {
            throw new \Exception("Rating must be between {$this->minRating} and {$this->maxRating}");
        }

        // Review text validation
        if (strlen($data['review_text']) < $this->minReviewLength || 
            strlen($data['review_text']) > $this->maxReviewLength) {
            throw new \Exception("Review text must be between {$this->minReviewLength} and {$this->maxReviewLength} characters");
        }

        // User ID validation
        if (!is_numeric($data['user_id'])) {
            throw new \Exception("Invalid user ID");
        }

        // Activity ID validation
        if (!is_numeric($data['activity_id'])) {
            throw new \Exception("Invalid activity ID");
        }
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
