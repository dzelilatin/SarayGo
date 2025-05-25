<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug information
error_log("=== New Request ===");
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Script Name: " . $_SERVER['SCRIPT_NAME']);
error_log("PHP Self: " . $_SERVER['PHP_SELF']);
error_log("Query String: " . $_SERVER['QUERY_STRING']);
error_log("URL Parameter: " . (isset($_GET['url']) ? $_GET['url'] : 'none'));
error_log("Base URL: " . dirname($_SERVER['SCRIPT_NAME']));
error_log("Document Root: " . $_SERVER['DOCUMENT_ROOT']);
error_log("Request Time: " . date('Y-m-d H:i:s'));

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload classes using Composer
require_once __DIR__ . '/config.php';  // Include the config.php with Database class
use Dzelitin\SarayGo\Database;
use Dzelitin\SarayGo\Config;
use PDO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Configure error handling
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', true);

// Set the base URL for Flight
$baseUrl = '/SarayGo/backend/rest'; // Set the correct base URL with /rest
error_log("Setting Flight base URL to: " . $baseUrl);
Flight::set('flight.base_url', $baseUrl);

// Add route debugging
Flight::map('notFound', function() {
    error_log("404 - Route not found");
    error_log("Current URL: " . Flight::request()->url);
    error_log("Request Method: " . Flight::request()->method);
    error_log("Request URI: " . $_SERVER['REQUEST_URI']);
    error_log("Script Name: " . $_SERVER['SCRIPT_NAME']);
    Flight::json([
        'error' => 'Not Found',
        'url' => Flight::request()->url,
        'method' => Flight::request()->method,
        'request_uri' => $_SERVER['REQUEST_URI']
    ], 404);
});

// Use custom Database class for DB connection
Flight::register('db', 'PDO', array(
    'mysql:host=' . Config::DB_HOST() . ';dbname=' . Config::DB_NAME(),
    Config::DB_USER(),
    Config::DB_PASSWORD()
), function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

// Register DAOs (Data Access Objects) with the correct namespace
Flight::register('activityDao', 'Dzelitin\SarayGo\Dao\ActivityDao', array(Flight::db()));
Flight::register('blogDao', 'Dzelitin\SarayGo\Dao\BlogDao', array(Flight::db()));
Flight::register('categoryDao', 'Dzelitin\SarayGo\Dao\CategoryDao', array(Flight::db()));
Flight::register('contactDao', 'Dzelitin\SarayGo\Dao\ContactDao', array(Flight::db()));
Flight::register('moodDao', 'Dzelitin\SarayGo\Dao\MoodDao', array(Flight::db()));
Flight::register('recommendationDao', 'Dzelitin\SarayGo\Dao\RecommendationDao', array(Flight::db()));
Flight::register('reviewDao', 'Dzelitin\SarayGo\Dao\ReviewDao', array(Flight::db()));
Flight::register('userMoodDao', 'Dzelitin\SarayGo\Dao\UserMoodDao', array(Flight::db()));
Flight::register('userDao', 'Dzelitin\SarayGo\Dao\UserDao', array(Flight::db()));
Flight::register('authDao', 'Dzelitin\SarayGo\Dao\AuthDao', array(Flight::db()));

// Register Services (which rely on DAOs)
Flight::register('activityService', 'Dzelitin\\SarayGo\\services\\ActivityService', array(Flight::activityDao()));
Flight::register('blogService', 'Dzelitin\\SarayGo\\services\\BlogService', array(Flight::blogDao()));
Flight::register('categoryService', 'Dzelitin\\SarayGo\\services\\CategoryService', array(Flight::categoryDao()));
Flight::register('contactService', 'Dzelitin\\SarayGo\\services\\ContactService', array(Flight::contactDao()));
Flight::register('moodService', 'Dzelitin\\SarayGo\\services\\MoodService', array(Flight::moodDao()));
Flight::register('recommendationService', 'Dzelitin\\SarayGo\\services\\RecommendationService', array(Flight::recommendationDao()));
Flight::register('reviewService', 'Dzelitin\\SarayGo\\services\\ReviewService', array(Flight::reviewDao()));
Flight::register('userMoodService', 'Dzelitin\\SarayGo\\services\\UserMoodService', array(Flight::userMoodDao()));
Flight::register('userService', 'Dzelitin\\SarayGo\\services\\UserService', array(Flight::userDao()));
Flight::register('auth_service', 'Dzelitin\\SarayGo\\services\\AuthService', array(Flight::authDao()));
Flight::register('authService', 'Dzelitin\\SarayGo\\services\\AuthService', array(Flight::authDao()));

// Register Middleware
require_once __DIR__ . '/middleware/AuthMiddleware.php';
$authMiddleware = new Dzelitin\SarayGo\middleware\AuthMiddleware();

// Authentication Middleware
Flight::route('/*', function() use ($authMiddleware) {
    // Skip authentication for login and register routes
    if(
        strpos(Flight::request()->url, '/auth/login') === 0 ||
        strpos(Flight::request()->url, '/auth/register') === 0
    ) {
        return TRUE;
    }

    // For all other routes, verify JWT token
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? $headers['authorization'] ?? $headers['Authentication'] ?? $headers['authentication'] ?? null;
    error_log("Index.php - Token from headers: " . ($token ?: "null"));
    return $authMiddleware->verifyToken($token);
});

// Register Routes
error_log("Loading routes...");
require_once __DIR__ . '/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/ActivityRoutes.php';
require_once __DIR__ . '/routes/BlogRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.php';
require_once __DIR__ . '/routes/ContactRoutes.php';
require_once __DIR__ . '/routes/MoodRoutes.php';
require_once __DIR__ . '/routes/RecommendationRoutes.php';
require_once __DIR__ . '/routes/ReviewRoutes.php';
require_once __DIR__ . '/routes/UserMoodRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';
require_once __DIR__ . '/routes/BaseRoutes.php';

// Initialize and register routes
$authRoutes = new Dzelitin\SarayGo\routes\AuthRoutes();
$authRoutes->get_routes();

$userRoutes = new Dzelitin\SarayGo\routes\UserRoutes();
$userRoutes->get_routes();

error_log("Routes loaded");

// Add CORS headers
Flight::before('start', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication');
    
    // Handle pre-flight requests (OPTIONS)
    if (Flight::request()->method == 'OPTIONS') {
        Flight::halt(200);
    }
});

// Start the application
Flight::start();
?>