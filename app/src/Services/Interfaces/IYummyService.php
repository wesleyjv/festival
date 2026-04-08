<?php

namespace App\Services\Interfaces;

use App\ViewModels\YummyOverviewViewModel;
use App\Models\YummyEvent;

/** Contract for the public-facing Yummy page service. */
interface IYummyService
{
    /** Builds the view model for the overview page, applying the cuisine filter when provided. */
    public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel;

    /** Returns the restaurant matching the given URL slug, or null if not found or inactive. */
    public function getRestaurantBySlug(string $slug): ?YummyEvent;
}
