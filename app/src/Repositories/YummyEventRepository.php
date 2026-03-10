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
            r.id,
            r.name AS restaurant_name,
            r.slug,
            r.description,
            r.address,
            r.image_path,
            yer.price,
            yer.rating,
            GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
        FROM yummy_event_restaurants yer
        INNER JOIN restaurants r ON r.id = yer.restaurant_id
        LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
        LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
        WHERE yer.event_id = :event_id
          AND yer.active = 1
    ";

    if ($cuisine) {
        $sql .= " AND ct.name = :cuisine";
    }

    $sql .= "
        GROUP BY
            r.id,
            r.name,
            r.slug,
            r.description,
            r.address,
            r.image_path,
            yer.price,
            yer.rating
        ORDER BY r.name ASC
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue(':event_id', 97, \PDO::PARAM_INT);

    if ($cuisine) {
        $stmt->bindValue(':cuisine', $cuisine);
    }

    $stmt->execute();

    return $stmt->fetchAll();
}
}