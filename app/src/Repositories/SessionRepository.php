<?php
namespace App\Repositories;

use App\DB;
use App\Models\Session;

/**
 * Repository class responsible for retrieving Session data from the database.
 */
class SessionRepository
{
    /**
     * Retrieve a single Session by its ID.
     *
     * Queries the `sessions` table using a prepared statement and maps
     * the resulting row to a {@see Session} model instance.
     *
     * @param int $id The unique identifier of the session.
     * @return Session|null The corresponding Session object, or null if no matching record is found.
     */
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