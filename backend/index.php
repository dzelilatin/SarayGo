<?php
require 'vendor/autoload.php';

// Register services
require_once __DIR__ . '/rest/services/ActivityService.php';
require_once __DIR__ . '/rest/services/BlogService.php';
require_once __DIR__ . '/rest/services/CategoryService.php';
require_once __DIR__ . '/rest/services/ContactService.php';
require_once __DIR__ . '/rest/services/MoodService.php';
require_once __DIR__ . '/rest/services/RecommendationService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/UserMoodService.php';
require_once __DIR__ . '/rest/services/UserService.php';

Flight::register('activityService', 'ActivityService');
Flight::register('blogService', 'BlogService');
Flight::register('categoryService', 'CategoryService');
Flight::register('contactService', 'ContactService');
Flight::register('moodService', 'MoodService');
Flight::register('recommendationService', 'RecommendationService');
Flight::register('reviewService', 'ReviewService');
Flight::register('userMoodService', 'UserMoodService');
Flight::register('userService', 'UserService');

// Include route files
require_once __DIR__ . '/rest/routes/ActivityRoutes.php';
require_once __DIR__ . '/rest/routes/BlogRoutes.php';
require_once __DIR__ . '/rest/routes/CategoryRoutes.php';
require_once __DIR__ . '/rest/routes/ContactRoutes.php';
require_once __DIR__ . '/rest/routes/MoodRoutes.php';
require_once __DIR__ . '/rest/routes/RecommendationRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/UserMoodRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';

// Start FlightPHP
Flight::start();
?> 