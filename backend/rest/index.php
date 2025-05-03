<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Configure error handling
Flight::set('flight.handle_errors', true);
Flight::set('flight.log_errors', true);

// Configure database connection
Flight::register('db', 'PDO', array(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
), function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
});

// Register DAOs
Flight::register('activityDao', 'ActivityDao', array(Flight::db()));
Flight::register('blogDao', 'BlogDao', array(Flight::db()));
Flight::register('categoryDao', 'CategoryDao', array(Flight::db()));
Flight::register('contactDao', 'ContactDao', array(Flight::db()));
Flight::register('moodDao', 'MoodDao', array(Flight::db()));
Flight::register('recommendationDao', 'RecommendationDao', array(Flight::db()));
Flight::register('reviewDao', 'ReviewDao', array(Flight::db()));
Flight::register('userMoodDao', 'UserMoodDao', array(Flight::db()));
Flight::register('userDao', 'UserDao', array(Flight::db()));

// Register Services
Flight::register('activityService', 'ActivityService', array(Flight::activityDao()));
Flight::register('blogService', 'BlogService', array(Flight::blogDao()));
Flight::register('categoryService', 'CategoryService', array(Flight::categoryDao()));
Flight::register('contactService', 'ContactService', array(Flight::contactDao()));
Flight::register('moodService', 'MoodService', array(Flight::moodDao()));
Flight::register('recommendationService', 'RecommendationService', array(Flight::recommendationDao()));
Flight::register('reviewService', 'ReviewService', array(Flight::reviewDao()));
Flight::register('userMoodService', 'UserMoodService', array(Flight::userMoodDao()));
Flight::register('userService', 'UserService', array(Flight::userDao()));

// Register Routes
require_once __DIR__ . '/routes/ActivityRoutes.php';
require_once __DIR__ . '/routes/BlogRoutes.php';
require_once __DIR__ . '/routes/CategoryRoutes.php';
require_once __DIR__ . '/routes/ContactRoutes.php';
require_once __DIR__ . '/routes/MoodRoutes.php';
require_once __DIR__ . '/routes/RecommendationRoutes.php';
require_once __DIR__ . '/routes/ReviewRoutes.php';
require_once __DIR__ . '/routes/UserMoodRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';

// Add CORS headers
Flight::before('start', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    if (Flight::request()->method == 'OPTIONS') {
        Flight::halt(200);
    }
});

// Start the application
Flight::start();
?>
