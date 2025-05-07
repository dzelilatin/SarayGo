<?php
/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Get all categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     )
 * )
 */
Flight::route('GET /categories', function() {
    Flight::json(Flight::categoryService()->getAll());
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
 * @OA\Post(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Create a new category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string", example="Adventure"),
 *             @OA\Property(property="description", type="string", example="Adventure activities and experiences"),
 *             @OA\Property(property="parent_id", type="integer", example=null),
 *             @OA\Property(property="icon", type="string", example="adventure-icon.png")
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
    Flight::json(Flight::categoryService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Update category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Adventure"),
 *             @OA\Property(property="description", type="string", example="Adventure activities and experiences"),
 *             @OA\Property(property="parent_id", type="integer", example=null),
 *             @OA\Property(property="icon", type="string", example="adventure-icon.png")
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
    Flight::json(Flight::categoryService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Delete category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    Flight::json(Flight::categoryService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/categories/parent/{parent_id}",
 *     tags={"categories"},
 *     summary="Get subcategories by parent ID",
 *     @OA\Parameter(
 *         name="parent_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of subcategories"
 *     )
 * )
 */
Flight::route('GET /categories/parent/@parent_id', function($parent_id) {
    Flight::json(Flight::categoryService()->getSubcategories($parent_id));
});

/**
 * @OA\Get(
 *     path="/categories/tree",
 *     tags={"categories"},
 *     summary="Get category tree",
 *     @OA\Response(
 *         response=200,
 *         description="Category hierarchy tree"
 *     )
 * )
 */
Flight::route('GET /categories/tree', function() {
    Flight::json(Flight::categoryService()->getCategoryTree());
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