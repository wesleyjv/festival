<?php

namespace App\ViewModels;

/** Carries all data the Yummy overview page needs — restaurants, filter state, and CMS content. */
final readonly class YummyOverviewViewModel
{
    public function __construct(
        public array $content,
        public array $restaurants,
        public array $cuisines,
        public ?string $selectedCuisine
    ) {
    }
}
