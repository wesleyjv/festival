<?php

namespace App\Services;

use App\Database;
use App\Services\Interfaces\IAdminYummyService;
use PDO;

/** Executes admin CRUD operations against the restaurants and yummy_event_restaurants tables. */
class AdminYummyService implements IAdminYummyService
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function getAllRestaurantsForEvent(int $eventId): array
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
                yer.active,
                GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
            FROM yummy_event_restaurants yer
            INNER JOIN restaurants r ON r.id = yer.restaurant_id
            LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
            LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
            WHERE yer.event_id = :event_id
            GROUP BY
                r.id, r.name, r.slug, r.description, r.address, r.image_path,
                yer.price, yer.rating, yer.active
            ORDER BY r.name ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Returns a single restaurant row joined with its event-link data, or null. */
    public function getRestaurantById(int $restaurantId): ?array
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
                yer.active
            FROM restaurants r
            LEFT JOIN yummy_event_restaurants yer ON yer.restaurant_id = r.id
            WHERE r.id = :id
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /** Inserts into `restaurants` then links the new row to the event in `yummy_event_restaurants`. */
    public function createRestaurant(int $eventId, array $formData): void
    {
        $name        = trim($formData['restaurant_name'] ?? '');
        $slug        = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name))), '-');
        $description = $formData['description'] ?? null;
        $address     = $formData['address']     ?? null;
        $imagePath   = $formData['image_path']  ?? null;
        $price       = isset($formData['price'])  && $formData['price'] !== '' ? (float) $formData['price']  : null;
        $rating      = isset($formData['rating']) && $formData['rating'] !== '' ? (float) $formData['rating'] : null;

        $stmt = $this->connection->prepare("
            INSERT INTO restaurants (name, slug, description, address, image_path)
            VALUES (:name, :slug, :description, :address, :image_path)
        ");
        $stmt->bindValue(':name',        $name,        PDO::PARAM_STR);
        $stmt->bindValue(':slug',        $slug,        PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, $description === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':address',     $address,     $address     === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':image_path',  $imagePath,   $imagePath   === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->execute();

        $restaurantId = (int) $this->connection->lastInsertId();

        $stmt = $this->connection->prepare("
            INSERT INTO yummy_event_restaurants (restaurant_id, event_id, price, rating, active)
            VALUES (:restaurant_id, :event_id, :price, :rating, 1)
        ");
        $stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindValue(':event_id',      $eventId,      PDO::PARAM_INT);
        $stmt->bindValue(':price',         $price,        $price  === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':rating',        $rating,       $rating === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->execute();
    }

    /** Updates both the `restaurants` row and the price/rating columns in `yummy_event_restaurants`. */
    public function updateRestaurant(int $restaurantId, array $formData): void
    {
        $name        = trim($formData['restaurant_name'] ?? '');
        $slug        = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name))), '-');
        $description = $formData['description'] ?? null;
        $address     = $formData['address']     ?? null;
        $imagePath   = $formData['image_path']  ?? null;
        $price       = isset($formData['price'])  && $formData['price'] !== '' ? (float) $formData['price']  : null;
        $rating      = isset($formData['rating']) && $formData['rating'] !== '' ? (float) $formData['rating'] : null;

        $stmt = $this->connection->prepare("
            UPDATE restaurants
            SET name = :name, slug = :slug, description = :description,
                address = :address, image_path = :image_path
            WHERE id = :id
        ");
        $stmt->bindValue(':name',        $name,        PDO::PARAM_STR);
        $stmt->bindValue(':slug',        $slug,        PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, $description === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':address',     $address,     $address     === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':image_path',  $imagePath,   $imagePath   === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':id',          $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->connection->prepare("
            UPDATE yummy_event_restaurants
            SET price = :price, rating = :rating
            WHERE restaurant_id = :id
        ");
        $stmt->bindValue(':price',  $price,  $price  === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':rating', $rating, $rating === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':id',     $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /** Deletes the event link before the restaurant row to satisfy the foreign key constraint. */
    public function deleteRestaurant(int $restaurantId): void
    {
        $stmt = $this->connection->prepare(
            'DELETE FROM yummy_event_restaurants WHERE restaurant_id = :id'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->connection->prepare(
            'DELETE FROM restaurants WHERE id = :id'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /** Reads the current `active` value and flips it to the opposite. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void
    {
        $stmt = $this->connection->prepare(
            'SELECT active FROM yummy_event_restaurants WHERE restaurant_id = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return;
        }

        $newActiveValue = 1 - (int) $row['active'];

        $stmt = $this->connection->prepare(
            'UPDATE yummy_event_restaurants SET active = :active WHERE restaurant_id = :id'
        );
        $stmt->bindValue(':active', $newActiveValue, PDO::PARAM_INT);
        $stmt->bindValue(':id',     $restaurantId,   PDO::PARAM_INT);
        $stmt->execute();
    }
}
