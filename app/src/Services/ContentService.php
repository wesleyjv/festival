<?php

namespace App\Services;

/**
 * Very small content storage layer for editable page sections.
 *
 * Stores HTML snippets per page in JSON files under app/storage/content.
 * This is intentionally simple for the assignment and can be swapped
 * for a real database later without changing controllers/views.
 */
final class ContentService
{
    private string $storageDir;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../../storage/content';
    }

    /**
     * Load all editable fields for a given page.
     *
     * @return array<string,string>
     */
    public function getPageContent(string $page): array
    {
        $path = $this->getPagePath($page);

        if (is_file($path)) {
            $json = file_get_contents($path);
            if ($json !== false) {
                $data = json_decode($json, true);
                if (is_array($data)) {
                    return array_map('strval', $data);
                }
            }
        }

        return $this->getDefaultContent($page);
    }

    /**
     * Persist editable fields for a page.
     *
     * @param array<string,string> $data
     */
    public function savePageContent(string $page, array $data): void
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0775, true);
        }

        $path = $this->getPagePath($page);

        // Only keep scalar string-ish values.
        $clean = [];
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $clean[$key] = (string) $value;
            }
        }

        file_put_contents($path, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function getPagePath(string $page): string
    {
        $safe = preg_replace('/[^a-z0-9_\-]/i', '_', $page);
        return $this->storageDir . '/' . $safe . '.json';
    }

    /**
     * Default values that mirror the current hard-coded copy,
     * so existing pages keep their look before any edits.
     *
     * @return array<string,string>
     */
    private function getDefaultContent(string $page): array
    {
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
                ];

            case 'yummy':
                return [
                    'intro_heading' => 'Yummy Events',
                    'intro_text' => 'Taste the finest culinary experiences in Haarlem.',
                ];

            case 'history':
                return [
                    'hero_title' => 'Historic Haarlem',
                    'hero_description' => 'Walk through centuries of rich history with expert guides. Explore Haarlem\'s most iconic landmarks and hidden gems.',
                ];

            case 'jazz':
                return [
                    'intro_heading' => 'Jazz Events',
                    'intro_text' => 'Discover the best jazz performances at the festival.',
                ];

            default:
                return [];
        }
    }
}

