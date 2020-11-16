<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/classes/Connection.php

define("DB_HOST", "localhost");
define("DB_NAME", "iwww");
define("DB_USER", "test");
define("DB_PASSWORD", "test");

class Connection
{
    static private $instance = NULL;

    static function getPdoInstance(): PDO
    {
        if (self::$instance == NULL) {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "", DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = $conn;
        }
        return self::$instance;
    }
}