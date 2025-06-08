<?php

/**
 * @OA\Get(
 *     path="/offers",
 *     tags={"offers"},
 *     summary="Get all offers",
 *     description="Retrieve a list of all offers.",
 *     @OA\Response(response=200, description="List of offers returned successfully."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('GET /offers', function () {
    Flight::json(Flight::offerService()->getAllOffers());
});

/**
 * @OA\Get(
 *     path="/offers/category/{name}",
 *     tags={"offers"},
 *     summary="Get offers by category",
 *     description="Retrieve offers by a specific category name.",
 *     @OA\Parameter(name="name", in="path", required=true, description="Category name", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Offers returned successfully."),
 *     @OA\Response(response=404, description="Category not found."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('GET /offers/category/@name', function ($category_name) {
    Flight::json(Flight::offerService()->getByCategoryName($category_name));
});

/**
 * @OA\Get(
 *     path="/offers/name/{name}",
 *     tags={"offers"},
 *     summary="Get offers by name",
 *     description="Retrieve offers by a specific name.",
 *     @OA\Parameter(name="name", in="path", required=true, description="Offer name", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Offers returned successfully."),
 *     @OA\Response(response=404, description="Offer not found."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('GET /offers/name/@name', function ($category_name) {
    Flight::json(Flight::offerService()->getByName($category_name));
});

/**
 * @OA\Get(
 *     path="/offer/id/{id}",
 *     tags={"offers"},
 *     summary="Get offer by ID",
 *     description="Retrieve a specific offer by its ID.",
 *     @OA\Parameter(name="id", in="path", required=true, description="Offer ID", @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Offer returned successfully."),
 *     @OA\Response(response=404, description="Offer not found."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route('GET /offer/id/@id', function ($offer_id) {
    Flight::json(Flight::offerService()->getOfferById($offer_id));
});
