<?php
/**
 * @OA\Get(
 *     path="/api/blog",
 *     tags={"blogs"},
 *     summary="Get all blogs",
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all blogs",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="How to Stay Active"),
 *                 @OA\Property(property="content", type="string", example="Blog content here..."),
 *                 @OA\Property(property="author", type="string", example="John Doe"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/blogs', function() {
    $category_id = Flight::request()->query['category_id'] ?? null;
    $user_id = Flight::request()->query['user_id'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::blogService()->search($query));
    } else if ($category_id) {
        Flight::json(Flight::blogService()->get_by_category($category_id));
    } else if ($user_id) {
        Flight::json(Flight::blogService()->get_by_user($user_id));
    } else {
        Flight::json(Flight::blogService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/api/blog/{id}",
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
Flight::route('GET /api/blogs/@id', function($id) {
    $blog = Flight::blogService()->getById($id);
    if ($blog) {
        Flight::json($blog);
    } else {
        Flight::halt(404, 'Blog not found');
    }
});

/**
 * @OA\Post(
 *     path="/api/blog",
 *     tags={"blogs"},
 *     summary="Create a new blog post",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "author", "category_id"},
 *             @OA\Property(property="title", type="string", example="How to Stay Active"),
 *             @OA\Property(property="content", type="string", example="Blog content here..."),
 *             @OA\Property(property="author", type="string", example="John Doe"),
 *             @OA\Property(property="category_id", type="integer", example=1)
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
Flight::route('POST /api/blogs', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::blogService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/api/blog/{id}",
 *     tags={"blogs"},
 *     summary="Update a blog post",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="How to Stay Active"),
 *             @OA\Property(property="content", type="string", example="Blog content here..."),
 *             @OA\Property(property="author", type="string", example="John Doe"),
 *             @OA\Property(property="category_id", type="integer", example=1)
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
Flight::route('PUT /api/blogs/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::blogService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Blog not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/api/blog/{id}",
 *     tags={"blogs"},
 *     summary="Delete a blog post",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Blog deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Blog not found"
 *     )
 * )
 */
Flight::route('DELETE /api/blogs/@id', function($id) {
    try {
        $result = Flight::blogService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Blog not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get recent blogs
Flight::route('GET /api/blogs/recent', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::blogService()->getRecentBlogs($limit));
});

// Get popular blogs
Flight::route('GET /api/blogs/popular', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::blogService()->getPopularBlogs($limit));
});

// Get blogs by tags
Flight::route('GET /api/blogs/tags', function() {
    $tags = Flight::request()->query['tags'] ?? null;
    $limit = Flight::request()->query['limit'] ?? 10;
    
    if (!$tags) {
        Flight::halt(400, 'Tags parameter is required');
    }
    
    $tags = explode(',', $tags);
    Flight::json(Flight::blogService()->getBlogsByTags($tags, $limit));
});
?> 