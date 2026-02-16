<?php
namespace App\Repositories;

use App\DB;
use App\Models\Session;

class SessionRepository
{
    public function getById(int $id): ?Session
    {
        $db = DB::getConnection();
        $stmt = $db->prepare("SELECT * FROM sessions WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        $session = new Session();
        $session->id = (int)$row['id'];
        $session->startTime = new \DateTime($row['start_time']);
        $session->endTime = new \DateTime($row['end_time']);
        $session->totalCapacity = (int)$row['capacity'];
        $session->price = (float)$row['price']; 

        
        return $session;
    }
}