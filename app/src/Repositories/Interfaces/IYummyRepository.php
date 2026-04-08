<?php

namespace App\Repositories\Interfaces;

use App\Models\YummyEvent;

/** Contract for reading restaurant data scoped to a Yummy festival event. */
interface IYummyRepository
{
    /**
     * Returns only active restaurants — inactive ones are hidden from visitors.
     *
     * @return YummyEvent[]
     */
    public function findActiveRestaurantsByEventId(int $eventId, ?string $cuisineFilter = null): array;

    /**
     * Returns the distinct cuisine tag names present for the event, for use in the filter dropdown.
     *
     * @return string[]
     */
    public function findAvailableCuisinesByEventId(int $eventId): array;

    /** Looks up a restaurant by its URL slug; returns null when no match is found. */
    public function findActiveRestaurantBySlug(int $eventId, string $slug): ?YummyEvent;
}
