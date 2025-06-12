<?php
require_once __DIR__ . '/../Services/CartService.php';


/**
 * @OA\Get(
 *     path="/cart/{user_ID}",
 *     tags={"cart"},
 *     summary="Get cart by user ID",
 *     description="Retrieve the cart for a specific user by their user ID.",
 *     @OA\Parameter(name="user_ID", in="path", required=true, description="ID of the user", @OA\Schema(type="integer", example=1)),
 *     @OA\Response(response=200, description="Cart returned successfully."),
 *     @OA\Response(response=404, description="User or cart not found."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route("GET /cart/@user_ID", function ($user_ID) {

    $service = new CartService();

    Flight::json($service->getCartByUserID($user_ID));
});



/**
 * @OA\Get(
 *     path="/cart/item/new-item",
 *     tags={"cart"},
 *     summary="Add a new item to the cart",
 *     description="Add a new item to the cart by providing cart ID and offer ID.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="cart_ID", type="integer", example=1, description="ID of the cart"),
 *             @OA\Property(property="offer_id", type="integer", example=101, description="ID of the offer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Item added to the cart successfully."),
 *     @OA\Response(response=400, description="Invalid input data."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route("POST /cart/item/new-item", function () {
    $data = Flight::request()->data->getData();

    $user = Flight::get('user');

    $user_ID = $data['user_ID'];
    $offer_id = $data['offer_id'];



    if ($user->user_id != $user_ID) {
        Flight::json(['error' => 'Unauthorized user'], 403);
        return;
    }

    if (!$user_ID || !$offer_id) {
        Flight::json(['error' => 'Invalid input data'], 400);
        return;
    }


    $service = new CartService();
    Flight::json($service->bookOffer($offer_id, $user_ID));
});


Flight::route('POST /user/create-cart/@userId', function ($userId) {


    $USER_TOKEN = Flight::get('user');

    if ($USER_TOKEN->user_id != $userId) {
        Flight::json(['Status' => 'Error', 'Message' => 'You are not that user :) ']);
    };


    Flight::json(Flight::userService()->createCart($userId));
});
