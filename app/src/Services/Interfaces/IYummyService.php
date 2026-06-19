<?php

namespace App\Services\Interfaces;

use App\Models\Ticket;
use App\ViewModels\ReservationOverviewViewModel;
use App\ViewModels\YummyDetailViewModel;
use App\ViewModels\YummyOverviewViewModel;

/** Contract for the public-facing Yummy page service. */
interface IYummyService
{
    /** Builds the overview view model, applying the cuisine filter when provided. */
    public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel;

    /**
     * Fetches the restaurant and its menu items by slug and assembles the detail view model.
     * Returns null when no active restaurant matches the slug.
     */
    public function getRestaurantDetailViewModel(string $slug): ?YummyDetailViewModel;

    /**
     * Looks up an active restaurant's URL slug by its primary key.
     * Returns null when not found or inactive — used to redirect back to the
     * restaurant detail page after a reservation error.
     */
    public function getRestaurantSlugById(int $restaurantId): ?string;

    /**
     * Returns the number of seats still available for individual reservations in the
     * given session, applying the same 90% capacity rule as the reservation flow.
     *
     * @throws \InvalidArgumentException for any invalid parameter.
     */
    public function getRemainingSeats(int $restaurantId, string $festivalDate, int $sessionNumber): int;

    /**
     * Validates the reservation parameters, fetches the restaurant, calculates session
     * times and totals, and returns an assembled ReservationOverviewViewModel.
     *
     * @throws \InvalidArgumentException when any required parameter is invalid.
     */
    public function buildReservationOverviewViewModel(array $params): ReservationOverviewViewModel;

    /**
     * Validates params, saves the reservation, creates a template ticket row, links it
     * to the reservation, and returns the loaded Ticket so the caller can add it to cart.
     *
     * Expected keys in $params: restaurant_id, session_number, festival_date,
     * adults, children, special_request, user_id (nullable).
     *
     * @throws \InvalidArgumentException when any required parameter is invalid.
     */
    public function createAndCartReservation(array $params): Ticket;
}
