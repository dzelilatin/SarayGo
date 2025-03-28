<?php
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ActivityController.php';
// Add more controllers as needed

header("Content-Type: application/json");

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0]; // Remove query params
$method = $_SERVER['REQUEST_METHOD'];

$userController = new UserController();
// $activityController = new ActivityController();

// Define routes
switch ($request_uri) {
    // 🟢 User Routes
    case '/api/user/register':
        if ($method === 'POST') {
            $userController->register();
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
        }
        break;

    case '/api/user/login':
        if ($method === 'POST') {
            $userController->login();
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
        }
        break;

    // 🟢 Activity Routes (Example)
    case '/api/activities':
        if ($method === 'GET') {
            $activityController->getAllActivities();
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
        }
        break;
    
    case '/api/activities/by-category':
        if ($method === 'GET') {
            $activityController->getByCategory();
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
        }
        break;
        
    // 🚨 Default: Route Not Found
    default:
        http_response_code(404);
        echo json_encode(["error" => "Route Not Found"]);
        break;
}
?>
