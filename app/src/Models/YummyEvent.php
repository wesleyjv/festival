<?php

namespace App\Models;

/** Represents a single restaurant record from the `restaurants` table. */
class YummyEvent
{
    public function __construct(
        public int $id,
        public string $restaurantName,
        public string $slug,
        public ?string $address,
        public ?string $shortDescription,
        public ?string $about,
        public ?int $adultPriceCents,
        public ?int $childPriceCents,
        public ?int $childMaxAge,
        public ?int $seats,
        public ?int $sessionCount,
        public ?int $sessionDurationMinutes,
        public ?string $sessionOneStartTime,
        public ?string $sessionTwoStartTime,
        public ?string $sessionThreeStartTime,
        public ?float $rating,
        public ?int $reviewCount,
        public ?string $restaurantImagePath,
        public ?string $chefImagePath,
        public ?string $chefName,
        public ?string $chefTitle,
        public ?string $chefBio,
        public ?string $aboutImagePath,
        public ?string $reservationImagePath,
        public array $cuisineTags = []
    ) {
    }
}
