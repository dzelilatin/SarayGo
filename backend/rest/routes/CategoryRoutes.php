<?php
/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Get all categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Yoga"),
 *                 @OA\Property(property="description", type="string", example="Yoga activities and exercises")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /categories', function() {
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::categoryService()->searchCategories($query));
    } else {
        Flight::json(Flight::categoryService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Get category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    $category = Flight::categoryService()->getById($id);
    if ($category) {
        Flight::json($category);
    } else {
        Flight::halt(404, 'Category not found');
    }
});

/**
 * @OA\Get(
 *     path="/categories/name/@name",
 *     tags={"categories"},
 *     summary="Get category by name",
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", example="Yoga")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('GET /categories/name/@name', function($name) {
    $category = Flight::categoryService()->getByName($name);
    if ($category) {
        Flight::json($category);
    } else {
        Flight::halt(404, 'Category not found');
    }
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Create a new category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="Yoga"),
 *             @OA\Property(property="description", type="string", example="Yoga activities and exercises")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Category created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /categories', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::categoryService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Update a category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Yoga"),
 *             @OA\Property(property="description", type="string", example="Yoga activities and exercises")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::categoryService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Category not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Delete a category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Category deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    try {
        $result = Flight::categoryService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Category not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Get(
 *     path="/categories/with-blog-count",
 *     tags={"categories"},
 *     summary="Get categories with blog count",
 *     @OA\Response(
 *         response=200,
 *         description="List of categories with blog count"
 *     )
 * )
 */
Flight::route('GET /categories/with-blog-count', function() {
    Flight::json(Flight::categoryService()->get_with_blog_count());
});

/**
 * @OA\Get(
 *     path="/categories/with-activity-count",
 *     tags={"categories"},
 *     summary="Get categories with activity count",
 *     @OA\Response(
 *         response=200,
 *         description="List of categories with activity count"
 *     )
 * )
 */
Flight::route('GET /categories/with-activity-count', function() {
    Flight::json(Flight::categoryService()->getCategoriesWithActivityCount());
});

/**
 * @OA\Get(
 *     path="/categories/popular",
 *     tags={"categories"},
 *     summary="Get popular categories",
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=10)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of popular categories"
 *     )
 * )
 */
Flight::route('GET /categories/popular', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::categoryService()->getPopularCategories($limit));
});
?> 