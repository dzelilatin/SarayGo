<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../../vendor/autoload.php';  // Updated path to point to backend/vendor

// Test JWT library loading
error_log("Checking JWT library...");
error_log("Autoload path: " . __DIR__ . '/../../vendor/autoload.php');
error_log("Class exists check: " . (class_exists('Firebase\JWT\JWT') ? 'true' : 'false'));

if (!class_exists('Firebase\JWT\JWT')) {
    error_log("JWT class not found. Autoload path: " . __DIR__ . '/../../vendor/autoload.php');
    die("JWT library not found. Please run 'composer require firebase/php-jwt'");
}

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dzelitin\SarayGo\Config;
use Dzelitin\SarayGo\dao\AuthDao;

class AuthService extends BaseService {
    private $auth_dao;
    
    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct(new AuthDao());
    }

    public function getUserByEmail($email) {
        return $this->auth_dao->getUserByEmail($email);
    }

    public function register($entity) {
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        if (!filter_var($entity['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid Email Address!!!'];
        }

        $email_exists = $this->auth_dao->getUserByEmail($entity['email']);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);
        $result = parent::create($entity);

        // Fetch the newly created user to return (by email)
        $user = $this->auth_dao->getUserByEmail($entity['email']);
        if (is_array($user)) {
            unset($user['password']);
        }

        return ['success' => true, 'data' => $user];
    }

    public function login($entity) {
        error_log("Login attempt for email: " . $entity['email']);
        
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->getUserByEmail($entity['email']);
        if (!$user) {
            error_log("User not found for email: " . $entity['email']);
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        if (!password_verify($entity['password'], $user['password'])) {
            error_log("Invalid password for user: " . $entity['email']);
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        error_log("Password verified for user: " . $entity['email']);
        unset($user['password']);

        try {
            $jwt_payload = [
                'user' => $user,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24) // 24 hours
            ];

            error_log("Generating JWT token with payload: " . json_encode($jwt_payload));
            $token = JWT::encode(
                $jwt_payload,
                Config::JWT_SECRET(),
                'HS256'
            );
            error_log("JWT token generated successfully");

            $response = [
                'success' => true,
                'data' => array_merge($user, ['token' => $token])
            ];
            error_log("Login response: " . json_encode($response));
            return $response;
        } catch (\Exception $e) {
            error_log("Error generating JWT token: " . $e->getMessage());
            return ['success' => false, 'error' => 'Authentication failed: ' . $e->getMessage()];
        }
    }
}
?> 