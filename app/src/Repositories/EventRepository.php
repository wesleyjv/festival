<?php

namespace App\Repositories;

use App\DB;
use App\Models\HistoryEvent;

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
}