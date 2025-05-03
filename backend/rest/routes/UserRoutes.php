<?php
/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="List of all users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="username", type="string", example="john_doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/users', function() {
    $query = Flight::request()->query['query'] ?? null;
    $role = Flight::request()->query['role'] ?? null;
    
    if ($query) {
        Flight::json(Flight::userService()->searchUsers($query));
    } else if ($role) {
        Flight::json(Flight::userService()->getUsersByRole($role));
    } else {
        Flight::json(Flight::userService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('GET /api/users/@id', function($id) {
    $user = Flight::userService()->getById($id);
    if ($user) {
        Flight::json($user);
    } else {
        Flight::halt(404, 'User not found');
    }
});

/**
 * @OA\Post(
 *     path="/api/users",
 *     tags={"users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="securePassword123!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /api/users', function() {
    $data = Flight::request()->data->getData();
    
    // Validate required fields
    $required = ['username', 'email', 'password'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            Flight::halt(400, json_encode(['error' => "Missing required field: $field"]));
        }
    }

    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        Flight::halt(400, json_encode(['error' => 'Invalid email format']));
    }

    // Validate password strength
    if (strlen($data['password']) < 8) {
        Flight::halt(400, json_encode(['error' => 'Password must be at least 8 characters long']));
    }

    $result = Flight::userService()->create($data);
    Flight::json($result, 201);
});

/**
 * @OA\Put(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Update a user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="securePassword123!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('PUT /api/users/@id', function($id) {
    $data = Flight::request()->data->getData();

    // Validate email format if provided
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        Flight::halt(400, json_encode(['error' => 'Invalid email format']));
    }

    // Validate password strength if provided
    if (isset($data['password']) && strlen($data['password']) < 8) {
        Flight::halt(400, json_encode(['error' => 'Password must be at least 8 characters long']));
    }

    $result = Flight::userService()->update($id, $data);
    if (!$result) {
        Flight::halt(404, json_encode(['error' => 'User not found']));
    }
    Flight::json($result);
});

/**
 * @OA\Delete(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="User deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('DELETE /api/users/@id', function($id) {
    $result = Flight::userService()->delete($id);
    if (!$result) {
        Flight::halt(404, json_encode(['error' => 'User not found']));
    }
    Flight::json(null, 204);
});

// Get user by username
Flight::route('GET /api/users/username/@username', function($username) {
    try {
        $user = Flight::userService()->getByUsername($username);
        if ($user) {
            Flight::json($user);
        } else {
            Flight::halt(404, 'User not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get user by email
Flight::route('GET /api/users/email/@email', function($email) {
    try {
        $user = Flight::userService()->getByEmail($email);
        if ($user) {
            Flight::json($user);
        } else {
            Flight::halt(404, 'User not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Register a new user
Flight::route('POST /api/users/register', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::userService()->register($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// User login
Flight::route('POST /api/users/login', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::userService()->login($data);
        Flight::json($result);
    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

// Change password
Flight::route('PUT /api/users/@id/password', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::userService()->changePassword($id, $data);
        if ($result) {
            Flight::json(['message' => 'Password changed successfully']);
        } else {
            Flight::halt(404, 'User not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Reset password
Flight::route('POST /api/users/reset-password', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::userService()->resetPassword($data);
        Flight::json(['message' => 'Password reset instructions sent']);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get active users
Flight::route('GET /api/users/active', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::userService()->getActiveUsers($limit));
});

// Get user statistics
Flight::route('GET /api/users/statistics', function() {
    Flight::json(Flight::userService()->getUserStatistics());
});
?> 