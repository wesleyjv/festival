<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = getenv('DB_HOST') ?: 'mysql';
            $port = getenv('DB_PORT') ?: '3306';
            $database = getenv('DB_DATABASE') ?: 'defaultdb';
            $username = getenv('DB_USERNAME') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: '';

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                $hint = '';
                if (strpos($msg, 'getaddrinfo') !== false || strpos($msg, '2002') !== false) {
                    $hint = ' (Host name could not be resolved: check DB_HOST in your environment, internet/VPN, '
                        . 'and that the database service is running. In Docker, ensure the container can reach the host '
                        . 'or use a reachable hostname such as host.docker.internal for a DB on your machine.)';
                }
                throw new PDOException('Database connection failed: ' . $msg . $hint, (int) $e->getCode());
            }
        }

        return self::$connection;
    }
}
