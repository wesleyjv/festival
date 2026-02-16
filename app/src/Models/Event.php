<?php

namespace App\Models;

use PDO;
use Exception;

class Event
{
    public ?int $id = null;
    public string $title;
    public string $description;
    public string $image;
    public string $alt_text;
    public string $type;
    public string $link;
    public string $image_position;
    public int $sort_order;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->image = $data['image'] ?? '';
        $this->alt_text = $data['alt_text'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->link = $data['link'] ?? '';
        $this->image_position = $data['image_position'] ?? 'left';
        $this->sort_order = $data['sort_order'] ?? 0;
    }

    public static function getHomepageEvents(): array
    {
        try {
            $db = Database::getConnection();
            
            $sql = "SELECT id, title, description, image, alt_text, type, link, image_position, sort_order 
                    FROM events 
                    WHERE is_active = 1 
                    ORDER BY sort_order ASC";
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            $events = [];
            while ($row = $stmt->fetch()) {
                $events[] = new self($row);
            }
            
            return $events;
        } catch (Exception $e) {
            return self::getFallbackEvents();
        }
    }

    private static function getFallbackEvents(): array
    {
        return [
            new self([
                'title' => 'Haarlem Jazz',
                'description' => 'Welcome to Haarlem Jazz – where the city resonates with the soulful notes of jazz. Explore the artists, events, and the dynamic vibe of this enchanting Dutch festival right here on our Haarlem Jazz page. Get ready for a musical journey that defines the spirit of jazz in the heart of Haarlem!',
                'image' => '/img/jazz-festival.jpg',
                'alt_text' => 'Haarlem Jazz',
                'type' => 'jazz',
                'link' => '/events/jazz',
                'image_position' => 'left'
            ]),
            new self([
                'title' => 'Storytelling',
                'description' => 'Step into the world of one of our featured storytellers and discover what makes their voice unique. This page invites you to explore their craft, their stories, and the experiences they bring to the festival.',
                'image' => '/img/storytelling.jpg',
                'alt_text' => 'Storytelling Event',
                'type' => 'storytelling',
                'link' => '/events/stories',
                'image_position' => 'right'
            ]),
            new self([
                'title' => 'Yummy!',
                'description' => 'Get excited for the festival! Check out all the tasty restaurants and stay tuned for a closer look at two of them, including pics, chef info, and a sneak peek at their delicious dishes. It\'s foodie heaven coming your way!',
                'image' => '/img/food-festival.jpg',
                'alt_text' => 'Yummy Food Festival',
                'type' => 'yummy',
                'link' => '/events/yummy',
                'image_position' => 'left'
            ]),
            new self([
                'title' => 'Stroll Through History',
                'description' => 'Participate in an amazing historical tour through the beautiful city of Haarlem. From July 28th to July 31st you can participate in such a tour. In a duration of 2.5 hours you will be able to visit 9 venues which will surely impress you!',
                'image' => '/img/history-tour.jpg',
                'alt_text' => 'History Tour',
                'type' => 'history',
                'link' => '/events/history',
                'image_position' => 'right'
            ])
        ];
    }

    public static function createTable(): bool
    {
        try {
            $db = Database::getConnection();
            
            $tableExists = $db->query("SHOW TABLES LIKE 'events'")->rowCount() > 0;
            
            if (!$tableExists) {
                $sql = "CREATE TABLE events (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT NOT NULL,
                    image VARCHAR(500) NOT NULL,
                    alt_text VARCHAR(255) NOT NULL,
                    type VARCHAR(50) NOT NULL,
                    link VARCHAR(500) NOT NULL,
                    image_position ENUM('left', 'right') DEFAULT 'left',
                    sort_order INT DEFAULT 0,
                    is_active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                
                $db->exec($sql);
                
                self::seedDefaultEvents();
            } else {
                $columns = $db->query("SHOW COLUMNS FROM events")->fetchAll(PDO::FETCH_COLUMN);
                
                $expectedColumns = ['title', 'description', 'image', 'alt_text', 'type', 'link'];
                $hasAllColumns = true;
                
                foreach ($expectedColumns as $column) {
                    if (!in_array($column, $columns)) {
                        $hasAllColumns = false;
                        break;
                    }
                }
                
                if (!$hasAllColumns) {
                    error_log("Events table exists but has incompatible structure. Using fallback data.");
                    return false;
                }
                
                $count = $db->query("SELECT COUNT(*) FROM events")->fetchColumn();
                if ($count == 0) {
                    self::seedDefaultEvents();
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Failed to create/verify events table: " . $e->getMessage());
            return false;
        }
    }

    private static function seedDefaultEvents(): void
    {
        try {
            $db = Database::getConnection();
            
            $defaultEvents = self::getFallbackEvents();
            
            $sql = "INSERT INTO events (title, description, image, alt_text, type, link, image_position, sort_order) 
                    VALUES (:title, :description, :image, :alt_text, :type, :link, :image_position, :sort_order)";
            
            $stmt = $db->prepare($sql);
            
            foreach ($defaultEvents as $index => $event) {
                $stmt->execute([
                    ':title' => $event->title,
                    ':description' => $event->description,
                    ':image' => $event->image,
                    ':alt_text' => $event->alt_text,
                    ':type' => $event->type,
                    ':link' => $event->link,
                    ':image_position' => $event->image_position,
                    ':sort_order' => $index
                ]);
            }
        } catch (Exception $e) {
            error_log("Failed to seed events: " . $e->getMessage());
        }
    }
}
