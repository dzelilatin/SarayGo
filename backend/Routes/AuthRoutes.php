<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Register new user.",
 *     description="Add a new user to the database.",
 *     tags={"auth"},
 *     @OA\RequestBody(
 *         description="Add new user",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"password", "email"},
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     example="some_password",
 *                     description="User password"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="demo@gmail.com",
 *                     description="User email"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User has been added."
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request. Invalid input."
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="User already exists."
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error."
 *     )
 * )
 */
Flight::route("POST /auth/register", function () {
    $data = Flight::request()->data->getData();
    $response = Flight::authService()->register($data);
    if ($response['success']) {
        Flight::json([
            'message' => 'User registered successfully',
            'data' => $response['data']
        ]);
    } else {
        Flight::halt(500., $response['error']);
    }
});

/**
 * @OA\Post(
 *      path="/auth/login",
 *      tags={"auth"},
 *      summary="Login to system using email and password",
 *      description="Authenticate user and return JWT token.",
 *      @OA\RequestBody(
 *          description="Credentials used to login the user.",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"email","password"},
 *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="Example email address"),
 *              @OA\Property(property="password", type="string", example="some_password", description="Example password")
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="User data and JWT gets sent"
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bad request. Invalid credentials."
 *      ),
 *      @OA\Response(
 *           response=401,
 *           description="Unauthorized. Invalid email or password."
 *      ),
 *      @OA\Response(
 *           response=500,
 *           description="Internal server error."
 *      )
 * )
 */
Flight::route("POST /auth/login", function () {
    $data = Flight::request()->data->getData();



    $response = Flight::authService()->login($data);
    if ($response['success']) {
        Flight::json([
            'message' => 'User logged in successfully',
            'data' => $response['data']
        ]);
    } else {
        Flight::halt(500, $response['error']);
    }
});
