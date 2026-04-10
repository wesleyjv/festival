<?php

namespace App\Models;

/** Represents a single item from the `restaurant_menu_items` table. */
class YummyMenuItem
{
    public function __construct(
        public int $id,
        public int $restaurantId,
        public string $name,
        public ?string $description,
        public ?string $imagePath,
        public int $displayOrder
    ) {
    }
}
