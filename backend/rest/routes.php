<?php
// Load environment variables
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set base path
Flight::set('flight.base_path', '/SarayGo/backend');

// Register services
try {
    // Register services
    Flight::register('authService', 'Dzelitin\\SarayGo\\Services\\AuthService');
    Flight::register('activityService', 'Dzelitin\\SarayGo\\Services\\ActivityService');
    Flight::register('userService', 'Dzelitin\\SarayGo\\Services\\UserService');
    Flight::register('blogService', 'Dzelitin\\SarayGo\\Services\\BlogService');
    Flight::register('categoryService', 'Dzelitin\\SarayGo\\Services\\CategoryService');
    Flight::register('contactService', 'Dzelitin\\SarayGo\\Services\\ContactService');
    Flight::register('moodService', 'Dzelitin\\SarayGo\\Services\\MoodService');
    Flight::register('recommendationService', 'Dzelitin\\SarayGo\\Services\\RecommendationService');
    Flight::register('reviewService', 'Dzelitin\\SarayGo\\Services\\ReviewService');
    Flight::register('userMoodService', 'Dzelitin\\SarayGo\\Services\\UserMoodService');
} catch (Exception $e) {
    error_log('Service registration failed: ' . $e->getMessage());
    die('Service initialization failed. Please check the logs.');
}

// Register routes
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/ActivityRoutes.php';
require_once __DIR__ . '/routes/BlogRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.php';
require_once __DIR__ . '/routes/ContactRoutes.php';
require_once __DIR__ . '/routes/MoodRoutes.php';
require_once __DIR__ . '/routes/RecommendationRoutes.php';
require_once __DIR__ . '/routes/ReviewRoutes.php';
require_once __DIR__ . '/routes/UserMoodRoutes.php';

// CORS middleware
Flight::before('start', function(&$params, &$output) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    if (Flight::request()->method == 'OPTIONS') {
        Flight::halt(200);
    }
});

// Error handling middleware
Flight::map('error', function(Exception $ex) {
    // Log the error
    error_log($ex->getMessage());
    
    // Return appropriate error response
    $response = [
        'error' => true,
        'message' => $ex->getMessage()
    ];
    
    if ($ex->getCode() == 401) {
        Flight::json($response, 401);
    } else if ($ex->getCode() == 404) {
        Flight::json($response, 404);
    } else {
        Flight::json($response, 500);
    }
});

// Authentication middleware
Flight::before('start', function(&$params, &$output) {
    $publicRoutes = [
        '/auth/login',
        '/auth/register',
        '/auth/forgot-password',
        '/auth/reset-password'
    ];
    
    $currentRoute = Flight::request()->url;
    
    // Skip authentication for public routes
    foreach ($publicRoutes as $route) {
        if (strpos($currentRoute, $route) === 0) {
            return;
        }
    }
    
    // Check authentication for protected routes
    $token = Flight::request()->getHeader('Authorization');
    if (!$token || !Flight::authService()->validateToken($token)) {
        Flight::halt(401, 'Unauthorized');
    }
});

// Start the application
Flight::start();
?> 