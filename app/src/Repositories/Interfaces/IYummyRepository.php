<?php

namespace App\Repositories\Interfaces;

use App\Models\YummyEvent;

interface IYummyRepository
{
    /**
     * @return YummyEvent[]
     */
    public function getAll(int $eventId, ?string $cuisine = null): array;

    /**
     * @return string[]
     */
    public function getAvailableCuisines(int $eventId): array;

    public function getBySlug(int $eventId, string $slug): ?YummyEvent;
}