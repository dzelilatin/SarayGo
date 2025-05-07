<?php
require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function register() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['email']) || !isset($data['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Email and password are required']);
                return;
            }

            // Mock successful registration
            $response = [
                'message' => 'User registered successfully',
                'user' => [
                    'id' => 1,
                    'email' => $data['email']
                ]
            ];
            
            http_response_code(201);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function login() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['email']) || !isset($data['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Email and password are required']);
                return;
            }

            // Mock successful login
            $response = [
                'message' => 'Login successful',
                'token' => 'mock_jwt_token',
                'user' => [
                    'id' => 1,
                    'email' => $data['email']
                ]
            ];
            
            http_response_code(200);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
