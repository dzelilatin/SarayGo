<?php
namespace Dzelitin\SarayGo\middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dzelitin\SarayGo\Config;
use Dzelitin\SarayGo\Roles;

class AuthMiddleware {
    public function verifyToken($token) {
        try {
            if(!$token) {
                \Flight::halt(401, "Missing authentication header");
            }
            
            // Remove 'Bearer ' if present
            $token = str_replace('Bearer ', '', $token);
            
            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            
            if (!isset($decoded_token->user)) {
                \Flight::halt(401, "Invalid token structure");
            }
            
            \Flight::set('user', $decoded_token->user);
            \Flight::set('jwt_token', $token);
            return TRUE;
        } catch (\Exception $e) {
            \Flight::halt(401, "Invalid token: " . $e->getMessage());
        }
    }

    public function authorizeRole($requiredRole) {
        $user = \Flight::get('user');
        if ($user->role !== $requiredRole) {
            \Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function authorizeRoles($roles) {
        $user = \Flight::get('user');
        if (!in_array($user->role, $roles)) {
            \Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    function authorizePermission($permission) {
        $user = \Flight::get('user');
        if (!in_array($permission, $user->permissions)) {
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