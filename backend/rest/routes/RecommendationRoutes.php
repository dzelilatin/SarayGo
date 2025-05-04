<?php
/**
 * @OA\Get(
 *     path="/recommendations",
 *     tags={"recommendations"},
 *     summary="Get all recommendations",
 *     @OA\Parameter(
 *         name="mood_id",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all recommendations",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="mood_id", type="integer", example=1),
 *                 @OA\Property(property="activity_id", type="integer", example=1),
 *                 @OA\Property(property="description", type="string", example="Try this activity when feeling happy"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /recommendations', function() {
    $mood_id = Flight::request()->query['mood_id'] ?? null;
    $category_id = Flight::request()->query['category_id'] ?? null;
    $user_id = Flight::request()->query['user_id'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::recommendationService()->search($query));
    } else if ($mood_id) {
        Flight::json(Flight::recommendationService()->get_by_mood($mood_id));
    } else if ($category_id) {
        Flight::json(Flight::recommendationService()->get_by_category($category_id));
    } else if ($user_id) {
        Flight::json(Flight::recommendationService()->get_by_user($user_id));
    } else {
        Flight::json(Flight::recommendationService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/recommendations/{id}",
 *     tags={"recommendations"},
 *     summary="Get recommendation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recommendation details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recommendation not found"
 *     )
 * )
 */
Flight::route('GET /recommendations/@id', function($id) {
    $recommendation = Flight::recommendationService()->getById($id);
    if ($recommendation) {
        Flight::json($recommendation);
    } else {
        Flight::halt(404, 'Recommendation not found');
    }
});

/**
 * @OA\Post(
 *     path="/recommendations",
 *     tags={"recommendations"},
 *     summary="Create a new recommendation",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"mood_id", "activity_id", "description"},
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="description", type="string", example="Try this activity when feeling happy")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Recommendation created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /recommendations', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::recommendationService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/recommendations/{id}",
 *     tags={"recommendations"},
 *     summary="Update a recommendation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="description", type="string", example="Try this activity when feeling happy")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recommendation updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recommendation not found"
 *     )
 * )
 */
Flight::route('PUT /recommendations/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::recommendationService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Recommendation not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/recommendations/{id}",
 *     tags={"recommendations"},
 *     summary="Delete a recommendation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Recommendation deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recommendation not found"
 *     )
 * )
 */
Flight::route('DELETE /recommendations/@id', function($id) {
    try {
        $result = Flight::recommendationService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Recommendation not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get personalized recommendations
Flight::route('GET /recommendations/personalized', function() {
    $user_id = Flight::request()->query['user_id'] ?? null;
    $mood_id = Flight::request()->query['mood_id'] ?? null;
    
    if (!$user_id || !$mood_id) {
        Flight::halt(400, 'Both user_id and mood_id are required');
    }
    
    try {
        $recommendations = Flight::recommendationService()->getPersonalizedRecommendations($user_id, $mood_id);
        Flight::json($recommendations);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get popular recommendations
Flight::route('GET /recommendations/popular', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::recommendationService()->getPopularRecommendations($limit));
});

// Get recommendation statistics
Flight::route('GET /recommendations/statistics', function() {
    Flight::json(Flight::recommendationService()->getRecommendationStatistics());
});
?> 