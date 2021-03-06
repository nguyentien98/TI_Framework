<?php

namespace Core;

use PDO;

class DBConnection
{
    private static $connection = null;

    private function __construct()
    {
    }

    private static function connectToDatabase()
    {
        $config = config('config.DB_Connect');
        try {
            $connection = new PDO(
                'mysql:host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['username'],
                $config['password'],
                [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
            );

            return $connection;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getConnection()
    {
        if (!self::$connection) {
            self::$connection = self::connectToDatabase();
        }

        return self::$connection;
    }

    public static function disconnection()
    {
        if (self::$connection) {
            self::$connection = null;
        }
    }
}
