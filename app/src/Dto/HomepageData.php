<?php

namespace App\Dto;

/**
 * Data model for the festival homepage. All fields are loaded from the backend.
 * Used by HomeController and passed to the view for dynamic rendering.
 *
 * @see \App\Controllers\HomeController::home()
 */
final class HomepageData
{
    /** @param list<FeaturedEventDto> $featuredEvents */
    public function __construct(
        public string $festivalTitle,
        public string $tagline,
        public string $heroBackgroundImageUrl,
        public string $aboutText,
        public string $aboutImageUrl,
        public array $featuredEvents,
        public int $statsEventCount,
        public int $statsTicketsSold,
        public int $statsArtistCount,
        public string $ctaText,
        public string $ctaButtonUrl,
        /** @var list<array{label: string, url: string}> */
        public array $footerLinks,
        /** @var list<array{name: string, url: string, icon: string}> */
        public array $footerSocialUrls,
    ) {
    }
}
