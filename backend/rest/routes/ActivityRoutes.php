<?php
/**
 * @OA\Get(
 *     path="/api/activities",
 *     tags={"activities"},
 *     summary="Get all activities",
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all activities",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Morning Yoga"),
 *                 @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *                 @OA\Property(property="category_id", type="integer", example=1),
 *                 @OA\Property(property="difficulty", type="string", example="beginner"),
 *                 @OA\Property(property="duration", type="integer", example=30)
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/activities', function() {
    $category_id = Flight::request()->query['category_id'] ?? null;
    $difficulty = Flight::request()->query['difficulty'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::activityService()->searchActivities($query, $category_id, $difficulty));
    } else if ($category_id) {
        Flight::json(Flight::activityService()->getActivitiesByCategory($category_id));
    } else {
        Flight::json(Flight::activityService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Get activity by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
Flight::route('GET /api/activities/@id', function($id) {
    $activity = Flight::activityService()->getById($id);
    if ($activity) {
        Flight::json($activity);
    } else {
        Flight::halt(404, 'Activity not found');
    }
});

/**
 * @OA\Post(
 *     path="/api/activities",
 *     tags={"activities"},
 *     summary="Create a new activity",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "category_id", "difficulty", "duration"},
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", enum={"beginner", "intermediate", "advanced"}, example="beginner"),
 *             @OA\Property(property="duration", type="integer", minimum=1, example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Activity created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /api/activities', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::activityService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Update an activity",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", enum={"beginner", "intermediate", "advanced"}, example="beginner"),
 *             @OA\Property(property="duration", type="integer", minimum=1, example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
Flight::route('PUT /api/activities/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::activityService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Activity not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Delete an activity",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Activity deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
Flight::route('DELETE /api/activities/@id', function($id) {
    try {
        $result = Flight::activityService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Activity not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Get(
 *     path="/api/activities/popular",
 *     tags={"activities"},
 *     summary="Get popular activities",
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of popular activities",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Morning Yoga"),
 *                 @OA\Property(property="popularity_score", type="number", format="float", example=4.5)
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/activities/popular', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::activityService()->getPopularActivities($limit));
});

/**
 * @OA\Get(
 *     path="/api/activities/mood/{mood_id}",
 *     tags={"activities"},
 *     summary="Get activities by mood",
 *     @OA\Parameter(
 *         name="mood_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of activities matching the mood",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Morning Yoga"),
 *                 @OA\Property(property="mood_match_score", type="number", format="float", example=0.85)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid mood ID or other error"
 *     )
 * )
 */
Flight::route('GET /api/activities/mood/@mood_id', function($mood_id) {
    try {
        $activities = Flight::activityService()->getActivitiesByMood($mood_id);
        Flight::json($activities);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});
?> 