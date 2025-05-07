<?php
/**
 * @OA\Get(
 *     path="/moods",
 *     tags={"moods"},
 *     summary="Get all moods",
 *     @OA\Response(
 *         response=200,
 *         description="List of all moods"
 *     )
 * )
 */
Flight::route('GET /moods', function() {
    Flight::json(Flight::moodService()->getAll());
});

/**
 * @OA\Get(
 *     path="/moods/{id}",
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
Flight::route('GET /moods/@id', function($id) {
    $mood = Flight::moodService()->getById($id);
    if ($mood) {
        Flight::json($mood);
    } else {
        Flight::halt(404, 'Mood not found');
    }
});

/**
 * @OA\Post(
 *     path="/moods",
 *     tags={"moods"},
 *     summary="Create a new mood",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="Happy"),
 *             @OA\Property(property="description", type="string", example="Feeling joyful and content"),
 *             @OA\Property(property="icon", type="string", example="happy-icon.png"),
 *             @OA\Property(property="color", type="string", example="#FFD700")
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
Flight::route('POST /moods', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::moodService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/moods/{id}",
 *     tags={"moods"},
 *     summary="Update mood by ID",
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
 *             @OA\Property(property="icon", type="string", example="happy-icon.png"),
 *             @OA\Property(property="color", type="string", example="#FFD700")
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
Flight::route('PUT /moods/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::moodService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/moods/{id}",
 *     tags={"moods"},
 *     summary="Delete mood by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Mood deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mood not found"
 *     )
 * )
 */
Flight::route('DELETE /moods/@id', function($id) {
    Flight::json(Flight::moodService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/moods/search",
 *     tags={"moods"},
 *     summary="Search moods",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="happy")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching moods"
 *     )
 * )
 */
Flight::route('GET /moods/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::moodService()->search($query));
});

/**
 * @OA\Get(
 *     path="/moods/activities/{mood_id}",
 *     tags={"moods"},
 *     summary="Get activities for a mood",
 *     @OA\Parameter(
 *         name="mood_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of activities matching the mood"
 *     )
 * )
 */
Flight::route('GET /moods/activities/@mood_id', function($mood_id) {
    Flight::json(Flight::moodService()->getActivitiesByMood($mood_id));
});
?> 