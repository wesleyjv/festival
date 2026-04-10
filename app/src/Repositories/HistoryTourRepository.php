<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DB;
use PDO;

/**
 * Admin CRUD for walking-tour rows in `history_events` plus linked `events` listing rows.
 */
final class HistoryTourRepository
{
    /**
     * @return list<array<string, mixed>>
     */
    public function getAllForAdmin(): array
    {
        try {
            $db = DB::getConnection();
            $stmt = $db->query('SELECT * FROM history_events ORDER BY event_id ASC');

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            error_log('HistoryTourRepository::getAllForAdmin: ' . $e->getMessage());

            return [];
        }
    }

    public function create(string $guideName, string $language): int
    {
        $guideName = trim($guideName);
        $language = trim($language);
        if ($guideName === '' || $language === '') {
            throw new \InvalidArgumentException('Guide name and language are required.');
        }

        $db = DB::getConnection();
        $events = new EventRepository();

        $db->beginTransaction();
        try {
            $eventId = $events->insertHistoryListingEvent($db, $guideName, $guideName);
            $stmt = $db->prepare(
                'INSERT INTO history_events (event_id, guide_name, language) VALUES (:id, :g, :l)'
            );
            $stmt->execute(['id' => $eventId, 'g' => $guideName, 'l' => $language]);
            $db->commit();

            return $eventId;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function update(int $eventId, string $guideName, string $language): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid tour id.');
        }
        $guideName = trim($guideName);
        $language = trim($language);
        if ($guideName === '' || $language === '') {
            throw new \InvalidArgumentException('Guide name and language are required.');
        }

        $db = DB::getConnection();
        $db->beginTransaction();
        try {
            $chk = $db->prepare('SELECT 1 FROM history_events WHERE event_id = :id LIMIT 1');
            $chk->execute(['id' => $eventId]);
            if (!$chk->fetchColumn()) {
                throw new \RuntimeException('History tour not found.');
            }

            $db->prepare(
                'UPDATE history_events SET guide_name = :g, language = :l WHERE event_id = :id'
            )->execute(['g' => $guideName, 'l' => $language, 'id' => $eventId]);

            (new EventRepository())->updateListingEventTitleDescription($db, $eventId, $guideName, $guideName);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function delete(int $eventId): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid tour id.');
        }

        $db = DB::getConnection();

        if ((new EventRepository())->tableExists($db, 'tickets')) {
            $c = $db->prepare('SELECT COUNT(*) FROM tickets WHERE event_id = :id');
            $c->execute(['id' => $eventId]);
            if ((int) $c->fetchColumn() > 0) {
                throw new \RuntimeException(
                    'Cannot delete this tour while tickets exist for this event.'
                );
            }
        }

        $db->beginTransaction();
        try {
            $delHe = $db->prepare('DELETE FROM history_events WHERE event_id = :id');
            $delHe->execute(['id' => $eventId]);
            if ($delHe->rowCount() === 0) {
                throw new \RuntimeException('History tour not found.');
            }

            $delEv = $db->prepare('DELETE FROM events WHERE id = :id');
            $delEv->execute(['id' => $eventId]);
            if ($delEv->rowCount() === 0) {
                throw new \RuntimeException('Event row not found.');
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
