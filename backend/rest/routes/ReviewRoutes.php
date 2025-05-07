<?php
/**
 * @OA\Get(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews"
 *     )
 * )
 */
Flight::route('GET /reviews', function() {
    Flight::json(Flight::reviewService()->getAll());
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Get review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function($id) {
    $review = Flight::reviewService()->getById($id);
    if ($review) {
        Flight::json($review);
    } else {
        Flight::halt(404, 'Review not found');
    }
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Create a new review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "activity_id", "rating", "comment"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *             @OA\Property(property="comment", type="string", example="Great experience!"),
 *             @OA\Property(property="title", type="string", example="Amazing Activity"),
 *             @OA\Property(property="images", type="array", @OA\Items(type="string"), example=["image1.jpg", "image2.jpg"])
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Review created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /reviews', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Update review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *             @OA\Property(property="comment", type="string", example="Great experience!"),
 *             @OA\Property(property="title", type="string", example="Amazing Activity"),
 *             @OA\Property(property="images", type="array", @OA\Items(type="string"), example=["image1.jpg", "image2.jpg"])
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reviewService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Delete review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('DELETE /reviews/@id', function($id) {
    Flight::json(Flight::reviewService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/reviews/activity/{activity_id}",
 *     tags={"reviews"},
 *     summary="Get reviews by activity",
 *     @OA\Parameter(
 *         name="activity_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews for the activity"
 *     )
 * )
 */
Flight::route('GET /reviews/activity/@activity_id', function($activity_id) {
    Flight::json(Flight::reviewService()->getByActivity($activity_id));
});

/**
 * @OA\Get(
 *     path="/reviews/user/{user_id}",
 *     tags={"reviews"},
 *     summary="Get reviews by user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of reviews by the user"
 *     )
 * )
 */
Flight::route('GET /reviews/user/@user_id', function($user_id) {
    Flight::json(Flight::reviewService()->getByUser($user_id));
});

/**
 * @OA\Get(
 *     path="/reviews/search",
 *     tags={"reviews"},
 *     summary="Search reviews",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="great")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching reviews"
 *     )
 * )
 */
Flight::route('GET /reviews/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::reviewService()->search($query));
});

// Get recent reviews
Flight::route('GET /reviews/recent', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::reviewService()->getRecentReviews($limit));
});

// Get top rated activities
Flight::route('GET /reviews/top-rated', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::reviewService()->getTopRatedActivities($limit));
});

// Get average rating for a recommendation
Flight::route('GET /reviews/average-rating/@recommendation_id', function($recommendation_id) {
    try {
        $average = Flight::reviewService()->get_average_rating($recommendation_id);
        Flight::json(['average_rating' => $average]);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get review statistics
Flight::route('GET /reviews/statistics', function() {
    Flight::json(Flight::reviewService()->getReviewStatistics());
});
?> 