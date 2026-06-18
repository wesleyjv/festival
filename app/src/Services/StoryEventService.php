<?php

namespace App\Services;

use App\Repositories\StoryEventRepository;
use App\Services\Interfaces\IContentService;
use App\Services\Interfaces\IStoryEventService;
use App\ViewModels\StoriesOverviewViewModel;

/**
 * Service layer for storytelling events.
 *
 * Encapsulates all coordination between the controller and the
 * underlying StoryEventRepository so the controller stays thin.
 */
final class StoryEventService implements IStoryEventService
{
    private StoryEventRepository $repository;
    private IContentService $contentService;

    /**
     * Dependencies are injectable so the service can be unit tested with
     * fakes/mocks; they default to concrete implementations for callers that
     * don't wire anything up.
     */
    public function __construct(
        ?StoryEventRepository $repository = null,
        ?IContentService $contentService = null
    ) {
        $this->repository     = $repository ?? new StoryEventRepository();
        $this->contentService = $contentService ?? new ContentService();
    }

    /**
     * Resolve all data needed for the stories overview page based on
     * the current query string parameters.
     *
     * @param array<string,string|null> $filters
     */
    public function getStoriesOverviewViewModel(array $filters): StoriesOverviewViewModel
    {
        $day      = $this->normalizeFilter($filters['day']      ?? null);
        $date     = $this->normalizeFilter($filters['date']     ?? null);
        $time     = $this->normalizeFilter($filters['time']     ?? null);
        $location = $this->normalizeFilter($filters['location'] ?? null);

        // Full list used for building filter options in the view.
        $allEvents = $this->repository->getEvents();

        // Filters combine with AND; any null filter is ignored.
        $events = ($day === null && $date === null && $time === null && $location === null)
            ? $allEvents
            : $this->repository->getFilteredEvents($day, $date, $time, $location);

        return new StoriesOverviewViewModel(
            events: $events,
            allEvents: $allEvents,
            featuredStoryteller: $this->repository->getFeatured() ?? [],
            locations: $this->repository->getLocations(),
            content: $this->contentService->getPageContent('stories')
        );
    }

    /**
     * Resolve a single story event for its detail page, or null when not found.
     *
     * @return array<string,mixed>|null
     */
    public function getEventDetail(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->repository->getEventById($id);
    }

    /**
     * Treat empty strings and the literal "all" sentinel as "no filter".
     */
    private function normalizeFilter(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return ($value === '' || $value === 'all') ? null : $value;
    }
}
