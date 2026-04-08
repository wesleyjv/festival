<?php

namespace App\Services;

use App\Enums\CuisineType;
use App\Models\YummyEvent;
use App\Repositories\Interfaces\IYummyRepository;
use App\Services\Interfaces\IYummyService;
use App\ViewModels\YummyOverviewViewModel;

/** Assembles the view models needed by the public Yummy event pages. */
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

	/**
	 * Silently drops an unrecognised cuisine filter rather than showing an empty list or an error.
	 * CuisineType::isValid guards against arbitrary values being passed to the repository.
	 */
	public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel
	{
		if ($selectedCuisine !== null && $selectedCuisine !== '' && !CuisineType::isValid($selectedCuisine)) {
			$selectedCuisine = null;
		}

		$content     = $this->contentService->getPageContent('yummy');
		$restaurants = $this->yummyRepository->findActiveRestaurantsByEventId($this->yummyEventId, $selectedCuisine);
		$cuisines    = $this->yummyRepository->findAvailableCuisinesByEventId($this->yummyEventId);

		return new YummyOverviewViewModel(
			content: $content,
			restaurants: $restaurants,
			cuisines: $cuisines,
			selectedCuisine: $selectedCuisine
		);
	}

	/** Returns the restaurant matching the given URL slug, or null if not found or inactive. */
	public function getRestaurantBySlug(string $slug): ?YummyEvent
	{
		return $this->yummyRepository->findActiveRestaurantBySlug($this->yummyEventId, $slug);
	}
}
