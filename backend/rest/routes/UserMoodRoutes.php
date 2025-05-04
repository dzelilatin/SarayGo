<?php
/**
 * @OA\Get(
 *     path="/user-moods",
 *     tags={"user-moods"},
 *     summary="Get all user moods",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all user moods",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="mood_id", type="integer", example=1),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /user-moods', function() {
    $user_id = Flight::request()->query['user_id'] ?? null;
    $mood_id = Flight::request()->query['mood_id'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::userMoodService()->searchUserMoods($query));
    } else if ($user_id) {
        Flight::json(Flight::userMoodService()->get_by_user($user_id));
    } else if ($mood_id) {
        Flight::json(Flight::userMoodService()->get_by_mood($mood_id));
    } else {
        Flight::json(Flight::userMoodService()->getAll());
    }
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
 *             @OA\Property(property="mood_id", type="integer", example=1)
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
    try {
        $result = Flight::userMoodService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/user-moods/{id}",
 *     tags={"user-moods"},
 *     summary="Update a user mood",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="mood_id", type="integer", example=1)
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
    try {
        $result = Flight::userMoodService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'User mood not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/user-moods/{id}",
 *     tags={"user-moods"},
 *     summary="Delete a user mood",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="User mood deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User mood not found"
 *     )
 * )
 */
Flight::route('DELETE /user-moods/@id', function($id) {
    try {
        $result = Flight::userMoodService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'User mood not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
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