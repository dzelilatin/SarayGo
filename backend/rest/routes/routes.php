<?php
require_once __DIR__ . '/../services/ActivityService.php';
require_once __DIR__ . '/../services/BlogService.php';
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../services/ContactService.php';
require_once __DIR__ . '/../services/MoodService.php';
require_once __DIR__ . '/../services/RecommendationService.php';
require_once __DIR__ . '/../services/ReviewService.php';
require_once __DIR__ . '/../services/UserMoodService.php';
require_once __DIR__ . '/../services/UserService.php';

// Get the base path
$base_path = '/SarayGo/backend/api';
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0]; // Remove query params
$method = $_SERVER['REQUEST_METHOD'];

// Remove the base path from the request URI
$path = str_replace($base_path, '', $request_uri);

// Initialize services
$activityService = new ActivityService();
$blogService = new BlogService();
$categoryService = new CategoryService();
$contactService = new ContactService();
$moodService = new MoodService();
$recommendationService = new RecommendationService();
$reviewService = new ReviewService();
$userMoodService = new UserMoodService();
$userService = new UserService();

// Helper function for input validation
function validateInput($data, $requiredFields) {
    $errors = [];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $errors[] = "Missing required field: $field";
        }
    }
    return $errors;
}

// Helper function for error response
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

/**
 * @OA\Post(
 *     path="/api/user/register",
 *     tags={"auth"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "name"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", minLength=8, example="password123"),
 *             @OA\Property(property="name", type="string", example="John Doe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="name", type="string", example="John Doe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Missing required field: email")
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Email already exists",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Email already registered")
 *         )
 *     )
 * )
 */
if ($method === 'POST' && $path === '/user/register') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendError('Invalid JSON input');
        }

        $errors = validateInput($data, ['email', 'password', 'name']);
        if (!empty($errors)) {
            sendError(implode(', ', $errors));
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            sendError('Invalid email format');
        }

        // Validate password strength
        if (strlen($data['password']) < 8) {
            sendError('Password must be at least 8 characters long');
        }

        $result = $userService->register($data);
        http_response_code(201);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Post(
 *     path="/api/user/login",
 *     tags={"auth"},
 *     summary="Login user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="email", type="string", example="user@example.com"),
 *                 @OA\Property(property="name", type="string", example="John Doe")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Invalid email or password")
 *         )
 *     )
 * )
 */
if ($method === 'POST' && $path === '/user/login') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendError('Invalid JSON input');
        }

        $errors = validateInput($data, ['email', 'password']);
        if (!empty($errors)) {
            sendError(implode(', ', $errors));
        }

        $result = $userService->login($data);
        if (!$result) {
            sendError('Invalid email or password', 401);
        }

        http_response_code(200);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Get(
 *     path="/api/activities",
 *     tags={"activities"},
 *     summary="Get all activities",
 *     @OA\Parameter(
 *         name="category",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of all activities",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Morning Yoga"),
 *                 @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *                 @OA\Property(property="category_id", type="integer", example=1),
 *                 @OA\Property(property="difficulty", type="string", example="beginner"),
 *                 @OA\Property(property="duration", type="integer", example=30)
 *             )
 *         )
 *     )
 * )
 */
if ($method === 'GET' && $path === '/activities') {
    try {
        $category = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $result = $category ? $activityService->getByCategory($category) : $activityService->getAll();
        http_response_code(200);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

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
if ($method === 'GET' && $path === '/blog') {
    try {
        $category = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $result = $category ? $blogService->getByCategory($category) : $blogService->getAll();
        http_response_code(200);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Get(
 *     path="/api/categories",
 *     tags={"categories"},
 *     summary="Get all categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/categories') {
    http_response_code(200);
    echo json_encode($categoryService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/contacts",
 *     tags={"contacts"},
 *     summary="Get all contacts",
 *     @OA\Response(
 *         response=200,
 *         description="List of all contacts"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/contacts') {
    http_response_code(200);
    echo json_encode($contactService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/moods",
 *     tags={"moods"},
 *     summary="Get all moods",
 *     @OA\Response(
 *         response=200,
 *         description="List of all moods"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/moods') {
    http_response_code(200);
    echo json_encode($moodService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/recommendations",
 *     tags={"recommendations"},
 *     summary="Get all recommendations",
 *     @OA\Response(
 *         response=200,
 *         description="List of all recommendations"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/recommendations') {
    http_response_code(200);
    echo json_encode($recommendationService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="List of all reviews"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/reviews') {
    http_response_code(200);
    echo json_encode($reviewService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/user-moods",
 *     tags={"user-moods"},
 *     summary="Get all user moods",
 *     @OA\Response(
 *         response=200,
 *         description="List of all user moods"
 *     )
 * )
 */
if ($method === 'GET' && $path === '/user-moods') {
    http_response_code(200);
    echo json_encode($userMoodService->getAll());
}

/**
 * @OA\Get(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Get activity by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity details",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", example="beginner"),
 *             @OA\Property(property="duration", type="integer", example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
if ($method === 'GET' && preg_match('/^\/activities\/(\d+)$/', $path, $matches)) {
    try {
        $id = (int)$matches[1];
        $result = $activityService->getById($id);
        if (!$result) {
            sendError('Activity not found', 404);
        }
        http_response_code(200);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Post(
 *     path="/api/activities",
 *     tags={"activities"},
 *     summary="Create a new activity",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description", "category_id", "difficulty", "duration"},
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", enum={"beginner", "intermediate", "advanced"}, example="beginner"),
 *             @OA\Property(property="duration", type="integer", minimum=1, example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Activity created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", example="beginner"),
 *             @OA\Property(property="duration", type="integer", example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
if ($method === 'POST' && $path === '/activities') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendError('Invalid JSON input');
        }

        $errors = validateInput($data, ['title', 'description', 'category_id', 'difficulty', 'duration']);
        if (!empty($errors)) {
            sendError(implode(', ', $errors));
        }

        // Validate difficulty
        $validDifficulties = ['beginner', 'intermediate', 'advanced'];
        if (!in_array($data['difficulty'], $validDifficulties)) {
            sendError('Invalid difficulty level');
        }

        // Validate duration
        if (!is_numeric($data['duration']) || $data['duration'] < 1) {
            sendError('Duration must be a positive number');
        }

        $result = $activityService->create($data);
        http_response_code(201);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Put(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Update an activity",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Morning Yoga"),
 *             @OA\Property(property="description", type="string", example="Start your day with yoga"),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="difficulty", type="string", enum={"beginner", "intermediate", "advanced"}, example="beginner"),
 *             @OA\Property(property="duration", type="integer", minimum=1, example=30)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Activity updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
if ($method === 'PUT' && preg_match('/^\/activities\/(\d+)$/', $path, $matches)) {
    try {
        $id = (int)$matches[1];
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendError('Invalid JSON input');
        }

        // Validate difficulty if provided
        if (isset($data['difficulty'])) {
            $validDifficulties = ['beginner', 'intermediate', 'advanced'];
            if (!in_array($data['difficulty'], $validDifficulties)) {
                sendError('Invalid difficulty level');
            }
        }

        // Validate duration if provided
        if (isset($data['duration']) && (!is_numeric($data['duration']) || $data['duration'] < 1)) {
            sendError('Duration must be a positive number');
        }

        $result = $activityService->update($id, $data);
        if (!$result) {
            sendError('Activity not found', 404);
        }
        http_response_code(200);
        echo json_encode($result);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

/**
 * @OA\Delete(
 *     path="/api/activities/{id}",
 *     tags={"activities"},
 *     summary="Delete an activity",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Activity deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Activity not found"
 *     )
 * )
 */
if ($method === 'DELETE' && preg_match('/^\/activities\/(\d+)$/', $path, $matches)) {
    try {
        $id = (int)$matches[1];
        $result = $activityService->delete($id);
        if (!$result) {
            sendError('Activity not found', 404);
        }
        http_response_code(204);
    } catch (Exception $e) {
        sendError($e->getMessage(), 500);
    }
}

// Default route for undefined endpoints
http_response_code(404);
echo json_encode(['error' => 'Not Found']);
?>
