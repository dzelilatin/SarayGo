<?php
define('TEST_MODE', true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/roles.php';

use Dzelitin\SarayGo\Config;
use Dzelitin\SarayGo\Roles;
use Firebase\JWT\JWT;

// Test data
$testUser = (object)[
    'id' => 1,
    'username' => 'testuser',
    'email' => 'test@example.com',
    'role' => Roles::USER,
    'permissions' => Roles::getRolePermissions(Roles::USER)
];

$testAdmin = (object)[
    'id' => 2,
    'username' => 'admin',
    'email' => 'admin@example.com',
    'role' => Roles::ADMIN,
    'permissions' => Roles::getRolePermissions(Roles::ADMIN)
];

// Function to generate JWT token
function generateToken($user) {
    return JWT::encode(['user' => $user], Config::JWT_SECRET(), 'HS256');
}

// Function to test token verification
function testTokenVerification($authMiddleware) {
    echo "\nTesting Token Verification:\n";
    
    // Test 1: Valid token
    $token = generateToken($GLOBALS['testUser']);
    try {
        $result = $authMiddleware->verifyToken($token);
        echo "✅ Valid token test passed\n";
    } catch (Exception $e) {
        echo "❌ Valid token test failed: " . $e->getMessage() . "\n";
    }
    
    // Test 2: Invalid token
    try {
        $authMiddleware->verifyToken('invalid_token');
        echo "❌ Invalid token test failed (should have thrown exception)\n";
    } catch (Exception $e) {
        echo "✅ Invalid token test passed\n";
    }
    
    // Test 3: Empty token
    try {
        $authMiddleware->verifyToken('');
        echo "❌ Empty token test failed (should have thrown exception)\n";
    } catch (Exception $e) {
        echo "✅ Empty token test passed\n";
    }
}

// Function to test role authorization
function testRoleAuthorization($authMiddleware) {
    echo "\nTesting Role Authorization:\n";
    
    // Set up test user in Flight
    \Flight::set('user', $GLOBALS['testUser']);
    
    // Test 1: User role check
    try {
        $authMiddleware->authorizeRole(Roles::USER);
        echo "✅ User role check passed\n";
    } catch (Exception $e) {
        echo "❌ User role check failed: " . $e->getMessage() . "\n";
    }
    
    // Test 2: Admin role check (should fail for regular user)
    try {
        $authMiddleware->authorizeRole(Roles::ADMIN);
        echo "❌ Admin role check failed (should have thrown exception)\n";
    } catch (Exception $e) {
        echo "✅ Admin role check passed (correctly denied access)\n";
    }
    
    // Test 3: Multiple roles check
    try {
        $authMiddleware->authorizeRoles([Roles::USER, Roles::ADMIN]);
        echo "✅ Multiple roles check passed\n";
    } catch (Exception $e) {
        echo "❌ Multiple roles check failed: " . $e->getMessage() . "\n";
    }
}

// Function to test permission authorization
function testPermissionAuthorization($authMiddleware) {
    echo "\nTesting Permission Authorization:\n";
    
    // Set up test user in Flight
    \Flight::set('user', $GLOBALS['testUser']);
    
    // Test 1: Valid permission
    try {
        $authMiddleware->authorizePermission(Roles::PERMISSION_READ);
        echo "✅ Valid permission check passed\n";
    } catch (Exception $e) {
        echo "❌ Valid permission check failed: " . $e->getMessage() . "\n";
    }
    
    // Test 2: Invalid permission
    try {
        $authMiddleware->authorizePermission(Roles::PERMISSION_WRITE);
        echo "❌ Invalid permission check failed (should have thrown exception)\n";
    } catch (Exception $e) {
        echo "✅ Invalid permission check passed (correctly denied access)\n";
    }
}

// Function to test admin-specific operations
function testAdminOperations($authMiddleware) {
    echo "\nTesting Admin Operations:\n";
    
    // Set up admin user in Flight
    \Flight::set('user', $GLOBALS['testAdmin']);
    
    // Test 1: Admin role verification
    try {
        $authMiddleware->verifyIsAdmin();
        echo "✅ Admin verification passed\n";
    } catch (Exception $e) {
        echo "❌ Admin verification failed: " . $e->getMessage() . "\n";
    }
    
    // Test 2: Admin permissions
    try {
        $authMiddleware->authorizePermission(Roles::PERMISSION_DELETE);
        echo "✅ Admin permission check passed\n";
    } catch (Exception $e) {
        echo "❌ Admin permission check failed: " . $e->getMessage() . "\n";
    }
}

// Run all tests
echo "Starting Authentication and Authorization Tests\n";
echo "============================================\n";

$authMiddleware = new Dzelitin\SarayGo\middleware\AuthMiddleware();

testTokenVerification($authMiddleware);
testRoleAuthorization($authMiddleware);
testPermissionAuthorization($authMiddleware);
testAdminOperations($authMiddleware);

echo "\nAll tests completed!\n"; 