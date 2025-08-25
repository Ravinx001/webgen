<?php

namespace User\SludiOauthPhp\Service;

use PDO;
use PDOException;

require_once __DIR__ . '/../Helper/functions.php';

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            $host = env('DB_HOST', 'localhost');
            $port = env('DB_PORT', '3306');
            $db   = env('DB_NAME', 'sludi');
            $user = env('DB_USER', 'root');
            $pass = env('DB_PASS', '');
            $charset = env('DB_CHARSET', 'utf8mb4');

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new \Exception('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
