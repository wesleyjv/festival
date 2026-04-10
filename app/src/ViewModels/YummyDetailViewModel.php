<?php

namespace App\ViewModels;

use App\Models\YummyEvent;

/** Carries the restaurant, its menu items, and CMS page content for the detail page. */
final readonly class YummyDetailViewModel
{
    public function __construct(
        public YummyEvent $restaurant,
        public array $menuItems,
        public array $pageContent
    ) {
    }
}
