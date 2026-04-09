<?php

namespace App\Services\Interfaces;

use App\ViewModels\YummyOverviewViewModel;
use App\Models\YummyEvent;

interface IYummyService
{
    public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel;

    public function getRestaurantBySlug(string $slug): ?YummyEvent;
}