<?php

namespace App\Services\Interfaces;

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
     * Validates the reservation parameters, fetches the restaurant, calculates session
     * times and totals, and returns an assembled ReservationOverviewViewModel.
     *
     * @throws \InvalidArgumentException when any required parameter is invalid.
     */
    public function buildReservationOverviewViewModel(array $params): ReservationOverviewViewModel;

    /**
     * Validates all reservation parameters, calculates the reservation fee, and
     * persists the reservation via the repository.
     *
     * Expected keys in $params: restaurant_id, session_number, festival_date,
     * adults, children, special_request, user_id (nullable).
     *
     * @return int The newly created reservation's primary key.
     * @throws \InvalidArgumentException when any required parameter is invalid.
     */
    public function saveReservation(array $params): int;
}
