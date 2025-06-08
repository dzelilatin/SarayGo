<?php


Flight::group("/admin", function () {


    /**
     * @OA\Get(
     *     path="/admin/user/id/{user_ID}",
     *     tags={"admin"},
     *     summary="Get user by ID (admin)",
     *     description="Returns user details for the given user ID.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(name="user_ID", in="path", required=true, description="ID of the user", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="User details returned successfully."),
     *     @OA\Response(response=404, description="User not found."),
     *     @OA\Response(response=401, description="Unauthorized. Missing or invalid token."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("GET /user/id/@user_ID", function ($user_ID) {
        Flight::json(Flight::adminService()->getUserByID($user_ID));
    });

    /**
     * @OA\Get(
     *     path="/admin/user/name/{user_name}",
     *     tags={"admin"},
     *     summary="Get users by name (admin)",
     *     description="Returns a list of users matching the given name.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(name="user_name", in="path", required=true, description="Name of the user to search for", @OA\Schema(type="string", example="John Doe")),
     *     @OA\Response(response=200, description="Users returned successfully."),
     *     @OA\Response(response=404, description="No users found."),
     *     @OA\Response(response=401, description="Unauthorized. Missing or invalid token."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("GET /user/name/@user_name", function ($name) {




        Flight::json(Flight::adminService()->getUsersByName($name));
    });

    /**
     * @OA\Get(
     *     path="/admin/product/{product_name}",
     *     tags={"admin"},
     *     summary="Get products by name (admin)",
     *     description="Returns a list of products matching the given title.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(name="product_title", in="path", required=true, description="Title of the product to search for", @OA\Schema(type="string", example="Harry Potter")),
     *     @OA\Response(response=200, description="products returned successfully."),
     *     @OA\Response(response=404, description="No products found."),
     *     @OA\Response(response=401, description="Unauthorized. Missing or invalid token."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("GET /product/@product_title", function ($product_title) {
        Flight::json(Flight::productService()->getByTitle($product_title));
    });

    /**
     * @OA\Get(
     *     path="/admin/user/orders/{user_ID}",
     *     tags={"admin"},
     *     summary="Get order history for a user (admin)",
     *     description="Returns the order history for the specified user.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(name="user_ID", in="path", required=true, description="ID of the user", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Order history returned successfully."),
     *     @OA\Response(response=404, description="User or orders not found."),
     *     @OA\Response(response=401, description="Unauthorized. Missing or invalid token."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("GET /user/orders/@user_ID", function ($user_ID) {
        Flight::json(Flight::adminService()->getUserOrderHistory($user_ID));
    });

    /**
     * @OA\Get(
     *     path="/admin/user/cart/{user_ID}",
     *     tags={"admin"},
     *     summary="Get cart for a user (admin)",
     *     description="Returns the cart for the specified user.",
     *     security={{"ApiKey": {}}},
     *     @OA\Parameter(name="user_ID", in="path", required=true, description="ID of the user", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Cart returned successfully."),
     *     @OA\Response(response=404, description="User or cart not found."),
     *     @OA\Response(response=401, description="Unauthorized. Missing or invalid token."),
     *     @OA\Response(response=500, description="Internal server error.")
     * )
     */
    Flight::route("GET /user/cart/@user_ID", function ($user_ID) {
       

    $cart = Flight::userService()->getUserCart($user_ID);
    $orders = Flight::userService()->getUserOrders($user_ID);

    Flight::json([
        'cart' => $cart,
        'orders' => $orders,
    ]);
    });
});

