<?php

namespace App\Services;

use App\Enums\CuisineType;
use App\Models\YummyEvent;
use App\Repositories\Interfaces\IYummyRepository;
use App\Services\Interfaces\IYummyService;
use App\ViewModels\YummyOverviewViewModel;

class YummyService implements IYummyService
{
	private const YUMMY_EVENT_ID = 97;

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
		$restaurants = $this->yummyRepository->getAll(self::YUMMY_EVENT_ID, $selectedCuisine);
		$cuisines = $this->yummyRepository->getAvailableCuisines(self::YUMMY_EVENT_ID);

		return new YummyOverviewViewModel(
			content: $content,
			restaurants: $restaurants,
			cuisines: $cuisines,
			selectedCuisine: $selectedCuisine
		);
	}

	public function getRestaurantBySlug(string $slug): ?YummyEvent
	{
		return $this->yummyRepository->getBySlug(self::YUMMY_EVENT_ID, $slug);
	}
}
