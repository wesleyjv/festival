<?php

namespace App\Dto;

/**
 * Data transfer object for a featured event on the homepage.
 * Populated from backend (repository); do not hardcode in views.
 */
final class FeaturedEventDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $date,
        public string $location,
        public string $imageUrl,
        public string $detailsUrl,
        public string $description = '',
    ) {
    }
}
