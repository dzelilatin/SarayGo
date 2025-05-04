<?php
/**
 * @OA\Get(
 *     path="/SarayGo/backend/activities",
 *     tags={"activities"},
 *     summary="Get all activities",
 *     @OA\Response(
 *         response=200,
 *         description="List of all activities"
 *     )
 * )
 */
Flight::route('GET /SarayGo/backend/activities', function() {
    Flight::json(Flight::activityService()->getAll());
});

/**
 * @OA\Get(
 *     path="/SarayGo/backend/activities/{id}",
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
Flight::route('GET /SarayGo/backend/activities/@id', function($id) {
    $activity = Flight::activityService()->getById($id);
    if ($activity) {
        Flight::json($activity);
    } else {
        Flight::halt(404, 'Activity not found');
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
 *             required={"name", "description", "location"},
 *             @OA\Property(property="name", type="string", example="Hiking"),
 *             @OA\Property(property="description", type="string", example="Mountain hiking activity"),
 *             @OA\Property(property="location", type="string", example="Mountains"),
 *             @OA\Property(property="price", type="number", format="float", example=50.00),
 *             @OA\Property(property="duration", type="integer", example=120),
 *             @OA\Property(property="max_participants", type="integer", example=20)
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
    Flight::json(Flight::activityService()->create($data), 201);
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
 *             @OA\Property(property="name", type="string", example="Hiking"),
 *             @OA\Property(property="description", type="string", example="Mountain hiking activity"),
 *             @OA\Property(property="location", type="string", example="Mountains"),
 *             @OA\Property(property="price", type="number", format="float", example=50.00),
 *             @OA\Property(property="duration", type="integer", example=120),
 *             @OA\Property(property="max_participants", type="integer", example=20)
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
Flight::route('PUT /activities/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::activityService()->update($id, $data));
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
    Flight::json(Flight::activityService()->delete($id));
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
 *     )
 * )
 */
Flight::route('GET /activities/location/@location', function($location) {
    Flight::json(Flight::activityService()->getByLocation($location));
});
?> 