<?php
/**
 * @OA\Get(
 *     path="/recommendations",
 *     tags={"recommendations"},
 *     summary="Get all recommendations",
 *     @OA\Response(
 *         response=200,
 *         description="List of all recommendations"
 *     )
 * )
 */
Flight::route('GET /recommendations', function() {
    Flight::json(Flight::recommendationService()->getAll());
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
 *             required={"title", "description", "activity_id"},
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="priority", type="integer", example=1),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example=["morning", "yoga"])
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
    Flight::json(Flight::recommendationService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/recommendations/{id}",
 *     tags={"recommendations"},
 *     summary="Update recommendation by ID",
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
 *             @OA\Property(property="activity_id", type="integer", example=1),
 *             @OA\Property(property="mood_id", type="integer", example=1),
 *             @OA\Property(property="priority", type="integer", example=1),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example=["morning", "yoga"])
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
    Flight::json(Flight::recommendationService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/recommendations/{id}",
 *     tags={"recommendations"},
 *     summary="Delete recommendation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Recommendation deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Recommendation not found"
 *     )
 * )
 */
Flight::route('DELETE /recommendations/@id', function($id) {
    Flight::json(Flight::recommendationService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/recommendations/mood/{mood_id}",
 *     tags={"recommendations"},
 *     summary="Get recommendations by mood",
 *     @OA\Parameter(
 *         name="mood_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of recommendations for the mood"
 *     )
 * )
 */
Flight::route('GET /recommendations/mood/@mood_id', function($mood_id) {
    Flight::json(Flight::recommendationService()->getByMood($mood_id));
});

/**
 * @OA\Get(
 *     path="/recommendations/activity/{activity_id}",
 *     tags={"recommendations"},
 *     summary="Get recommendations by activity",
 *     @OA\Parameter(
 *         name="activity_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of recommendations for the activity"
 *     )
 * )
 */
Flight::route('GET /recommendations/activity/@activity_id', function($activity_id) {
    Flight::json(Flight::recommendationService()->getByActivity($activity_id));
});

/**
 * @OA\Get(
 *     path="/recommendations/search",
 *     tags={"recommendations"},
 *     summary="Search recommendations",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="yoga")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching recommendations"
 *     )
 * )
 */
Flight::route('GET /recommendations/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::recommendationService()->search($query));
});
?> 