<?php

namespace App\Services;

use App\Config\FestivalEventConfig;
use App\Enums\CuisineType;
use App\Models\YummyEvent;
use App\Repositories\Interfaces\IYummyRepository;
use App\Services\Interfaces\IYummyService;
use App\ViewModels\YummyOverviewViewModel;

class YummyService implements IYummyService
{
	public function __construct(
		private readonly IYummyRepository $yummyRepository,
		private readonly ContentService $contentService
	) {
	}

	public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel
	{
		if ($selectedCuisine !== null && $selectedCuisine !== '' && !CuisineType::isValid($selectedCuisine)) {
			$selectedCuisine = null;
		}

		$content = $this->contentService->getPageContent('yummy');
		$eid = FestivalEventConfig::yummyEventId();
		$restaurants = $this->yummyRepository->getAll($eid, $selectedCuisine);
		$cuisines = $this->yummyRepository->getAvailableCuisines($eid);

		return new YummyOverviewViewModel(
			content: $content,
			restaurants: $restaurants,
			cuisines: $cuisines,
			selectedCuisine: $selectedCuisine
		);
	}

	public function getRestaurantBySlug(string $slug): ?YummyEvent
	{
		return $this->yummyRepository->getBySlug(FestivalEventConfig::yummyEventId(), $slug);
	}
}
