<?php

namespace App\Services;

/**
 * Provides festival configuration for the homepage (hero, about, CTA, footer).
 * Replace with database or env-based config in production.
 */
final class FestivalConfigService
{
    public function getFestivalTitle(): string
    {
        return 'The Haarlem Festival';
    }

    public function getTagline(): string
    {
        return 'Experience the ultimate celebration of music, art, and culture in the heart of Haarlem.';
    }

    public function getHeroBackgroundImageUrl(): string
    {
        return 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=1920';
    }

    public function getAboutText(): string
    {
        return 'The Haarlem Festival brings together artists, musicians, and food lovers for a multi-day celebration. From jazz nights and storytelling to historic walks and culinary experiences, there is something for everyone.';
    }

    public function getAboutImageUrl(): string
    {
        return 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800';
    }

    public function getCtaText(): string
    {
        return 'Explore our full program and plan your visit.';
    }

    public function getCtaButtonUrl(): string
    {
        return '/events';
    }

    /** @return list<array{label: string, url: string}> */
    public function getFooterLinks(): array
    {
        return [
            ['label' => 'About us', 'url' => '/about'],
            ['label' => 'Contact', 'url' => '/contact'],
            ['label' => 'FAQ', 'url' => '/faq'],
            ['label' => 'Terms & conditions', 'url' => '/terms'],
            ['label' => 'Privacy policy', 'url' => '/privacy'],
        ];
    }

    /**
     * Number of participating artists (for stats section).
     * Can be replaced by counting unique artists from events.
     */
    public function getParticipatingArtistCount(): int
    {
        return 48;
    }

    /** @return list<array{name: string, url: string, icon: string}> */
    public function getFooterSocialUrls(): array
    {
        return [
            ['name' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'instagram'],
            ['name' => 'Facebook', 'url' => 'https://facebook.com', 'icon' => 'facebook'],
            ['name' => 'Twitter', 'url' => 'https://twitter.com', 'icon' => 'twitter'],
            ['name' => 'YouTube', 'url' => 'https://youtube.com', 'icon' => 'youtube'],
        ];
    }
}
