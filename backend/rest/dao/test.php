<?php

require_once __DIR__ . '/ReviewDao.php';

try {
    // Create an instance of ReviewDao
    $reviewDao = new ReviewDao();

    // Test: Create a new review
    echo "Testing createReview function...\n";
    $user_id = 1; // Assuming user with ID 1 exists
    $activity_id = 5; // Assuming activity with ID 2 exists
    $rating = 4; // Rating between 1 to 5
    $review_text = "This activity was really fun and relaxing!";
    $newReview = $reviewDao->createReview($user_id, $activity_id, $rating, $review_text);
    echo "New review created with ID: $newReview\n";
/*
    // Test: Get review by ID
    echo "Testing getReviewById function...\n";
    $reviewId = 1; // Assuming the review with ID 1 exists
    $review = $reviewDao->getReviewById($reviewId);
    echo "Review fetched by ID: ";
    print_r($review);

    // Test: Get all reviews
    echo "Testing getAllReviews function...\n";
    $allReviews = $reviewDao->getAllReviews();
    echo "All reviews: ";
    print_r($allReviews);

    // Test: Get reviews by user ID
    echo "Testing getReviewsByUserId function...\n";
    $userReviews = $reviewDao->getReviewsByUserId($user_id);
    echo "Reviews fetched by user ID: ";
    print_r($userReviews);

    // Test: Get reviews by activity ID
    echo "Testing getReviewsByActivityId function...\n";
    $activityReviews = $reviewDao->getReviewsByActivityId($activity_id);
    echo "Reviews fetched by activity ID: ";
    print_r($activityReviews);

    // Test: Update review
    echo "Testing updateReview function...\n";
    $updatedReview = $reviewDao->updateReview($reviewId, $user_id, $activity_id, 5, "Updated review text!");
    echo "Review updated with ID: $updatedReview\n";

    // Test: Delete review by ID
    echo "Testing deleteReview function...\n";
    $deletedReview = $reviewDao->deleteReview($reviewId);
    echo "Review with ID $reviewId deleted: $deletedReview\n";
*/
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
