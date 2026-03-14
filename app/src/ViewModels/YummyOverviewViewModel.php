<?php

namespace App\ViewModels;

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