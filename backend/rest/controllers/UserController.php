<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $service;

    public function __construct() {
        $this->service = new UserService();
    }

    public function register() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->service->registerUser($data);
            echo json_encode(["message" => "User registered successfully"]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $user = $this->service->loginUser($data['email'], $data['password']);
            echo json_encode(["message" => "Login successful", "user" => $user]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
?>
