<?php
namespace Dzelitin\SarayGo\routes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once 'BaseRoutes.php';
require_once(__DIR__ . '/../services/AuthService.php');

use Dzelitin\SarayGo\services\AuthService;

/**
 * @OA\Tag(
 *     name="auth",
 *     description="Authentication endpoints"
 * )
 */
class AuthRoutes extends BaseRoutes {
    public function __construct() {
        parent::__construct(new AuthService());
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     tags={"auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "password"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or email already exists"
     *     )
     * )
     */
    public function register() {
        $data = \Flight::request()->data->getData();
        $result = $this->service->register($data);
        
        if ($result['success']) {
            \Flight::json($result['data'], 201);
        } else {
            \Flight::json(['error' => $result['error']], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"auth"},
     *     summary="Login user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login() {
        error_log("✅ AuthRoutes::login() reached");
        $data = \Flight::request()->data->getData();
        $result = $this->service->login($data);
        
        if ($result['success']) {
            \Flight::json([
                'message' => 'User logged in successfully',
                'data' => $result['data']
            ]);
        } else {
            \Flight::json(['error' => $result['error']], 400);
        }
    }        

    public function refreshToken() {
        $data = \Flight::request()->data->getData();
        $result = $this->service->refreshToken($data['refresh_token']);
        if ($result) {
            \Flight::json($result);
        } else {
            \Flight::halt(401, 'Invalid refresh token');
        }
    }

    public function logout() {
        $token = \Flight::request()->getHeader('Authorization');
        if ($token) {
            $this->service->logout($token);
        }
        \Flight::json(['message' => 'Logged out successfully']);
    }

    public function getCurrentUser() {
        $token = \Flight::request()->getHeader('Authorization');
        if ($token) {
            $user = $this->service->getCurrentUser($token);
            if ($user) {
                \Flight::json($user);
                return;
            }
        }
        \Flight::halt(401, 'Unauthorized');
    }

    public function forgotPassword() {
        $data = \Flight::request()->data->getData();
        $this->service->forgotPassword($data['email']);
        \Flight::json(['message' => 'Password reset email sent']);
    }

    public function resetPassword() {
        $data = \Flight::request()->data->getData();
        $result = $this->service->resetPassword($data['token'], $data['password']);
        if ($result) {
            \Flight::json(['message' => 'Password reset successful']);
        } else {
            \Flight::halt(400, 'Invalid or expired token');
        }
    }

    public function get_routes() {
        \Flight::route('POST /auth/register', [$this, 'register']);
        \Flight::route('POST /auth/login', [$this, 'login']);
        \Flight::route('POST /auth/refresh-token', [$this, 'refreshToken']);
        \Flight::route('POST /auth/logout', [$this, 'logout']);
        \Flight::route('GET /auth/me', [$this, 'getCurrentUser']);
        \Flight::route('POST /auth/forgot-password', [$this, 'forgotPassword']);
        \Flight::route('POST /auth/reset-password', [$this, 'resetPassword']);
    }
}
?> 