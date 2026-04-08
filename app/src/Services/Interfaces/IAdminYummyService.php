<?php

namespace App\Services\Interfaces;

/** Contract for admin operations on Yummy restaurants and their event links. */
interface IAdminYummyService
{
    /** Includes inactive restaurants so the admin can see and reactivate them. */
    public function getAllRestaurantsForEvent(int $eventId): array;

    /** Returns a single restaurant row joined with its event-link data, or null if not found. */
    public function getRestaurantById(int $restaurantId): ?array;

    /** Inserts into `restaurants` then links the new row to the event in `yummy_event_restaurants`. */
    public function createRestaurant(int $eventId, array $formData): void;

    /** Updates both the `restaurants` row and the price/rating columns in `yummy_event_restaurants`. */
    public function updateRestaurant(int $restaurantId, array $formData): void;

    /** Deletes the event link before the restaurant row to satisfy the foreign key constraint. */
    public function deleteRestaurant(int $restaurantId): void;

    /** Flips the `active` flag between 0 and 1 in `yummy_event_restaurants`. */
    public function toggleRestaurantActiveStatus(int $restaurantId): void;
}
