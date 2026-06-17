<?php

namespace App\Services;

use App\Database;
use App\Services\Interfaces\IAdminYummyService;
use PDO;
use PDOStatement;

/** Executes admin CRUD operations against the restaurants and restaurant_menu_items tables. */
class AdminYummyService implements IAdminYummyService
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function findAllRestaurantsForAdmin(): array
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                name AS restaurant_name,
                slug,
                address,
                short_description,
                adult_price_cents,
                child_price_cents,
                rating,
                active
            FROM restaurants
            ORDER BY name ASC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Returns a single restaurant row, or null if the ID does not exist. */
    public function findRestaurantById(int $restaurantId): ?array
    {
        $stmt = $this->connection->prepare("
            SELECT
                id,
                name AS restaurant_name,
                slug,
                address,
                short_description,
                about,
                adult_price_cents,
                child_price_cents,
                child_max_age,
                seats,
                session_count,
                session_duration_minutes,
                session_one_start_time,
                session_two_start_time,
                session_three_start_time,
                rating,
                review_count,
                restaurant_image_path,
                chef_image_path,
                chef_name,
                chef_title,
                chef_bio,
                about_image_path,
                reservation_image_path,
                active
            FROM restaurants
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /** Inserts a new restaurant; slug is generated automatically from the name. Returns the new restaurant's ID. */
    public function createRestaurant(array $formData): int
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)), '-');

        $stmt = $this->connection->prepare("
            INSERT INTO restaurants (
                name, slug, address, short_description, about,
                adult_price_cents, child_price_cents, child_max_age,
                seats, session_count, session_duration_minutes,
                session_one_start_time, session_two_start_time, session_three_start_time,
                rating, review_count,
                restaurant_image_path, chef_image_path, chef_name, chef_title, chef_bio,
                about_image_path, reservation_image_path, active
            ) VALUES (
                :name, :slug, :address, :short_description, :about,
                :adult_price_cents, :child_price_cents, :child_max_age,
                :seats, :session_count, :session_duration_minutes,
                :session_one_start_time, :session_two_start_time, :session_three_start_time,
                :rating, :review_count,
                :restaurant_image_path, :chef_image_path, :chef_name, :chef_title, :chef_bio,
                :about_image_path, :reservation_image_path, 1
            )
        ");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $this->bindAllEditableFields($stmt, $formData);
        $stmt->execute();

        return (int) $this->connection->lastInsertId();
    }

    /** Updates all editable columns on the restaurants row. */
    public function updateRestaurant(int $restaurantId, array $formData): void
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)), '-');

        $stmt = $this->connection->prepare("
            UPDATE restaurants SET
                name = :name,
                slug = :slug,
                address = :address,
                short_description = :short_description,
                about = :about,
                adult_price_cents = :adult_price_cents,
                child_price_cents = :child_price_cents,
                child_max_age = :child_max_age,
                seats = :seats,
                session_count = :session_count,
                session_duration_minutes = :session_duration_minutes,
                session_one_start_time = :session_one_start_time,
                session_two_start_time = :session_two_start_time,
                session_three_start_time = :session_three_start_time,
                rating = :rating,
                review_count = :review_count,
                restaurant_image_path = :restaurant_image_path,
                chef_image_path = :chef_image_path,
                chef_name = :chef_name,
                chef_title = :chef_title,
                chef_bio = :chef_bio,
                about_image_path = :about_image_path,
                reservation_image_path = :reservation_image_path
            WHERE id = :id
        ");

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $this->bindAllEditableFields($stmt, $formData);
        $stmt->execute();
    }

    /** Deletes menu items and cuisine tags first to satisfy FK constraints, then deletes the restaurant. */
    public function deleteRestaurant(int $restaurantId): void
    {
        $stmt = $this->connection->prepare(
            'DELETE FROM restaurant_menu_items WHERE restaurant_id = :id'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->connection->prepare(
            'DELETE FROM restaurant_cuisine_tags WHERE restaurant_id = :id'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->connection->prepare(
            'DELETE FROM restaurants WHERE id = :id'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /** Flips the `active` flag between 0 and 1. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void
    {
        $stmt = $this->connection->prepare(
            'SELECT active FROM restaurants WHERE id = :id LIMIT 1'
        );
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return;
        }

        $newActiveValue = 1 - (int) $row['active'];

        $stmt = $this->connection->prepare(
            'UPDATE restaurants SET active = :active WHERE id = :id'
        );
        $stmt->bindValue(':active', $newActiveValue, PDO::PARAM_INT);
        $stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /** Returns all menu items for the given restaurant ordered by display_order. */
    public function findMenuItemsByRestaurantId(int $restaurantId): array
    {
        $stmt = $this->connection->prepare("
            SELECT id, restaurant_id, name, description, image_path, display_order
            FROM restaurant_menu_items
            WHERE restaurant_id = :restaurant_id
            ORDER BY display_order ASC
        ");
        $stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Inserts a new menu item when formData has no item_id, otherwise updates the existing one. */
    public function saveMenuItem(int $restaurantId, array $formData): void
    {
        if (trim($formData['name'] ?? '') === '') {
            throw new \InvalidArgumentException('Menu item name is required.');
        }

        $itemId       = isset($formData['item_id']) && (int) $formData['item_id'] > 0
                        ? (int) $formData['item_id']
                        : null;
        $name         = trim($formData['name'] ?? '');
        $description  = $this->sanitizeRichText($formData['description'] ?? null);
        $imagePath    = $formData['image_path']   !== '' ? ($formData['image_path']   ?? null) : null;
        $displayOrder = isset($formData['display_order']) && $formData['display_order'] !== ''
                        ? (int) $formData['display_order']
                        : 0;

        if ($itemId !== null) {
            $stmt = $this->connection->prepare("
                UPDATE restaurant_menu_items
                SET name = :name, description = :description,
                    image_path = :image_path, display_order = :display_order
                WHERE id = :id AND restaurant_id = :restaurant_id
            ");
            $stmt->bindValue(':id', $itemId, PDO::PARAM_INT);
        } else {
            $stmt = $this->connection->prepare("
                INSERT INTO restaurant_menu_items
                    (restaurant_id, name, description, image_path, display_order)
                VALUES
                    (:restaurant_id, :name, :description, :image_path, :display_order)
            ");
        }

        $stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->bindValue(':name',          $name,         PDO::PARAM_STR);
        $stmt->bindValue(':display_order', $displayOrder, PDO::PARAM_INT);
        $this->bindNullableString($stmt, ':description', $description);
        $this->bindNullableString($stmt, ':image_path',  $imagePath);
        $stmt->execute();
    }

    /** Permanently removes a single menu item row. */
    public function deleteMenuItem(int $menuItemId): void
    {
        $stmt = $this->connection->prepare(
            'DELETE FROM restaurant_menu_items WHERE id = :id'
        );
        $stmt->bindValue(':id', $menuItemId, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Binds all nullable editable fields shared between createRestaurant and updateRestaurant.
     * Extracted to avoid duplicating 20 identical bindValue calls in both methods.
     */
    private function bindAllEditableFields(PDOStatement $stmt, array $formData): void
    {
        $this->bindNullableString($stmt, ':address',              $formData['address']              ?? null);
        $this->bindNullableString($stmt, ':short_description',    $formData['short_description']    ?? null);
        $this->bindNullableString($stmt, ':about',                $this->sanitizeRichText($formData['about'] ?? null));
        $this->bindNullableString($stmt, ':session_one_start_time',   $formData['session_one_start_time']   ?? null);
        $this->bindNullableString($stmt, ':session_two_start_time',   $formData['session_two_start_time']   ?? null);
        $this->bindNullableString($stmt, ':session_three_start_time', $formData['session_three_start_time'] ?? null);
        $this->bindNullableString($stmt, ':restaurant_image_path',    $formData['restaurant_image_path']    ?? null);
        $this->bindNullableString($stmt, ':chef_image_path',      $formData['chef_image_path']      ?? null);
        $this->bindNullableString($stmt, ':chef_name',            $formData['chef_name']            ?? null);
        $this->bindNullableString($stmt, ':chef_title',           $formData['chef_title']           ?? null);
        $this->bindNullableString($stmt, ':chef_bio',             $this->sanitizeRichText($formData['chef_bio'] ?? null));
        $this->bindNullableString($stmt, ':about_image_path',     $formData['about_image_path']     ?? null);
        $this->bindNullableString($stmt, ':reservation_image_path', $formData['reservation_image_path'] ?? null);

        $this->bindNullableInt($stmt, ':adult_price_cents',      $formData['adult_price_cents']      ?? null);
        $this->bindNullableInt($stmt, ':child_price_cents',      $formData['child_price_cents']      ?? null);
        $this->bindNullableInt($stmt, ':child_max_age',          $formData['child_max_age']          ?? null);
        $this->bindNullableInt($stmt, ':seats',                  $formData['seats']                  ?? null);
        $this->bindNullableInt($stmt, ':session_count',          $formData['session_count']          ?? null);
        $this->bindNullableInt($stmt, ':session_duration_minutes', $formData['session_duration_minutes'] ?? null);
        $this->bindNullableInt($stmt, ':review_count',           $formData['review_count']           ?? null);

        $ratingRaw = $formData['rating'] ?? null;
        if ($ratingRaw === null || $ratingRaw === '') {
            $stmt->bindValue(':rating', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':rating', (float) $ratingRaw, PDO::PARAM_STR);
        }
    }

    private function bindNullableString(PDOStatement $stmt, string $param, ?string $value): void
    {
        if ($value === null || $value === '') {
            $stmt->bindValue($param, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
    }

    private function bindNullableInt(PDOStatement $stmt, string $param, mixed $value): void
    {
        if ($value === null || $value === '') {
            $stmt->bindValue($param, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue($param, (int) $value, PDO::PARAM_INT);
        }
    }

    private function validateRestaurantFormData(array $formData): void
    {
        if (trim($formData['restaurant_name'] ?? '') === '') {
            throw new \InvalidArgumentException('Restaurant name is required.');
        }

        foreach (['adult_price_cents', 'child_price_cents'] as $field) {
            $val = $formData[$field] ?? '';
            if ($val !== '' && (!is_numeric($val) || (float) $val < 0)) {
                throw new \InvalidArgumentException('Prices must be a number of 0 or more.');
            }
        }

        foreach (['seats', 'session_count', 'session_duration_minutes'] as $field) {
            $val = $formData[$field] ?? '';
            if ($val !== '' && filter_var($val, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
                throw new \InvalidArgumentException('Seats and session values must be 0 or more.');
            }
        }
    }

    /** Strips disallowed tags from TinyMCE output before storage; returns null for empty input. */
    private function sanitizeRichText(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return null;
        }

        $clean = strip_tags(
            $html,
            '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><blockquote><a>'
        );

        return $clean !== '' ? $clean : null;
    }

    /** Returns all cuisine tags for the CMS picker. */
    public function findAllCuisineTags(): array
    {
        $stmt = $this->connection->prepare('SELECT id, name FROM cuisine_tags ORDER BY name ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Returns the cuisine tag IDs currently assigned to the given restaurant. */
    public function findCuisineTagIdsForRestaurant(int $restaurantId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT tag_id FROM restaurant_cuisine_tags WHERE restaurant_id = :restaurant_id'
        );
        $stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /** Replaces the restaurant's cuisine tag assignments with the given tag IDs. */
    public function setCuisineTagsForRestaurant(int $restaurantId, array $tagIds): void
    {
        $deleteStmt = $this->connection->prepare(
            'DELETE FROM restaurant_cuisine_tags WHERE restaurant_id = :restaurant_id'
        );
        $deleteStmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
        $deleteStmt->execute();

        $insertStmt = $this->connection->prepare(
            'INSERT INTO restaurant_cuisine_tags (restaurant_id, tag_id) VALUES (:restaurant_id, :tag_id)'
        );

        foreach ($tagIds as $tagId) {
            $insertStmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
            $insertStmt->bindValue(':tag_id', (int) $tagId, PDO::PARAM_INT);
            $insertStmt->execute();
        }
    }
}
