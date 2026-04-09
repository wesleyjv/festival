<?php

namespace App\ViewModels;

final readonly class StoriesOverviewViewModel
{
    /**
     * @param array<int,array<string,mixed>> $events
     * @param array<int,array<string,mixed>> $allEvents
     * @param array<string,mixed> $featuredStoryteller
     * @param array<int,array<string,mixed>> $locations
     * @param array<string,mixed> $content
     */
    public function __construct(
        public array $events,
        public array $allEvents,
        public array $featuredStoryteller,
        public array $locations,
        public array $content
    ) {
    }
}
