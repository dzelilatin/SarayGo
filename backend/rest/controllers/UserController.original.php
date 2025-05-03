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

            if (!$data) {
                throw new Exception("Invalid JSON input");
            }

            $this->service->registerUser($data);
            http_response_code(201); // 201 Created
            echo json_encode(["message" => "User registered successfully"]);
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data || !isset($data['email']) || !isset($data['password'])) {
                throw new Exception("Email and password are required");
            }

            $user = $this->service->loginUser($data['email'], $data['password']);
            http_response_code(200); // OK
            echo json_encode(["message" => "Login successful", "user" => $user]);
        } catch (Exception $e) {
            http_response_code(401); // Unauthorized
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
?> 