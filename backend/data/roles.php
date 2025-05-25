<?php
namespace Dzelitin\SarayGo;

class Roles {
    const ADMIN = "admin";
    const USER = "user";
    
    // Permission constants
    const PERMISSION_READ = "read";
    const PERMISSION_WRITE = "write";
    const PERMISSION_DELETE = "delete";
    
    // Role permissions mapping
    public static function getRolePermissions($role) {
        $permissions = [
            self::ADMIN => [
                self::PERMISSION_READ,
                self::PERMISSION_WRITE,
                self::PERMISSION_DELETE
            ],
            self::USER => [
                self::PERMISSION_READ
            ]
        ];
        
        return $permissions[$role] ?? [];
    }
} 