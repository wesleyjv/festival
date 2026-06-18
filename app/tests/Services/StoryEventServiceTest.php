<?php

namespace App\Tests\Services;

use App\Repositories\StoryEventRepository;
use App\Services\Interfaces\IContentService;
use App\Services\StoryEventService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for StoryEventService.
 *
 * These exercise the service in isolation using lightweight test doubles for
 * the repository and content service — made possible by the constructor
 * injection on StoryEventService (no database required).
 */
final class StoryEventServiceTest extends TestCase
{
    public function testReturnsAllEventsWhenNoFiltersApplied(): void
    {
        $repo    = new FakeStoryEventRepository();
        $service = new StoryEventService($repo, new FakeContentService());

        $viewModel = $service->getStoriesOverviewViewModel([]);

        self::assertSame($repo->allEvents, $viewModel->events);
        self::assertSame($repo->allEvents, $viewModel->allEvents);
        self::assertNull(
            $repo->lastFilterArgs,
            'getFilteredEvents must not be called when no filters are set'
        );
    }

    public function testTreatsAllEmptyAndWhitespaceAsNoFilter(): void
    {
        $repo    = new FakeStoryEventRepository();
        $service = new StoryEventService($repo, new FakeContentService());

        $service->getStoriesOverviewViewModel([
            'day'      => 'all',
            'date'     => '',
            'time'     => '   ',
            'location' => null,
        ]);

        self::assertNull(
            $repo->lastFilterArgs,
            '"all"/empty/whitespace values should be normalised to no filter'
        );
    }

    public function testForwardsCombinedFiltersToRepository(): void
    {
        $repo    = new FakeStoryEventRepository();
        $service = new StoryEventService($repo, new FakeContentService());

        $service->getStoriesOverviewViewModel([
            'day'      => 'Friday',
            'time'     => 'evening',
            'location' => 'Grote Markt',
        ]);

        // [day, date, timeOfDay, location] — date omitted, so null.
        self::assertSame(['Friday', null, 'evening', 'Grote Markt'], $repo->lastFilterArgs);
    }
}

/** In-memory repository double; records the arguments passed to getFilteredEvents. */
final class FakeStoryEventRepository extends StoryEventRepository
{
    /** @var array<int,array<string,mixed>> */
    public array $allEvents = [
        ['id' => 1, 'title' => 'A'],
        ['id' => 2, 'title' => 'B'],
    ];

    /** @var array{0:?string,1:?string,2:?string,3:?string}|null */
    public ?array $lastFilterArgs = null;

    public function getEvents(): array
    {
        return $this->allEvents;
    }

    public function getFilteredEvents(
        ?string $day = null,
        ?string $date = null,
        ?string $timeOfDay = null,
        ?string $location = null
    ): array {
        $this->lastFilterArgs = [$day, $date, $timeOfDay, $location];

        return [];
    }

    public function getFeatured(): ?array
    {
        return null;
    }

    public function getLocations(): array
    {
        return [];
    }
}

/** Content service double — implements the interface so no DB/filesystem is touched. */
final class FakeContentService implements IContentService
{
    public function getPageContent(string $page): array
    {
        return [];
    }
}
