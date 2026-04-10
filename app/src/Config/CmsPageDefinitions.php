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
                    ['name' => 'hero_title', 'label' => 'Hero title (supports HTML)', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'text'],
                    ['name' => 'hero_description', 'label' => 'Tour description (hero box)', 'type' => 'wysiwyg', 'rows' => 2],
                    ['name' => 'editorial_1_title', 'label' => 'Editorial section 1 — title', 'type' => 'text'],
                    ['name' => 'editorial_1_text', 'label' => 'Editorial section 1 — text (HTML)', 'type' => 'wysiwyg', 'rows' => 4],
                    ['name' => 'editorial_2_title', 'label' => 'Editorial section 2 — title', 'type' => 'text'],
                    ['name' => 'editorial_2_text', 'label' => 'Editorial section 2 — text (HTML)', 'type' => 'wysiwyg', 'rows' => 4],
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
                'hero_title' => 'Haarlem<br>History',
                'hero_subtitle' => 'Walking Tour & Highlights',
                'hero_description' => 'Guided tours from Thursday till Sunday',
                'editorial_1_title' => 'Haarlems History',
                'editorial_1_text' => 'Haarlem is one of the oldest cities in the Netherlands, with a recorded history dating back over 800 years. It received city rights in 1245 and quickly developed into an important medieval trading and cultural center. During the Dutch Golden Age, Haarlem flourished as a hub for art, printing, and industry, attracting renowned painters such as Frans Hals and playing a key role in the development of Dutch culture.<br><br>The city\'s historic center still reflects this rich past. Medieval churches like the Grote Kerk dominate the skyline, while narrow streets and hidden hofjes recall daily life in earlier centuries. Haarlem was also one of the first Dutch cities to embrace innovation, from early industrial mills to the country\'s first museum, Teylers Museum, founded in 1778. Together, these layers of history make Haarlem a living record of Dutch heritage.',
                'editorial_2_title' => 'Trade, Canals, and Daily Life',
                'editorial_2_text' => 'Haarlem\'s growth was shaped not only by major historical events, but also by everyday life along its canals and streets. During the medieval period and the centuries that followed, the city developed into an important center of trade and craftsmanship. Industries such as brewing, textiles, and shipping played a central role in the local economy, attracting workers, merchants, and artisans from across the region.<br><br>The canals functioned as vital transport routes, allowing goods to move efficiently through the city while shaping its urban layout. Homes, warehouses, and workshops were built close to the water, forming compact neighborhoods where commerce and daily life were closely connected. Many of these historic structures still line Haarlem\'s waterways today, offering a visible reminder of how trade, community, and design together defined the city\'s character.',
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
