<?php

namespace App\Repositories\Interfaces;

use App\Models\YummyEvent;
use App\Models\YummyMenuItem;

/** Contract for reading restaurant data from the `restaurants` table. */
interface IYummyRepository
{
    /**
     * Returns only active restaurants — inactive ones are hidden from visitors.
     *
     * @return YummyEvent[]
     */
    public function findAllActiveRestaurants(?string $cuisineFilter = null): array;

    /**
     * Returns the distinct cuisine tag names present across active restaurants, for the filter dropdown.
     *
     * @return string[]
     */
    public function findAllAvailableCuisines(): array;

    /** Looks up an active restaurant by its URL slug; returns null when not found or inactive. */
    public function findActiveRestaurantBySlug(string $slug): ?YummyEvent;

    /** Looks up an active restaurant by its primary key; returns null when not found or inactive. */
    public function findRestaurantById(int $restaurantId): ?YummyEvent;

    /**
     * Returns all menu items for the given restaurant, ordered by display_order.
     *
     * @return YummyMenuItem[]
     */
    public function findMenuItemsByRestaurantId(int $restaurantId): array;

    /**
     * Inserts a row into yummy_reservations and returns the new primary key.
     *
     * Expected keys in $data:
     *   restaurant_id, session_number, festival_date, adults, children,
     *   special_request (nullable string), reservation_fee_cents, user_id (nullable int)
     */
    public function saveReservation(array $data): int;
}
