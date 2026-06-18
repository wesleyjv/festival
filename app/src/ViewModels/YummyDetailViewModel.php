<?php

namespace App\ViewModels;

use App\Models\YummyEvent;

/** Carries the restaurant and its menu items for the detail page. */
final readonly class YummyDetailViewModel
{
    public function __construct(
        public YummyEvent $restaurant,
        public array $menuItems
    ) {
    }
}
