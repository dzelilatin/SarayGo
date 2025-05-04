<?php
/**
 * @OA\Get(
 *     path="/blogs",
 *     tags={"blogs"},
 *     summary="Get all blogs",
 *     @OA\Response(
 *         response=200,
 *         description="List of all blogs"
 *     )
 * )
 */
Flight::route('GET /blogs', function() {
    Flight::json(Flight::blogService()->getAll());
});

/**
 * @OA\Get(
 *     path="/blogs/{id}",
 *     tags={"blogs"},
 *     summary="Get blog by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blog details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Blog not found"
 *     )
 * )
 */
Flight::route('GET /blogs/@id', function($id) {
    $blog = Flight::blogService()->getById($id);
    if ($blog) {
        Flight::json($blog);
    } else {
        Flight::halt(404, 'Blog not found');
    }
});

/**
 * @OA\Post(
 *     path="/blogs",
 *     tags={"blogs"},
 *     summary="Create a new blog",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "author_id"},
 *             @OA\Property(property="title", type="string", example="My Travel Experience"),
 *             @OA\Property(property="content", type="string", example="This is my travel experience..."),
 *             @OA\Property(property="author_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example=["travel", "adventure"]),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Blog created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /blogs', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::blogService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/blogs/{id}",
 *     tags={"blogs"},
 *     summary="Update blog by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="My Travel Experience"),
 *             @OA\Property(property="content", type="string", example="This is my travel experience..."),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example=["travel", "adventure"]),
 *             @OA\Property(property="image_url", type="string", example="https://example.com/image.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blog updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Blog not found"
 *     )
 * )
 */
Flight::route('PUT /blogs/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::blogService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/blogs/{id}",
 *     tags={"blogs"},
 *     summary="Delete blog by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blog deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Blog not found"
 *     )
 * )
 */
Flight::route('DELETE /blogs/@id', function($id) {
    Flight::json(Flight::blogService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/blogs/search",
 *     tags={"blogs"},
 *     summary="Search blogs",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="travel")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching blogs"
 *     )
 * )
 */
Flight::route('GET /blogs/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::blogService()->search($query));
});

/**
 * @OA\Get(
 *     path="/blogs/category/{category_id}",
 *     tags={"blogs"},
 *     summary="Get blogs by category",
 *     @OA\Parameter(
 *         name="category_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of blogs in category"
 *     )
 * )
 */
Flight::route('GET /blogs/category/@category_id', function($category_id) {
    Flight::json(Flight::blogService()->getByCategory($category_id));
});

/**
 * @OA\Get(
 *     path="/blogs/author/{author_id}",
 *     tags={"blogs"},
 *     summary="Get blogs by author",
 *     @OA\Parameter(
 *         name="author_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of blogs by author"
 *     )
 * )
 */
Flight::route('GET /blogs/author/@author_id', function($author_id) {
    Flight::json(Flight::blogService()->getByAuthor($author_id));
});
?> 