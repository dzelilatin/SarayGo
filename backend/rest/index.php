<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once "UserController.php";

header("Content-Type: application/json");
$controller = new UserController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_GET['action'] === "register") {
        $controller->register();
    } elseif ($_GET['action'] === "login") {
        $controller->login();
    }
}
?>
