<?php
/**
 * @OA\Get(
 *     path="/api/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Parameter(
 *         name="activity_id",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="activity_id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *                 @OA\Property(property="comment", type="string", example="Great activity!"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/reviews', function() {
    $user_id = Flight::request()->query['user_id'] ?? null;
    $activity_id = Flight::request()->query['activity_id'] ?? null;
    $recommendation_id = Flight::request()->query['recommendation_id'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::reviewService()->searchReviews($query));
    } else if ($user_id) {
        Flight::json(Flight::reviewService()->get_by_user($user_id));
    } else if ($activity_id) {
        Flight::json(Flight::reviewService()->get_by_activity($activity_id));
    } else if ($recommendation_id) {
        Flight::json(Flight::reviewService()->get_by_recommendation($recommendation_id));
    } else {
        Flight::json(Flight::reviewService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/api/reviews/{id}",
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
Flight::route('GET /api/reviews/@id', function($id) {
    $review = Flight::reviewService()->getById($id);
    if ($review) {
        Flight::json($review);
    } else {
        Flight::halt(404, 'Review not found');
    }
});

/**
 * @OA\Post(
 *     path="/api/reviews",
 *     tags={"reviews"},
 *     summary="Create a new review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"activity_id", "user_id", "rating", "comment"},
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *             @OA\Property(property="comment", type="string", example="Great activity!")
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
Flight::route('POST /api/reviews', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::reviewService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/api/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Update a review",
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
 *             @OA\Property(property="comment", type="string", example="Great activity!")
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
Flight::route('PUT /api/reviews/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::reviewService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Review not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/api/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Delete a review",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Review deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('DELETE /api/reviews/@id', function($id) {
    try {
        $result = Flight::reviewService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Review not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get recent reviews
Flight::route('GET /api/reviews/recent', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::reviewService()->getRecentReviews($limit));
});

// Get top rated activities
Flight::route('GET /api/reviews/top-rated', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::reviewService()->getTopRatedActivities($limit));
});

// Get average rating for a recommendation
Flight::route('GET /api/reviews/average-rating/@recommendation_id', function($recommendation_id) {
    try {
        $average = Flight::reviewService()->get_average_rating($recommendation_id);
        Flight::json(['average_rating' => $average]);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get review statistics
Flight::route('GET /api/reviews/statistics', function() {
    Flight::json(Flight::reviewService()->getReviewStatistics());
});
?> 