<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{

    public function verifyToken($token)
    {
        if (!$token)
            Flight::halt(401, "Missing authentication header");

        $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

        Flight::set('user', $decoded_token->user);
        Flight::set('user_token', $token);
        return TRUE;
    }

    public function authorizeRole(int $requiredPermissionLevel)
    {
        $user = Flight::get('user');
        if ($user->is_admin === $requiredPermissionLevel) {
            Flight::halt(403, 'Access denied: insufficient privileges');
        }
    }

    public function verifyIsAdmin()
    {
        $user = Flight::get('user');
        if ($user->is_admin === 0) {
            Flight::halt(0, 'Access denied: User is NOT admin.');
        }

        return TRUE;
    }

    public function getUserId()
    {
        $user = Flight::get('user');

        return $user->user_id;
    }

    public function authorizeRoles($roles)
    {
        $user = Flight::get('user');
        if (!in_array($user->role, $roles)) {
            Flight::halt(403, 'Forbidden: role not allowed');
        }
    }

    function authorizePermission($permission)
    {
        $user = Flight::get('user');
        if (!in_array($permission, $user->permissions)) {
            Flight::halt(403, 'Access denied: permission missing');
        }
    }
}
