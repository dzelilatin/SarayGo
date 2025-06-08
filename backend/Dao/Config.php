<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{

    public static function DATABASE_NAME()
    {
        return 'SarayGo';
    }

    public static function DATABASE_HOST()
    {
        return 'localhost';
    }

    public static function DATABASE_USERNAME()
    {
        return 'root';
    }

    public static function DATABASE_PASSWORD()
    {
        return '';
    }

    public static function JWT_SECRET()
    {
        return 'extremelysecurekey';
    }
}
