<?php

namespace App\Repositories;

use App\Database;
use PDO;

class YummyEventRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function getAll(?string $cuisine = null): array
    {
        $sql = "
            SELECT 
                e.id,
                e.name,
                e.description,
                e.image,
                ye.restaurant_name,
                ye.cuisine
            FROM yummy_events ye
            JOIN events e ON e.id = ye.event_id
            WHERE e.type = 'yummy'
        ";

        if ($cuisine) {
            $sql .= " AND ye.cuisine = :cuisine";
        }

        $sql .= " ORDER BY ye.restaurant_name ASC";

        $stmt = $this->connection->prepare($sql);

        if ($cuisine) {
            $stmt->bindParam(':cuisine', $cuisine);
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getDistinctCuisines(): array
    {
        $stmt = $this->connection->query("
            SELECT DISTINCT cuisine 
            FROM yummy_events 
            WHERE cuisine IS NOT NULL
        ");

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
