<?php

namespace App\Repositories;

use App\DB;
use App\Models\HistoryEvent;

class EventRepository
{
    public function getHistoryEvents(): array
    {
        $db = DB::getConnection();
        
        $sql = "SELECT e.*, h.guide_name, h.language 
                FROM events e 
                JOIN history_events h ON e.id = h.event_id 
                WHERE e.type = 'history'";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $events = [];
        foreach ($stmt as $row) {
            $event = new HistoryEvent();
            $event->id = (int)$row['id'];
            $event->name = $row['name'];
            $event->description = $row['description'];
            $event->image = $row['image'] ?? '';

            $event->guide = $row['guide_name'] ?? 'TBA'; 
            $event->language = $row['language'] ?? 'English';
            
            $events[] = $event;
        }

        return $events;
    }
}