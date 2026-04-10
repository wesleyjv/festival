<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Single source of truth for editable JSON-backed CMS pages (admin Content tab).
 * Jazz uses DB + a dedicated partial; it is registered here only for tab order/label.
 */
final class CmsPageDefinitions
{
    /**
     * @return array<string, array{label: string, storage: string, fields?: list<array<string, mixed>>, partial?: string}>
     */
    public static function pages(): array
    {
        return [
            'homepage' => [
                'label' => 'Homepage',
                'storage' => 'json',
                'fields' => [
                    ['name' => 'hero_title', 'label' => 'Hero title (supports HTML)', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'cta_heading', 'label' => 'CTA heading', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'cta_text', 'label' => 'CTA text', 'type' => 'wysiwyg', 'rows' => 3],
                ],
            ],
            'stories' => [
                'label' => 'Story',
                'storage' => 'json',
                'fields' => [
                    ['name' => 'hero_image', 'label' => 'Hero background image URL', 'type' => 'image'],
                    ['name' => 'featured_image', 'label' => 'Featured storyteller image URL', 'type' => 'image'],
                    ['name' => 'hero_title', 'label' => 'Hero title', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'hero_description', 'label' => 'Hero description', 'type' => 'wysiwyg', 'rows' => 5],
                    ['name' => 'info_paragraph', 'label' => 'Additional information text', 'type' => 'wysiwyg', 'rows' => 4],
                ],
            ],
            'yummy' => [
                'label' => 'Yummy',
                'storage' => 'json',
                'fields' => [
                    ['name' => 'hero_image', 'label' => 'Hero background image URL', 'type' => 'image'],
                    ['name' => 'hero_date', 'label' => 'Hero date line', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'hero_title', 'label' => 'Hero title', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'explore_heading', 'label' => 'Explore section heading', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'explore_text', 'label' => 'Explore section text', 'type' => 'wysiwyg', 'rows' => 3],
                    ['name' => 'feature_card_one_title', 'label' => 'Feature card 1: title', 'type' => 'text'],
                    ['name' => 'feature_card_one_text', 'label' => 'Feature card 1: text', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'feature_card_two_title', 'label' => 'Feature card 2: title', 'type' => 'text'],
                    ['name' => 'feature_card_two_text', 'label' => 'Feature card 2: text', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'feature_card_three_title', 'label' => 'Feature card 3: title', 'type' => 'text'],
                    ['name' => 'feature_card_three_text', 'label' => 'Feature card 3: text', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'intro_heading', 'label' => 'Intro heading', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'intro_text', 'label' => 'Intro text', 'type' => 'wysiwyg', 'rows' => 3],
                ],
            ],
            'history' => [
                'label' => 'History',
                'storage' => 'json',
                'fields' => [
                    ['name' => 'hero_title', 'label' => 'Hero title', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'hero_description', 'label' => 'Hero description', 'type' => 'wysiwyg', 'rows' => 3],
                ],
            ],
            'jazz' => [
                'label' => 'Jazz',
                'storage' => 'jazz_partial',
                'partial' => __DIR__ . '/../views/admin/partials/jazz-cms.php',
            ],
        ];
    }

    /**
     * Default string values for JSON pages (used when no saved file exists).
     *
     * @return array<string, string>
     */
    public static function defaultStringsForPage(string $page): array
    {
        $defaults = [
            'homepage' => [
                'hero_title' => "Welcome to<br>The Haarlem Festival",
                'hero_subtitle' => 'Discover the best of food, music, stories, and history in the heart of Haarlem.',
                'cta_heading' => 'Ready to experience Haarlem?',
                'cta_text' => 'Secure your spot before events sell out.',
            ],
            'stories' => [
                'hero_title' => 'The Art of <span class="highlight">Storytelling</span>',
                'hero_description' => 'Experience the magic of oral tradition as master storytellers weave tales that transport you through time and imagination. From ancient myths to contemporary narratives, discover the power of stories that connect us all.',
                'info_paragraph' => 'All storytelling events are suitable for ages 12 and above unless specifically marked as children\'s events. Tickets can be purchased online or at the venue 30 minutes before each performance. In case of rain, outdoor events will be moved to covered locations nearby.',
                'hero_image' => '/img/storytelling-hero.jpg',
                'featured_image' => '/img/featured-storyteller.jpg',
            ],
            'yummy' => [
                'hero_date' => 'JULY 23-26, 2026',
                'hero_title' => 'YUMMY! GOURMET WITH A TWIST',
                'hero_subtitle' => 'A curated culinary experience featuring seven restaurants, exclusive festival-only menus.',
                'explore_heading' => 'Explore Restaurants',
                'explore_text' => 'Taste the finest culinary experiences in Haarlem.',
                'feature_card_one_title' => 'Featured Restaurants',
                'feature_card_one_text' => 'Seven participating Haarlem restaurants offer a special festival experience, each serving a unique menu created exclusively for THE FESTIVAL.',
                'feature_card_two_title' => 'Festival Only Menus',
                'feature_card_two_text' => 'Each restaurant prepares one special menu designed specifically for THE FESTIVAL. These menus are available only during the festival days and offer a curated dining experience.',
                'feature_card_three_title' => 'Multiple Sessions',
                'feature_card_three_text' => 'Restaurants offer several dining sessions each evening, giving visitors the flexibility to choose a time that fits their festival schedule. Seats are limited, and reservations are required.',
                'intro_heading' => 'Yummy Events',
                'intro_text' => 'Taste the finest culinary experiences in Haarlem.',
                'hero_image' => '/assets/yummy/image/yummy-hero.jpg',
            ],
            'history' => [
                'hero_title' => 'Historic Haarlem',
                'hero_description' => 'Walk through centuries of rich history with expert guides. Explore Haarlem\'s most iconic landmarks and hidden gems.',
            ],
        ];

        return $defaults[$page] ?? [];
    }

    /** @return list<string> */
    public static function jsonPageKeys(): array
    {
        $keys = [];
        foreach (self::pages() as $key => $meta) {
            if (($meta['storage'] ?? '') === 'json') {
                $keys[] = $key;
            }
        }

        return $keys;
    }
}
