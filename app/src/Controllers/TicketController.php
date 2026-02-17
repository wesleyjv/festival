<?php

namespace App\Controllers;

use App\DB;
use App\Models\Session;

class TicketController
{
    public function index()
    {
        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            header('Location: /events/history');
            exit;
        }

        $db = DB::getConnection();
        $stmt = $db->prepare("
            SELECT s.*, l.name as location_name 
            FROM sessions s
            JOIN locations l ON s.location_id = l.id
            WHERE s.event_id = :id 
            ORDER BY s.start_time ASC
        ");
        $stmt->execute(['id' => $eventId]);

        $sessions = [];
        foreach ($stmt as $row) {
            $session = new Session();
            $session->id = (int)$row['id'];
            $session->startTime = new \DateTime($row['start_time']);
            $session->endTime = new \DateTime($row['end_time']);
            $session->price = (float)$row['price'];
            $session->totalCapacity = (int)$row['capacity'];
            $sessions[] = $session;
        }

        require __DIR__ . '/../views/tickets/index.php';
    }
}