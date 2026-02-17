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
            // Load environment variables from .env file
            $envFile = __DIR__ . '/../../../.env';
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos($line, '#') === 0) continue; // Skip comments
                    if (strpos($line, '=') === false) continue; // Skip invalid lines
                    
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    $_ENV[$key] = $value;
                }
            }
            
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
