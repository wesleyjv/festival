<?php

namespace App\Repositories\Interfaces;

/** Contract for admin data access against the restaurants and restaurant_menu_items tables. */
interface IAdminYummyRepository
{
    /**
     * Returns all restaurants (including inactive) for the admin list view,
     * ordered by name. Each row contains only the columns needed for the list.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllRestaurantsForAdmin(): array;

    /**
     * Returns a single restaurant row with all editable fields, or null when not found.
     *
     * @return array<string, mixed>|null
     */
    public function findRestaurantById(int $restaurantId): ?array;

    /**
     * Returns all menu items for the given restaurant, ordered by display_order.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findMenuItemsByRestaurantId(int $restaurantId): array;

    /**
     * Returns a single menu item row by its primary key, or null when not found.
     *
     * @return array<string, mixed>|null
     */
    public function findMenuItemById(int $itemId): ?array;

    /**
     * Inserts a new restaurant row and returns the new primary key.
     *
     * Expected keys in $data: name, slug, address, short_description, about,
     * adult_price_cents, child_price_cents, child_max_age, seats, session_count,
     * session_duration_minutes, session_one_start_time, session_two_start_time,
     * session_three_start_time, rating, review_count, restaurant_image_path,
     * chef_image_path, chef_name, chef_title, chef_bio, about_image_path,
     * reservation_image_path. Rich-text fields (about, chef_bio) must already be
     * sanitized by the caller.
     */
    public function createRestaurant(array $data): int;

    /**
     * Updates all editable columns for the given restaurant.
     * $data has the same keys as createRestaurant, with rich-text pre-sanitized.
     */
    public function updateRestaurant(int $restaurantId, array $data): void;

    /**
     * Removes the restaurant and all its menu-item and cuisine-tag rows to satisfy
     * FK constraints.
     */
    public function deleteRestaurant(int $restaurantId): void;

    /** Flips the `active` flag between 0 and 1 for the given restaurant. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void;

    /**
     * Inserts a new menu item row when $data has no item_id, otherwise updates the
     * existing row. $data keys: item_id (optional), name, description (nullable,
     * pre-sanitized), image_path (nullable), display_order.
     */
    public function saveMenuItem(int $restaurantId, array $data): void;

    /** Permanently removes a single menu item row. */
    public function deleteMenuItem(int $menuItemId): void;

    /**
     * Returns all cuisine tags for the CMS picker, ordered by name.
     *
     * @return array<int, array{id: int, name: string}>
     */
    public function findAllCuisineTags(): array;

    /**
     * Returns the cuisine tag IDs currently assigned to the given restaurant.
     *
     * @return int[]
     */
    public function findCuisineTagIdsForRestaurant(int $restaurantId): array;

    /**
     * Replaces the restaurant's cuisine tag assignments with the given tag IDs.
     * Deletes existing rows then inserts new ones.
     *
     * @param int[] $tagIds
     */
    public function setCuisineTagsForRestaurant(int $restaurantId, array $tagIds): void;
}
