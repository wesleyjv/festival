<?php

namespace App\Services\Interfaces;

use App\ViewModels\StoriesOverviewViewModel;

/** Contract for the public-facing storytelling page service. */
interface IStoryEventService
{
    /**
     * Resolve all data needed for the stories overview page based on the
     * current query-string parameters. Recognised keys: day, date, time, location.
     *
     * @param array<string,string|null> $filters
     */
    public function getStoriesOverviewViewModel(array $filters): StoriesOverviewViewModel;

    /**
     * Resolve a single story event for its detail page, or null when no event
     * with that id exists.
     *
     * @return array<string,mixed>|null
     */
    public function getEventDetail(int $id): ?array;
}
