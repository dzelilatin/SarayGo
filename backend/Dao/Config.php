<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{

    public static function DATABASE_NAME()
    {
        return getenv('DATABASE_NAME') ?: 'SarayGo';
    }

    public static function DATABASE_HOST()
    {
        return '127.0.0.1;unix_socket=/tmp/mysql.sock';
    }

    public static function DATABASE_USERNAME()
    {
        return getenv('DATABASE_USERNAME') ?: 'root';
    }

    public static function DATABASE_PASSWORD()
    {
        return getenv('DATABASE_PASSWORD') ?: '';
    }

    public static function JWT_SECRET()
    {
        return getenv('JWT_SECRET') ?: 'extremelysecurekey';
    }
}
