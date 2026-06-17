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
}
