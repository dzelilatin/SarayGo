<?php
require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao {

    public function __construct() {
        parent::__construct('reviews'); // Set the table to 'reviews'
    }

    // Create a new review
    public function createReview($user_id, $activity_id, $rating, $review_text) {
        $data = [
            'user_id' => $user_id,
            'activity_id' => $activity_id,
            'rating' => $rating,
            'review_text' => $review_text
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a review by ID
    public function getReviewById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all reviews
    public function getAllReviews() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Get all reviews by user ID
    public function getReviewsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all reviews by activity ID
    public function getReviewsByActivityId($activity_id) {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE activity_id = :activity_id");
        $stmt->bindValue(':activity_id', $activity_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update a review by ID
    public function updateReview($id, $user_id, $activity_id, $rating, $review_text) {
        $data = [
            'user_id' => $user_id,
            'activity_id' => $activity_id,
            'rating' => $rating,
            'review_text' => $review_text
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a review by ID
    public function deleteReview($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
