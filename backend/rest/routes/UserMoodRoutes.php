<?php
/**
 * @OA\Get(
 *     path="/user-moods",
 *     tags={"user-moods"},
 *     summary="Get all user moods",
 *     @OA\Response(
 *         response=200,
 *         description="List of all user moods"
 *     )
 * )
 */
Flight::route('GET /user-moods', function() {
    Flight::json(Flight::userMoodService()->getAll());
});

/**
 * @OA\Get(
 *     path="/user-moods/{id}",
 *     tags={"user-moods"},
 *     summary="Get user mood by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User mood details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User mood not found"
 *     )
 * )
 */
Flight::route('GET /user-moods/@id', function($id) {
    $userMood = Flight::userMoodService()->getById($id);
    if ($userMood) {
        Flight::json($userMood);
    } else {
        Flight::halt(404, 'User mood not found');
    }
});

/**
 * @OA\Post(
 *     path="/user-moods",
 *     tags={"user-moods"},
 *     summary="Create a new user mood",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "mood_id"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="intensity", type="integer", minimum=1, maximum=10, example=8),
 *             @OA\Property(property="notes", type="string", example="Feeling great today!"),
 *             @OA\Property(property="timestamp", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User mood created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /user-moods', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userMoodService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/user-moods/{id}",
 *     tags={"user-moods"},
 *     summary="Update user mood by ID",
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
 *             @OA\Property(property="intensity", type="integer", minimum=1, maximum=10, example=8),
 *             @OA\Property(property="notes", type="string", example="Feeling great today!"),
 *             @OA\Property(property="timestamp", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User mood updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User mood not found"
 *     )
 * )
 */
Flight::route('PUT /user-moods/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userMoodService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/user-moods/{id}",
 *     tags={"user-moods"},
 *     summary="Delete user mood by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User mood deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User mood not found"
 *     )
 * )
 */
Flight::route('DELETE /user-moods/@id', function($id) {
    Flight::json(Flight::userMoodService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/user-moods/user/{user_id}",
 *     tags={"user-moods"},
 *     summary="Get user moods by user ID",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user moods for the user"
 *     )
 * )
 */
Flight::route('GET /user-moods/user/@user_id', function($user_id) {
    Flight::json(Flight::userMoodService()->getByUser($user_id));
});

/**
 * @OA\Get(
 *     path="/user-moods/mood/{mood_id}",
 *     tags={"user-moods"},
 *     summary="Get user moods by mood ID",
 *     @OA\Parameter(
 *         name="mood_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user moods for the mood"
 *     )
 * )
 */
Flight::route('GET /user-moods/mood/@mood_id', function($mood_id) {
    Flight::json(Flight::userMoodService()->getByMood($mood_id));
});

/**
 * @OA\Get(
 *     path="/user-moods/user/{user_id}/recent",
 *     tags={"user-moods"},
 *     summary="Get recent user moods for a user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of recent user moods"
 *     )
 * )
 */
Flight::route('GET /user-moods/user/@user_id/recent', function($user_id) {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::userMoodService()->getRecentByUser($user_id, $limit));
});

// Get current mood for a user
Flight::route('GET /user-moods/current/@user_id', function($user_id) {
    try {
        $currentMood = Flight::userMoodService()->get_current_mood($user_id);
        if ($currentMood) {
            Flight::json($currentMood);
        } else {
            Flight::halt(404, 'No current mood found for user');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get mood history for a user
Flight::route('GET /user-moods/history/@user_id', function($user_id) {
    try {
        $history = Flight::userMoodService()->getMoodHistory($user_id);
        Flight::json($history);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get mood trends for a user
Flight::route('GET /user-moods/trends/@user_id', function($user_id) {
    try {
        $trends = Flight::userMoodService()->getMoodTrends($user_id);
        Flight::json($trends);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get mood statistics
Flight::route('GET /user-moods/statistics', function() {
    Flight::json(Flight::userMoodService()->getMoodStatistics());
});
?> 