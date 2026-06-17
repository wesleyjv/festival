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

    /**
     * Inserts a template ticket row into the tickets table and returns the new primary key.
     *
     * Expected keys in $ticketData: name (string), price (float)
     * Inserts with event_id = 0, order_id = NULL, user_id = NULL, ticket_code = ''
     */
    public function createReservationTicket(array $ticketData): int;

    /**
     * Sets yummy_reservations.ticket_id for the given reservation row.
     */
    public function updateReservationTicketId(int $reservationId, int $ticketId): void;

    /**
     * Returns SUM(adults + children) across non-cancelled reservations for the given
     * restaurant/date/session. Returns 0 when there are none.
     */
    public function countReservedSeatsForSession(int $restaurantId, string $festivalDate, int $sessionNumber): int;
}
