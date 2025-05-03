<?php
/**
 * @OA\Get(
 *     path="/api/moods",
 *     tags={"moods"},
 *     summary="Get all moods",
 *     @OA\Response(
 *         response=200,
 *         description="List of all moods",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Happy"),
 *                 @OA\Property(property="description", type="string", example="Feeling joyful and content"),
 *                 @OA\Property(property="icon", type="string", example="😊")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/moods', function() {
    $result = Flight::moodService()->getAll();
    Flight::json($result);
});

/**
 * @OA\Get(
 *     path="/api/moods/{id}",
 *     tags={"moods"},
 *     summary="Get mood by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Mood details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mood not found"
 *     )
 * )
 */
Flight::route('GET /api/moods/@id', function($id) {
    $result = Flight::moodService()->getById($id);
    if (!$result) {
        Flight::halt(404, json_encode(['error' => 'Mood not found']));
    }
    Flight::json($result);
});

/**
 * @OA\Post(
 *     path="/api/moods",
 *     tags={"moods"},
 *     summary="Create a new mood",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description", "icon"},
 *             @OA\Property(property="name", type="string", example="Happy"),
 *             @OA\Property(property="description", type="string", example="Feeling joyful and content"),
 *             @OA\Property(property="icon", type="string", example="😊")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Mood created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /api/moods', function() {
    $data = Flight::request()->data->getData();
    
    // Validate required fields
    $required = ['name', 'description', 'icon'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            Flight::halt(400, json_encode(['error' => "Missing required field: $field"]));
        }
    }

    $result = Flight::moodService()->create($data);
    Flight::json($result, 201);
});

/**
 * @OA\Put(
 *     path="/api/moods/{id}",
 *     tags={"moods"},
 *     summary="Update a mood",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Happy"),
 *             @OA\Property(property="description", type="string", example="Feeling joyful and content"),
 *             @OA\Property(property="icon", type="string", example="😊")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Mood updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mood not found"
 *     )
 * )
 */
Flight::route('PUT /api/moods/@id', function($id) {
    $data = Flight::request()->data->getData();
    $result = Flight::moodService()->update($id, $data);
    if (!$result) {
        Flight::halt(404, json_encode(['error' => 'Mood not found']));
    }
    Flight::json($result);
});

/**
 * @OA\Delete(
 *     path="/api/moods/{id}",
 *     tags={"moods"},
 *     summary="Delete a mood",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Mood deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mood not found"
 *     )
 * )
 */
Flight::route('DELETE /api/moods/@id', function($id) {
    $result = Flight::moodService()->delete($id);
    if (!$result) {
        Flight::halt(404, json_encode(['error' => 'Mood not found']));
    }
    Flight::json(null, 204);
});
?> 