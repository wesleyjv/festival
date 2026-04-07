<?php

namespace App\ViewModels;

use App\Models\YummyEvent;

final readonly class YummyDetailViewModel
{
    public function __construct(
        public YummyEvent $restaurant,
        public array $pageContent
    ) {
    }
}
