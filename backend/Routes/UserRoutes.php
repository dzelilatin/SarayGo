<?php





/**
 * @OA\Get(
 *     path="/user/cart/{user_ID}",
 *     summary="Get user cart and orders",
 *     description="Retrieve the cart and orders for a specific user.",
 *     tags={"user"},
 *     @OA\Parameter(
 *         name="user_ID",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User cart and orders retrieved successfully."
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized access to another user's data."
 *     )
 * )
 */
Flight::route("GET /user/cart/@user_ID", function ($user_ID) {

    $USER_TOKEN = Flight::get('user');

    $cart = Flight::userService()->getUserCart($user_ID);

    Flight::json($cart);
});




/**
 * @OA\Put(
 *   path="/cart/{cart_ID}/offer",
 *   tags={"cart"},
 *   summary="Update the offer on a cart",
 *   @OA\Parameter(
 *     name="cart_ID", in="path", required=true,
 *     @OA\Schema(type="integer", example=123)
 *   ),
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *       @OA\Property(property="offer_id", type="integer", example=7)
 *     )
 *   ),
 *   @OA\Response(response=200, description="Cart offer updated")
 * )
 */
Flight::route("POST /cart/@cart_ID/offer", function ($cart_ID) {

    $data = Flight::request()->data->getData();

    $offer_id = $data['offer_id'];

    $cartService = Flight::cartService();


    Flight::json($cartService->bookOffer($cart_ID, $offer_id));
});


/**
 * @OA\Get(
 *     path="/user/{user_ID}/orders",
 *     summary="Get user orders",
 *     description="Retrieve all orders for a specific user.",
 *     tags={"user"},
 *     @OA\Parameter(
 *         name="user_ID",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User orders retrieved successfully."
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized access to another user's data."
 *     )
 * )
 */
Flight::route('GET /user/@user_ID/orders', function ($user_ID) {
    $USER_TOKEN = Flight::get('user');

    if ($USER_TOKEN->user_id != $user_ID) {
        Flight::json(['Status' => 'Error', 'Message' => 'You are not that user :) ']);
    };

    Flight::json(Flight::userService()->getUserOrders($user_ID));
});


/**
 * @OA\Delete(
 *     path="/user/cart/deletecart/{user_ID}",
 *     summary="Delete user cart and checkout",
 *     description="Delete the cart of a specific user and perform checkout.",
 *     tags={"user"},
 *     @OA\Parameter(
 *         name="user_ID",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 * 
 *     @OA\Response(
 *         response=200,
 *         description="Cart deleted and checkout completed successfully."
 *     ),
 *      @OA\Response(
 *         response=401,
 *         description="Missing auth header. Go to Postman/SwaggerUI and login as a user. In Headers provide Authentication : <JWT_TOKEN>."
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Unauthorized access to another user's data."
 *     )
 * )
 */
Flight::route('DELETE /user/cart/deletecart/@user_ID', function ($user_ID) {
    $USER_TOKEN = Flight::get('user');

    if ($USER_TOKEN->user_id != $user_ID) {
        Flight::json(['Status' => 'Error', 'Message' => 'You are not that user :) ']);
    };

    Flight::json(Flight::cartService()->deleteCartByUserID($user_ID));
});
