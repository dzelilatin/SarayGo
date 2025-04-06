<?php
// Include necessary files
require_once __DIR__ . '/RecommendationDao.php';  // Assuming your DAO is RecommendationDao.php

// Create RecommendationDao instance
$recommendationDao = new RecommendationDao();

// Sample user ID and mood ID for testing
$user_id = 3;  // Replace with an actual user ID
$mood_id = 2;  // Replace with an actual mood ID from your moods table

// 1. Test creating a recommendation based on mood
echo "\nTesting createRecommendation Method\n";
try {
    $result = $recommendationDao->createRecommendation($user_id, $mood_id);
    if ($result) {
        echo "Recommendations successfully created for user ID: $user_id and mood ID: $mood_id\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 2. Test getting recommendations by mood ID
echo "\nTesting getRecommendationsByMoodId Method\n";
$recommendations = $recommendationDao->getRecommendationsByMoodId($mood_id);
if ($recommendations) {
    echo "Recommendations for mood ID $mood_id:\n";
    var_dump($recommendations);
} else {
    echo "No recommendations found for mood ID $mood_id\n";
}
?>
