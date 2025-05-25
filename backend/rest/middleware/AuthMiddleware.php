<?php
namespace Dzelitin\SarayGo\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dzelitin\SarayGo\Config;

class AuthMiddleware {
    public function verifyToken() {
        error_log("🔍 AuthMiddleware::verifyToken() - Starting token verification");
        
        // Get Authorization header from $_SERVER
        $headers = getallheaders();
        error_log("📝 All headers: " . print_r($headers, true));
        
        // Try different ways to get the Authorization header
        $token = null;
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        
        error_log("🔑 Authorization header: " . ($token ? $token : 'null'));
        
        if (!$token) {
            error_log("❌ No token provided in request");
            \Flight::halt(401, json_encode(['error' => 'No token provided']));
            return false;
        }

        try {
            $token = str_replace('Bearer ', '', $token);
            error_log("🔑 Cleaned token: " . $token);
            
            $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            error_log("✅ Token decoded successfully: " . print_r($decoded, true));
            
            \Flight::set('user', $decoded->user);
            return true;
        } catch (\Exception $e) {
            error_log("❌ Token verification failed: " . $e->getMessage());
            \Flight::halt(401, json_encode(['error' => 'Invalid token: ' . $e->getMessage()]));
            return false;
        }
    }

    public function isAdmin() {
        $user = \Flight::get('user');
        return $user && isset($user->role) && $user->role === 'admin';
    }

    public function hasPermission($permission) {
        $user = \Flight::get('user');
        return $user && isset($user->permissions) && in_array($permission, $user->permissions);
    }

    public function authorizeRole(int $requiredPermissionLevel) {
        $user = \Flight::get('user');
        if ($user->is_admin === $requiredPermissionLevel) {
            \Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function verifyIsAdmin() {
        $user = \Flight::get('user');
        if ($user->is_admin === 0) {
            \Flight::halt(403, 'Access denied: User is NOT admin.');
        }
        return true;
    }

    public function authorizeRoles($roles) {
        $user = \Flight::get('user');
        if (!in_array($user->role, $roles)) {
            \Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    public function authorizePermission($permission) {
        $user = \Flight::get('user');
        if (!in_array($permission, $user->permissions)) {
            \Flight::halt(403, 'Access denied: permission missing');
        }
    }
} 