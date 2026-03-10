<?php
namespace App;

use PDO;

/**
 * Thin wrapper that delegates to Database so the entire application
 * shares a single PDO instance regardless of which class is used.
 */
class DB
{
    public static function getConnection(): PDO
    {
        return Database::getConnection();
    }
}
