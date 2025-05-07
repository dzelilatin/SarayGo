<?php
/**
 * @OA\Post(
 *     path="/auth/login",
 *     tags={"auth"},
 *     summary="User login",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="username", type="string", example="john_doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="role", type="string", example="user")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     )
 * )
 */
Flight::route('POST /auth/login', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::authService()->login($data['email'], $data['password']);
    if ($result) {
        Flight::json($result);
    } else {
        Flight::halt(401, 'Invalid credentials');
    }
});

/**
 * @OA\Post(
 *     path="/auth/register",
 *     tags={"auth"},
 *     summary="User registration",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="username", type="string", example="john_doe"),
 *                 @OA\Property(property="email", type="string", example="john@example.com"),
 *                 @OA\Property(property="role", type="string", example="user")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /auth/register', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::authService()->register($data);
    if ($result) {
        Flight::json($result, 201);
    } else {
        Flight::halt(400, 'Registration failed');
    }
});

/**
 * @OA\Post(
 *     path="/auth/refresh-token",
 *     tags={"auth"},
 *     summary="Refresh authentication token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"refresh_token"},
 *             @OA\Property(property="refresh_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Token refreshed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid refresh token"
 *     )
 * )
 */
Flight::route('POST /auth/refresh-token', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::authService()->refreshToken($data['refresh_token']);
    if ($result) {
        Flight::json($result);
    } else {
        Flight::halt(401, 'Invalid refresh token');
    }
});

/**
 * @OA\Post(
 *     path="/auth/logout",
 *     tags={"auth"},
 *     summary="User logout",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful"
 *     )
 * )
 */
Flight::route('POST /auth/logout', function() {
    $token = Flight::request()->getHeader('Authorization');
    if ($token) {
        Flight::authService()->logout($token);
    }
    Flight::json(['message' => 'Logged out successfully']);
});

/**
 * @OA\Get(
 *     path="/auth/me",
 *     tags={"auth"},
 *     summary="Get current user information",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Current user information",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="role", type="string", example="user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
Flight::route('GET /auth/me', function() {
    $token = Flight::request()->getHeader('Authorization');
    if ($token) {
        $user = Flight::authService()->getCurrentUser($token);
        if ($user) {
            Flight::json($user);
            return;
        }
    }
    Flight::halt(401, 'Unauthorized');
});

/**
 * @OA\Post(
 *     path="/auth/forgot-password",
 *     tags={"auth"},
 *     summary="Request password reset",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset email sent"
 *     )
 * )
 */
Flight::route('POST /auth/forgot-password', function() {
    $data = Flight::request()->data->getData();
    Flight::authService()->forgotPassword($data['email']);
    Flight::json(['message' => 'Password reset email sent']);
});

/**
 * @OA\Post(
 *     path="/auth/reset-password",
 *     tags={"auth"},
 *     summary="Reset password with token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"token", "password"},
 *             @OA\Property(property="token", type="string", example="reset_token_123"),
 *             @OA\Property(property="password", type="string", example="newpassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired token"
 *     )
 * )
 */
Flight::route('POST /auth/reset-password', function() {
    $data = Flight::request()->data->getData();
    $result = Flight::authService()->resetPassword($data['token'], $data['password']);
    if ($result) {
        Flight::json(['message' => 'Password reset successful']);
    } else {
        Flight::halt(400, 'Invalid or expired token');
    }
});
?> 