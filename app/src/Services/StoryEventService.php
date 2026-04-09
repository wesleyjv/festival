<?php

namespace App\Services;

use App\Repositories\StoryEventRepository;
use App\ViewModels\StoriesOverviewViewModel;

/**
 * Service layer for storytelling events.
 *
 * Encapsulates all coordination between the controller and the
 * underlying StoryEventRepository so the controller stays thin.
 */
final class StoryEventService
{
    private StoryEventRepository $repository;
    private ContentService $contentService;

    public function __construct()
    {
        $this->repository = new StoryEventRepository();
        $this->contentService = new ContentService();
    }

    /**
     * Resolve all data needed for the stories overview page based on
     * the current query string parameters.
     *
     * @param array<string,string|null> $filters
     */
    public function getStoriesOverviewViewModel(array $filters): StoriesOverviewViewModel
    {
        $dayFilter      = $filters['day']      ?? null;
        $dateFilter     = $filters['date']     ?? null;
        $timeFilter     = $filters['time']     ?? null;
        $locationFilter = $filters['location'] ?? null;

        // Normalize "all" to null so it behaves like no filter
        if ($dayFilter === 'all') {
            $dayFilter = null;
        }
        if ($dateFilter === 'all') {
            $dateFilter = null;
        }
        if ($timeFilter === 'all') {
            $timeFilter = null;
        }
        if ($locationFilter === 'all') {
            $locationFilter = null;
        }

        // Full list used for building filter options in the view
        $allEvents = $this->repository->getEvents();

        // Filtered list for the actual cards
        if ($dayFilter !== null && $dayFilter !== '') {
            $events = $this->repository->getEventsByDay($dayFilter);
        } elseif ($dateFilter !== null && $dateFilter !== '') {
            $events = $this->repository->getEventsByDate($dateFilter);
        } elseif ($timeFilter !== null && $timeFilter !== '') {
            $events = $this->repository->getEventsByTime($timeFilter);
        } elseif ($locationFilter !== null && $locationFilter !== '') {
            $events = $this->repository->getEventsByLocation($locationFilter);
        } else {
            $events = $allEvents;
        }

        $featuredStoryteller = $this->repository->getFeatured() ?? [];
        $locations           = $this->repository->getLocations();
        $content             = $this->contentService->getPageContent('stories');

        return new StoriesOverviewViewModel(
            events: $events,
            allEvents: $allEvents,
            featuredStoryteller: $featuredStoryteller,
            locations: $locations,
            content: $content
        );
    }
}

