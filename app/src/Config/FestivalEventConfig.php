<?php

declare(strict_types=1);

namespace App\Config;

use App\DB;
use PDO;

/**
 * Resolves festival-scoped event IDs from the database (or environment), avoiding magic numbers in repositories.
 */
final class FestivalEventConfig
{
    public static function yummyEventId(): int
    {
        $env = getenv('YUMMY_EVENT_ID');
        if ($env !== false && $env !== '' && ctype_digit($env)) {
            return (int) $env;
        }

        try {
            $db = DB::getConnection();
            $stmt = $db->query(
                "SELECT id FROM events WHERE type = 'yummy' ORDER BY id ASC LIMIT 1"
            );
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['id'])) {
                return (int) $row['id'];
            }
        } catch (\Throwable) {
            // fall through
        }

        return 97;
    }
}
