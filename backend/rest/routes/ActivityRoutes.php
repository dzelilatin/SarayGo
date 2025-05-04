<?php
/**
 * @OA\Get(
 *     path="/activities",
 *     tags={"activities"},
 *     summary="Get all activities",
 *     @OA\Response(
 *         response=200,
 *         description="List of all activities"
 *     )
 * )
 */
Flight::route('GET /activities', function() {
    try {
        $activities = Flight::activityService()->getAll();
        Flight::json($activities);
    } catch (Exception $e) {
        error_log("Error in GET /activities: " . $e->getMessage());
        Flight::json(['error' => $e->getMessage()], 500);
    }
});

/**
 * @OA\Get(
 *     path="/activities/{id}",
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
Flight::route('GET /activities/@id', function($id) {
    try {
        if (!is_numeric($id)) {
            Flight::json(['error' => 'Invalid activity ID'], 400);
            return;
        }
        $activity = Flight::activityService()->getById($id);
        if ($activity) {
            Flight::json($activity);
        } else {
            Flight::json(['error' => 'Activity not found'], 404);
        }
    } catch (\Exception $e) {
        $code = $e->getCode();
        $code = ($code >= 400 && $code < 600) ? $code : 500;
        Flight::json(['error' => $e->getMessage()], $code);
    }
});

/**
 * @OA\Post(
 *     path="/activities",
 *     tags={"activities"},
 *     summary="Create a new activity",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"activity_name", "description", "category_id", "mood_id"},
 *             @OA\Property(property="activity_name", type="string", example="Hiking"),
 *             @OA\Property(property="description", type="string", example="Mountain hiking activity"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="location", type="string", example="Mountains")
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
Flight::route('POST /activities', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::activityService()->create($data);
        Flight::json($result, 201);
    } catch (\Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});

/**
 * @OA\Put(
 *     path="/activities/{id}",
 *     tags={"activities"},
 *     summary="Update activity by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"activity_name", "description", "category_id", "mood_id"},
 *             @OA\Property(property="activity_name", type="string", example="Hiking"),
 *             @OA\Property(property="description", type="string", example="Mountain hiking activity"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="location", type="string", example="Mountains")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error"
 *     )
 * )
 */
Flight::route('PUT /activities/@id', function($id) {
    try {
        $data = Flight::request()->data->getData();
        $result = Flight::activityService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Activity not found');
        }
    } catch (\Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Delete(
 *     path="/activities/{id}",
 *     tags={"activities"},
 *     summary="Delete activity by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
Flight::route('DELETE /activities/@id', function($id) {
    $result = Flight::activityService()->delete($id);
    if ($result) {
        Flight::json(['message' => 'Activity deleted successfully']);
    } else {
        Flight::halt(404, 'Activity not found');
    }
});

/**
 * @OA\Get(
 *     path="/activities/search",
 *     tags={"activities"},
 *     summary="Search activities",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="hiking")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching activities"
 *     )
 * )
 */
Flight::route('GET /activities/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::activityService()->search($query));
});

/**
 * @OA\Get(
 *     path="/activities/location/{location}",
 *     tags={"activities"},
 *     summary="Get activities by location",
 *     @OA\Parameter(
 *         name="location",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="Mountains")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of activities in location"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No activities found for this location"
 *     )
 * )
 */
Flight::route('GET /activities/location/@location', function($location) {
    Flight::json(Flight::activityService()->getByLocation($location));
});
?> 