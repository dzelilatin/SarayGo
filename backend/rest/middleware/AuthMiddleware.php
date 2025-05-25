<?php
namespace Dzelitin\SarayGo\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dzelitin\SarayGo\Config;
use Dzelitin\SarayGo\Roles;

class AuthMiddleware {
    public function verifyToken($token) {
        error_log("Starting token verification...");
        error_log("Initial token parameter: " . ($token ? "present" : "null"));
        
        $headers = getallheaders();
        error_log("All headers: " . json_encode($headers));
        
        // Check both Authorization and Authentication headers
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? $headers['Authentication'] ?? $headers['authentication'] ?? null;
        error_log("Authorization/Authentication header: " . ($authHeader ?: "null"));
        
        if (!$token && $authHeader) {
            $token = $authHeader;
            error_log("Using token from header: " . $token);
        }
        
        if (!$token) {
            error_log("No token provided in either parameter or headers");
            \Flight::halt(401, "No token provided");
        }
        
        // Remove 'Bearer ' if present
        $token = str_replace('Bearer ', '', $token);
        error_log("Cleaned token: " . $token);
        
        try {
            $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            error_log("Token decoded successfully");
            \Flight::set('user', $decoded->user);
            \Flight::set('jwt_token', $token);
            return true;
        } catch (\Exception $e) {
            error_log("Token verification failed: " . $e->getMessage());
            \Flight::halt(401, "Invalid token: " . $e->getMessage());
        }
    }

    public function authorizeRole($requiredRole) {
        $user = \Flight::get('user');
        if (!$user || !isset($user->role) || $user->role !== $requiredRole) {
            \Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles($roles) {
        $user = \Flight::get('user');
        if (!$user || !isset($user->role) || !in_array($user->role, $roles)) {
            \Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    public function authorizePermission($permission) {
        $user = \Flight::get('user');
        if (!$user || !isset($user->permissions) || !in_array($permission, $user->permissions)) {
            \Flight::halt(403, 'Access denied: permission missing');
        }
    }

    public function verifyIsAdmin() {
        $this->authorizeRole(Roles::ADMIN);
    }

    public function verifyIsUser() {
        $this->authorizeRole(Roles::USER);
    }
} 