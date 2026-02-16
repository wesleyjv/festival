<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = $_ENV['DB_HOST'] ?? 'haarlem-festival-dev-haarlemfestival123.i.aivencloud.com';
            $port = $_ENV['DB_PORT'] ?? '17152';
            $dbname = $_ENV['DB_DATABASE'] ?? 'defaultdb';
            $username = $_ENV['DB_USERNAME'] ?? 'avnadmin';
            $password = $_ENV['DB_PASSWORD'] ?? 'AVNS_SzVg-JB6UsaKcS1pDWY';
            
            try {
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
}
