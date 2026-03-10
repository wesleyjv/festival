<?php

namespace App\Repositories;

use App\DB;
use App\Models\HistoryEvent;
use App\Models\JazzEvent;
use App\Models\StorytellingEvent;

/**
 * Repository class responsible for retrieving event data from the database.
 */
class EventRepository
{
    /**
     * Retrieves all history events from the database.
     *
     * Joins the `events` and `history_events` tables to fetch history-specific
     * event data, including the guide name and language. Each row is mapped
     * to a {@see HistoryEvent} model instance.
     *
     * @return HistoryEvent[] An array of HistoryEvent objects.
     */
    public function getHistoryEvents(): array
    {
        $db = DB::getConnection();

        $sql = "SELECT * FROM history_events";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $events = [];
        foreach ($stmt as $row) {
            $event = new HistoryEvent([
                'id' => (int)$row['event_id'],
                'title' => $row['guide_name'] ?? '',
                'description' => '',
                'image' => '',
            ]);

            $event->guide = $row['guide_name'] ?? 'TBA';
            $event->language = $row['language'] ?? 'English';

            $events[] = $event;
        }

        return $events;
    }

    /**
     * Retrieves jazz events, optionally filtered by day of week.
     *
     * @param  string|null $day  Day name in English, e.g. "Friday" (case-insensitive). Null returns all.
     * @return JazzEvent[]
     */
    public function getJazzEvents(?string $day = null): array
    {
        $db = DB::getConnection();

        $sql = "SELECT je.event_id, je.artist, je.style, je.description,
                       je.profile_image, je.banner_image, je.location,
                       je.start_time, je.end_time, je.price, je.seats,
                       je.images, je.tracks
                FROM jazz_events je
                JOIN events e ON je.event_id = e.id
                WHERE e.type = 'jazz'";

        $params = [];
        if ($day !== null && $day !== 'all') {
            $sql .= " AND DAYNAME(je.start_time) = :day";
            $params[':day'] = ucfirst(strtolower($day));
        }

        $sql .= " ORDER BY je.artist ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $events = [];
        foreach ($stmt as $row) {
            $event = new JazzEvent([]);
            $event->eventId      = (int) $row['event_id'];
            $event->artist       = $row['artist'] ?? '';
            $event->style        = $row['style'] ?? '';
            $event->description  = $row['description'] ?? '';
            $event->profileImage = $row['profile_image'] ?? null;
            $event->bannerImage  = $row['banner_image'] ?? null;
            $event->location     = $row['location'] ?? null;
            $event->startTime    = $row['start_time'] ?? null;
            $event->endTime      = $row['end_time'] ?? null;
            $event->price        = $row['price'] !== null ? (float) $row['price'] : null;
            $event->seats        = $row['seats'] !== null ? (int) $row['seats'] : null;
            $event->images       = $row['images'] !== null ? json_decode($row['images'], true) : null;
            $event->tracks       = $row['tracks'] !== null ? json_decode($row['tracks'], true) : null;
            $events[] = $event;
        }

        return $events;
    }

    /**
     * Creates a new StorytellingEvent model instance using the shared DB connection.
     *
     * @return StorytellingEvent
     */
    private function createStorytellingModel(): StorytellingEvent
    {
        $db = DB::getConnection();

        return new StorytellingEvent($db);
    }

    /**
     * Retrieves all storytelling events.
     *
     * @return array
     */
    public function getStorytellingEvents(): array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getStorytellingEvents();
    }

    /**
     * Retrieves storytelling events filtered by date.
     *
     * @param string $date
     * @return array
     */
    public function getStorytellingEventsByDate(string $date): array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getStorytellingEventsByDate($date);
    }

    /**
     * Retrieves storytelling events filtered by time of day.
     *
     * @param string $timeOfDay
     * @return array
     */
    public function getStorytellingEventsByTime(string $timeOfDay): array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getStorytellingEventsByTime($timeOfDay);
    }

    /**
     * Retrieves storytelling events filtered by location.
     *
     * @param int $locationId
     * @return array
     */
    public function getStorytellingEventsByLocation(int $locationId): array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getStorytellingEventsByLocation($locationId);
    }

    /**
     * Retrieves all locations for storytelling events.
     *
     * @return array
     */
    public function getStorytellingLocations(): array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getStorytellingLocations();
    }

    /**
     * Retrieves the featured storyteller.
     *
     * @return array|null
     */
    public function getFeaturedStoryteller(): ?array
    {
        $storytellingModel = $this->createStorytellingModel();

        return $storytellingModel->getFeaturedStoryteller();
    }
}