<?php

namespace App\Services\Interfaces;

/** Contract for admin CRUD operations on Yummy restaurants and their menu items. */
interface IAdminYummyService
{
    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function findAllRestaurantsForAdmin(): array;

    /** Returns a single restaurant row, or null if the ID does not exist. */
    public function findRestaurantById(int $restaurantId): ?array;

    /** Inserts a new restaurant; slug is generated automatically from the name. Returns the new restaurant's ID. */
    public function createRestaurant(array $formData): int;

    /** Updates all editable columns on the restaurants row. */
    public function updateRestaurant(int $restaurantId, array $formData): void;

    /** Deletes menu items and cuisine tags first to satisfy FK constraints, then deletes the restaurant. */
    public function deleteRestaurant(int $restaurantId): void;

    /** Flips the `active` flag between 0 and 1. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void;

    /**
     * Returns all menu items for the given restaurant ordered by display_order.
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

    /** Inserts a new menu item when formData has no item_id, otherwise updates the existing one. */
    public function saveMenuItem(int $restaurantId, array $formData): void;

    /** Permanently removes a single menu item row. */
    public function deleteMenuItem(int $menuItemId): void;

    /**
     * Returns all cuisine tags for the CMS picker.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllCuisineTags(): array;

    /**
     * Returns the cuisine tag IDs currently assigned to the given restaurant.
     *
     * @return int[]
     */
    public function findCuisineTagIdsForRestaurant(int $restaurantId): array;

    /** Replaces the restaurant's cuisine tag assignments with the given tag IDs. */
    public function setCuisineTagsForRestaurant(int $restaurantId, array $tagIds): void;
}
