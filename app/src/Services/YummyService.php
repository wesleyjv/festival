<?php

namespace App\Services;

use App\Enums\CuisineType;
use App\Models\YummyEvent;
use App\Repositories\Interfaces\IYummyRepository;
use App\Services\Interfaces\IYummyService;
use App\ViewModels\YummyOverviewViewModel;

class YummyService implements IYummyService
{
	private int $yummyEventId;

	public function __construct(
		private readonly IYummyRepository $yummyRepository,
		private readonly ContentService $contentService
	) {
		$config = require __DIR__ . '/../Config/yummy.php';
		$this->yummyEventId = $config['event_id'];
	}

	public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel
	{
		if ($selectedCuisine !== null && $selectedCuisine !== '' && !CuisineType::isValid($selectedCuisine)) {
			$selectedCuisine = null;
		}

		$content = $this->contentService->getPageContent('yummy');
		$restaurants = $this->yummyRepository->getAll($this->yummyEventId, $selectedCuisine);
		$cuisines = $this->yummyRepository->getAvailableCuisines($this->yummyEventId);

		return new YummyOverviewViewModel(
			content: $content,
			restaurants: $restaurants,
			cuisines: $cuisines,
			selectedCuisine: $selectedCuisine
		);
	}

	public function getRestaurantBySlug(string $slug): ?YummyEvent
	{
		return $this->yummyRepository->getBySlug($this->yummyEventId, $slug);
	}
}
