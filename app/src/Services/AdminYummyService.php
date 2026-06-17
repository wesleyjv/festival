<?php

namespace App\Services;

use App\Database;
use App\Repositories\Interfaces\IAdminYummyRepository;
use App\Services\Interfaces\IAdminYummyService;
use PDO;
use PDOStatement;

/** Handles admin business logic for Yummy restaurants and their menu items. */
class AdminYummyService implements IAdminYummyService
{
    private PDO $connection;

    public function __construct(
        private readonly IAdminYummyRepository $adminYummyRepository
    ) {
        // Connection kept for saveMenuItem/deleteMenuItem and cuisine-tag methods (Groups C–D).
        $this->connection = Database::getConnection();
    }

    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function findAllRestaurantsForAdmin(): array
    {
        return $this->adminYummyRepository->findAllRestaurantsForAdmin();
    }

    /** Returns a single restaurant row, or null if the ID does not exist. */
    public function findRestaurantById(int $restaurantId): ?array
    {
        return $this->adminYummyRepository->findRestaurantById($restaurantId);
    }

    /** Inserts a new restaurant; slug is generated automatically from the name. Returns the new restaurant's ID. */
    public function createRestaurant(array $formData): int
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $data = $formData;
        $data['name']     = $name;
        $data['slug']     = $this->generateSlug($name);
        $data['about']    = $this->sanitizeRichText($formData['about']    ?? null);
        $data['chef_bio'] = $this->sanitizeRichText($formData['chef_bio'] ?? null);

        return $this->adminYummyRepository->createRestaurant($data);
    }

    /** Updates all editable columns on the restaurants row. */
    public function updateRestaurant(int $restaurantId, array $formData): void
    {
        $this->validateRestaurantFormData($formData);

        $name = trim($formData['restaurant_name'] ?? '');
        $data = $formData;
        $data['name']     = $name;
        $data['slug']     = $this->generateSlug($name);
        $data['about']    = $this->sanitizeRichText($formData['about']    ?? null);
        $data['chef_bio'] = $this->sanitizeRichText($formData['chef_bio'] ?? null);

        $this->adminYummyRepository->updateRestaurant($restaurantId, $data);
    }

    /** Deletes menu items and cuisine tags first to satisfy FK constraints, then deletes the restaurant. */
    public function deleteRestaurant(int $restaurantId): void
    {
        $this->adminYummyRepository->deleteRestaurant($restaurantId);
    }

    /** Flips the `active` flag between 0 and 1. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void
    {
        $this->adminYummyRepository->toggleRestaurantActiveStatus($restaurantId);
    }

    /** Returns all menu items for the given restaurant ordered by display_order. */
    public function findMenuItemsByRestaurantId(int $restaurantId): array
    {
        return $this->adminYummyRepository->findMenuItemsByRestaurantId($restaurantId);
    }

    /** Returns a single menu item row by its primary key, or null when not found. */
    public function findMenuItemById(int $itemId): ?array
    {
        return $this->adminYummyRepository->findMenuItemById($itemId);
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

    /** Converts a restaurant name to a URL-safe slug. Used by createRestaurant and updateRestaurant. */
    private function generateSlug(string $name): string
    {
        return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name)), '-');
    }

    private function bindNullableString(PDOStatement $stmt, string $param, ?string $value): void
    {
        if ($value === null || $value === '') {
            $stmt->bindValue($param, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
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
