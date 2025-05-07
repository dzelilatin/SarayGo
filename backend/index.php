<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

// If accessing root or docs, serve Swagger UI
if (preg_match('#^/SarayGo/backend/?$#', $_SERVER['REQUEST_URI']) || 
    preg_match('#^/SarayGo/backend/docs/?$#', $_SERVER['REQUEST_URI'])) {
    require __DIR__ . '/public/v1/docs/index.php';
    exit;
}

// Start FlightPHP
Flight::start();
?>
