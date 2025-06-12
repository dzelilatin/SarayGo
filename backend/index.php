<?php

require __DIR__ . '/vendor/autoload.php';

// SERVICES
require __DIR__ . '/Services/AdminService.php';
require __DIR__ . '/Services/AuthService.php';
require __DIR__ . '/Services/UserService.php';
require __DIR__ . '/Services/OfferService.php';
//  SERVICES



//  MIDDLEWARE
require_once __DIR__ . '/Middleware/AuthMiddleWare.php';
//  MIDDLEWARE



//  CONFIG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//  CONFIG



// JWT
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
// JWT

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

//ROUTE MIDDLEWARE START
//ROUTE MIDDLEWARE END



#############################SERVICES#######################################

Flight::register('adminService', 'AdminService');
Flight::register('authService', 'AuthService');
Flight::register('cartItemsService', 'CartItemsService');
Flight::register('cartService', 'CartService');
Flight::register('categoryService', 'CategoryService');
Flight::register('offerService', 'OfferService');
Flight::register('orderDetailsService', 'OrderDetailsService');
Flight::register('productService', 'ProductService');
Flight::register('userService', 'UserService');
############################################################################

##########################MIDDLEWARE########################################
Flight::register('authMiddleware', 'AuthMiddleWare');

Flight::set('flight.base_url', '/backend');

require_once __DIR__ . '/Routes/testRoute.php';
require_once __DIR__ . '/Routes/AdminRoutes.php';
require_once __DIR__ . '/Routes/AuthRoutes.php';
require_once __DIR__ . '/Routes/CartRoutes.php';
require_once __DIR__ . '/Routes/UserRoutes.php';
require_once __DIR__ . '/Routes/OfferRoutes.php';

Flight::start();
