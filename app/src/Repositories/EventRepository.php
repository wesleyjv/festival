<?php

namespace App\Repositories;

use App\DB;
use App\Models\HistoryEvent;
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