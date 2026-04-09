<?php

namespace App\Services;

use App\Repositories\JazzContentRepository;

/**
 * Editable page sections: JSON files under app/storage/content,
 * except Jazz pages which use the `jazz_page_contents` table.
 *
 * Page keys:
 * - `jazz` — Jazz homepage
 * - `jazz_{eventId}` — Overrides for a jazz artist detail page
 */
final class ContentService
{
    private string $storageDir;
    private JazzContentRepository $jazzContentRepo;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../../storage/content';
        $this->jazzContentRepo = new JazzContentRepository();
    }

    /**
     * @return array<string,string>
     */
    public function getPageContent(string $page): array
    {
        if ($this->isJazzDbPage($page) && $this->jazzContentRepo->hasConnection()) {
            $dbData = $this->jazzContentRepo->getPageContent($page);

            return array_merge($this->getDefaultContent($page), $dbData);
        }

        $path = $this->getPagePath($page);

        if (is_file($path)) {
            $json = file_get_contents($path);
            if ($json !== false) {
                $data = json_decode($json, true);
                if (is_array($data)) {
                    return array_merge($this->getDefaultContent($page), array_map('strval', $data));
                }
            }
        }

        return $this->getDefaultContent($page);
    }

    /**
     * @param array<string,string> $data
     */
    public function savePageContent(string $page, array $data): void
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $clean[$key] = (string) $value;
            }
        }

        $savedToJazzDb = false;
        if ($this->isJazzDbPage($page)) {
            $savedToJazzDb = $this->jazzContentRepo->savePageContent($page, $clean);
        }

        if ($this->isJazzDbPage($page) && $savedToJazzDb) {
            return;
        }

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0775, true);
        }

        $path = $this->getPagePath($page);
        file_put_contents($path, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function isJazzDbPage(string $page): bool
    {
        if ($page === 'jazz') {
            return true;
        }

        return (bool) preg_match('/^jazz_\d+$/', $page);
    }

    private function getPagePath(string $page): string
    {
        $safe = preg_replace('/[^a-z0-9_\-]/i', '_', $page);

        return $this->storageDir . '/' . $safe . '.json';
    }

    /**
     * @return array<string,string>
     */
    private function getDefaultContent(string $page): array
    {
        if ($page === 'jazz') {
            return $this->defaultJazzHome();
        }

        if (preg_match('/^jazz_\d+$/', $page)) {
            return $this->defaultJazzArtist();
        }

        switch ($page) {
            case 'homepage':
                return [
                    'hero_title' => "Welcome to<br>The Haarlem Festival",
                    'hero_subtitle' => 'Discover the best of food, music, stories, and history in the heart of Haarlem.',
                    'cta_heading' => 'Ready to experience Haarlem?',
                    'cta_text' => 'Secure your spot before events sell out.',
                ];

            case 'stories':
                return [
                    'hero_title' => 'The Art of <span class="highlight">Storytelling</span>',
                    'hero_description' => 'Experience the magic of oral tradition as master storytellers weave tales that transport you through time and imagination. From ancient myths to contemporary narratives, discover the power of stories that connect us all.',
                    'info_paragraph' => 'All storytelling events are suitable for ages 12 and above unless specifically marked as children\'s events. Tickets can be purchased online or at the venue 30 minutes before each performance. In case of rain, outdoor events will be moved to covered locations nearby.',
                    'hero_image' => '/img/storytelling-hero.jpg',
                    'featured_image' => '/img/featured-storyteller.jpg',
                ];

            case 'yummy':
                return [
                    'hero_date' => 'JULY 23-26, 2026',
                    'hero_title' => 'YUMMY! GOURMET WITH A TWIST',
                    'hero_subtitle' => 'A curated culinary experience featuring seven restaurants, exclusive festival-only menus.',
                    'explore_heading' => 'Explore Restaurants',
                    'explore_text' => 'Taste the finest culinary experiences in Haarlem.',
                    // Keep legacy keys so older templates remain functional during migration.
                    'intro_heading' => 'Yummy Events',
                    'intro_text' => 'Taste the finest culinary experiences in Haarlem.',
                    'hero_image' => '/assets/yummy/image/yummy-hero.jpg',
                ];

            case 'history':
                return [
                    'hero_title' => 'Haarlem<br>History',
                    'hero_subtitle' => 'Walking Tour & Highlights',
                    'hero_description' => 'Guided tours from Thursday till Sunday',
                    'editorial_1_title' => 'Haarlems History',
                    'editorial_1_text' => 'Haarlem is one of the oldest cities in the Netherlands, with a recorded history dating back over 800 years. It received city rights in 1245 and quickly developed into an important medieval trading and cultural center. During the Dutch Golden Age, Haarlem flourished as a hub for art, printing, and industry, attracting renowned painters such as Frans Hals and playing a key role in the development of Dutch culture.<br><br>The city\'s historic center still reflects this rich past. Medieval churches like the Grote Kerk dominate the skyline, while narrow streets and hidden hofjes recall daily life in earlier centuries. Haarlem was also one of the first Dutch cities to embrace innovation, from early industrial mills to the country\'s first museum, Teylers Museum, founded in 1778. Together, these layers of history make Haarlem a living record of Dutch heritage.',
                    'editorial_2_title' => 'Trade, Canals, and Daily Life',
                    'editorial_2_text' => 'Haarlem\'s growth was shaped not only by major historical events, but also by everyday life along its canals and streets. During the medieval period and the centuries that followed, the city developed into an important center of trade and craftsmanship. Industries such as brewing, textiles, and shipping played a central role in the local economy, attracting workers, merchants, and artisans from across the region.<br><br>The canals functioned as vital transport routes, allowing goods to move efficiently through the city while shaping its urban layout. Homes, warehouses, and workshops were built close to the water, forming compact neighborhoods where commerce and daily life were closely connected. Many of these historic structures still line Haarlem\'s waterways today, offering a visible reminder of how trade, community, and design together defined the city\'s character.',
                ];

            default:
                return [];
        }
    }

    /**
     * @return array<string,string>
     */
    private function defaultJazzHome(): array
    {
        return [
            'hero_title' => "Haarlem Jazz\nLive in the heart of the city",
            'hero_background_image' => '',
            'intro_heading' => 'Feel the rhythm of Haarlem',
            'intro_sub' => 'Soul, swing & late-night sessions',
            'intro_text' => '<p>Welcome to Haarlem Jazz – where the city resonates with the soulful notes of jazz. Explore the artists, events, and the dynamic vibe of this enchanting Dutch festival right here on our Haarlem Jazz page. Get ready for a musical journey that defines the spirit of jazz in the heart of Haarlem!</p>',
            'intro_image' => '/img/jazz-festival.jpg',
            'artists_heading' => 'Line-up',
            'artists_sub' => 'Filter by day and discover who plays when.',
            'locations_heading' => 'Festival locations',
            'locations_sub' => 'Around Haarlem – stroll between venues.',
            'locations_image' => '',
            'locations_text' => '<div class="location-list__item"><div class="location-list__name">De Patronaat</div><div class="location-list__addr">Zijlsingel 2, 2013 DN<br>Haarlem</div></div><div class="location-list__item"><div class="location-list__name">Grote Markt</div><div class="location-list__addr">Grote Markt<br>Haarlem</div></div><div class="location-list__item"><div class="location-list__name">Station Haarlem</div><div class="location-list__addr">Stationsplein 1IL<br>2011 LR Haarlem</div></div>',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function defaultJazzArtist(): array
    {
        return [
            'banner_image' => '',
            'profile_image' => '',
            'artist_name' => '',
            'bio_html' => '',
            'tracks_heading' => 'Listen to their sounds',
            'performances_heading' => 'Upcoming performances',
            'price_note' => 'Included in passes',
        ];
    }
}
