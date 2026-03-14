<?php

namespace App\Models;

class YummyEvent
{
    public function __construct(
        public int $id,
        public string $restaurantName,
        public string $slug,
        public ?string $description,
        public ?string $address,
        public ?string $imagePath,
        public ?float $price,
        public ?float $rating,
        public array $cuisineTags = []
    ) {
    }
}