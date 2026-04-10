<?php

namespace App\Repositories;

use App\DB;
use App\Models\HistoryEvent;
use App\Models\JazzEvent;

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
     * @param  string      $sort  "artist" or "start_time"
     * @return JazzEvent[]
     */
    public function getJazzEvents(?string $day = null, string $sort = 'artist'): array
    {
        $db = DB::getConnection();

        $imgCols = $this->jazzEventImageColumnsSql();
        $sql = "SELECT je.event_id, je.artist, je.style, je.description,
                       {$imgCols}
                       je.location,
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

        $sql .= $sort === 'start_time'
            ? ' ORDER BY je.start_time ASC, je.artist ASC'
            : ' ORDER BY je.artist ASC';

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
            $hp = $row['homepage_image'] ?? null;
            $event->homepageImage = ($hp !== null && $hp !== '') ? $hp : null;
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
     * Retrieves a single jazz event by its event ID.
     *
     * @param  int $id
     * @return JazzEvent|null  Null when not found.
     */
    public function getJazzEventById(int $id): ?JazzEvent
    {
        $db = DB::getConnection();

        $imgCols = $this->jazzEventImageColumnsSql();
        $sql = "SELECT je.event_id, je.artist, je.style, je.description,
                       {$imgCols}
                       je.location,
                       je.start_time, je.end_time, je.price, je.seats,
                       je.images, je.tracks
                FROM jazz_events je
                JOIN events e ON je.event_id = e.id
                WHERE je.event_id = :id AND e.type = 'jazz'
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $event = new JazzEvent([]);
        $event->eventId      = (int) $row['event_id'];
        $event->artist       = $row['artist'] ?? '';
        $event->style        = $row['style'] ?? '';
        $event->description  = $row['description'] ?? '';
        $event->profileImage = $row['profile_image'] ?? null;
        $event->bannerImage  = $row['banner_image'] ?? null;
        $hp = $row['homepage_image'] ?? null;
        $event->homepageImage = ($hp !== null && $hp !== '') ? $hp : null;
        $event->location     = $row['location'] ?? null;
        $event->startTime    = $row['start_time'] ?? null;
        $event->endTime      = $row['end_time'] ?? null;
        $event->price        = $row['price'] !== null ? (float) $row['price'] : null;
        $event->seats        = $row['seats'] !== null ? (int) $row['seats'] : null;
        $event->images       = $row['images'] !== null ? json_decode($row['images'], true) : null;
        $event->tracks       = $row['tracks'] !== null ? json_decode($row['tracks'], true) : null;

        return $event;
    }

    /**
     * Create a jazz artist: one row in `events` (type jazz) and one in `jazz_events`.
     *
     * @param array{artist:string,description:string,style?:string,location?:string,start_time?:string,end_time?:string,price?:string,seats?:string} $input
     * @return int New event id (same as jazz_events.event_id)
     */
    public function createJazzArtist(array $input): int
    {
        $db = DB::getConnection();

        $artist = trim($input['artist'] ?? '');
        $description = trim($input['description'] ?? '');
        if ($artist === '' || $description === '') {
            throw new \InvalidArgumentException('Artist name and description are required.');
        }

        $style = trim($input['style'] ?? '');
        $location = trim($input['location'] ?? '');
        $location = $location !== '' ? $location : null;

        $startTime = $this->parseOptionalDateTime($input['start_time'] ?? null);
        $endTime = $this->parseOptionalDateTime($input['end_time'] ?? null);

        $price = null;
        if (isset($input['price']) && $input['price'] !== '' && is_numeric($input['price'])) {
            $price = (float) $input['price'];
        }
        $seats = null;
        if (isset($input['seats']) && $input['seats'] !== '' && is_numeric($input['seats'])) {
            $seats = (int) $input['seats'];
        }

        $db->beginTransaction();
        try {
            $eventId = $this->insertEventsListingRow($db, $artist, $description, 'jazz', '/events/jazz', true);

            $insJazz = $db->prepare(
                'INSERT INTO jazz_events (
                    event_id, artist, style, description, profile_image, banner_image, location,
                    start_time, end_time, price, seats, images, tracks
                ) VALUES (
                    :event_id, :artist, :style, :description, NULL, NULL, :location,
                    :start_time, :end_time, :price, :seats, NULL, NULL
                )'
            );
            $insJazz->execute([
                'event_id' => $eventId,
                'artist' => $artist,
                'style' => $style,
                'description' => $description,
                'location' => $location,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'price' => $price,
                'seats' => $seats,
            ]);

            $db->commit();

            $ticketPrice = $price !== null ? (float) $price : 0.0;
            $ticketRepo = new TicketRepository();
            if ($ticketRepo->getByEventId($eventId) === []) {
                $ticketRepo->insertTicketForEvent(
                    $eventId,
                    'Admission — ' . $artist,
                    $ticketPrice
                );
            }

            return $eventId;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Remove a jazz artist and CMS rows. Fails if tickets still reference this event.
     */
    public function deleteJazzArtist(int $eventId): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid artist id.');
        }

        $db = DB::getConnection();

        if ($this->tableExists($db, 'tickets')) {
            $c = $db->prepare('SELECT COUNT(*) FROM tickets WHERE event_id = :id');
            $c->execute(['id' => $eventId]);
            if ((int) $c->fetchColumn() > 0) {
                throw new \RuntimeException(
                    'Cannot delete this artist while tickets exist for this event. Remove or reassign tickets first.'
                );
            }
        }

        $db->beginTransaction();
        try {
            $pageKey = 'jazz_' . $eventId;
            if ($this->tableExists($db, 'jazz_page_contents')) {
                $db->prepare('DELETE FROM jazz_page_contents WHERE page = :p')->execute(['p' => $pageKey]);
            }

            $delJe = $db->prepare('DELETE FROM jazz_events WHERE event_id = :id');
            $delJe->execute(['id' => $eventId]);
            if ($delJe->rowCount() === 0) {
                throw new \RuntimeException('Jazz artist not found.');
            }

            $delEv = $db->prepare('DELETE FROM events WHERE id = :id AND type = \'jazz\'');
            $delEv->execute(['id' => $eventId]);
            if ($delEv->rowCount() === 0) {
                throw new \RuntimeException('Event row not found or not a jazz event.');
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Persist jazz_events media columns (paths + JSON) from the admin artist form.
     * Paths are stored as VARCHAR URLs (same as CMS); files live on disk under /public/uploads.
     *
     * @param array<string,string> $pageData POST body after CSRF/page keys removed (includes profile_image, etc.)
     */
    public function syncJazzArtistMediaFromAdmin(int $eventId, array $pageData, string $tracksJson, string $imagesJson): void
    {
        if ($eventId <= 0) {
            return;
        }

        $db = DB::getConnection();
        $chk = $db->prepare(
            'SELECT 1 FROM jazz_events je INNER JOIN events e ON e.id = je.event_id
             WHERE je.event_id = :id AND e.type = \'jazz\' LIMIT 1'
        );
        $chk->execute(['id' => $eventId]);
        if (!$chk->fetchColumn()) {
            return;
        }

        $cols = $this->getJazzEventsTableColumns($db);
        $tracks = $this->sanitizeTracksJsonForDb($tracksJson);
        $images = $this->sanitizeImagesJsonForDb($imagesJson);

        $normPath = static function ($v): ?string {
            $v = trim((string) $v);

            return $v === '' ? null : $v;
        };

        $sets   = [];
        $params = ['event_id' => $eventId];

        if (in_array('profile_image', $cols, true)) {
            $sets[]                    = 'profile_image = :profile_image';
            $params['profile_image']   = $normPath($pageData['profile_image'] ?? '');
        }
        if (in_array('banner_image', $cols, true)) {
            $sets[]                   = 'banner_image = :banner_image';
            $params['banner_image']   = $normPath($pageData['banner_image'] ?? '');
        }
        if (in_array('homepage_image', $cols, true)) {
            $sets[]                    = 'homepage_image = :homepage_image';
            $params['homepage_image']  = $normPath($pageData['homepage_image'] ?? '');
        }
        if (in_array('tracks', $cols, true)) {
            $sets[]            = 'tracks = :tracks';
            $params['tracks']  = $tracks === [] ? null : json_encode($tracks, JSON_UNESCAPED_UNICODE);
        }
        if (in_array('images', $cols, true)) {
            $sets[]            = 'images = :images';
            $params['images']  = $images === [] ? null : json_encode($images, JSON_UNESCAPED_UNICODE);
        }

        if ($sets === []) {
            return;
        }

        $sql = 'UPDATE jazz_events SET ' . implode(', ', $sets) . ' WHERE event_id = :event_id';
        $db->prepare($sql)->execute($params);
    }

    /**
     * @return list<array{title:string, genre:string, url:string, duration:string, duration_seconds:int}>
     */
    private function sanitizeTracksJsonForDb(string $json): array
    {
        if (strlen($json) > 120000) {
            return [];
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return [];
        }
        $out = [];
        foreach (array_slice($data, 0, 24) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $genre = trim((string) ($row['genre'] ?? ''));
            $url   = trim((string) ($row['url'] ?? ''));
            if ($url !== '' && !preg_match('#^(/uploads/|https?://)#i', $url)) {
                $url = '';
            }
            $dur        = trim((string) ($row['duration'] ?? ''));
            $durSeconds = (int) ($row['duration_seconds'] ?? 0);
            if ($durSeconds < 0) {
                $durSeconds = 0;
            }
            if ($dur === '' && $durSeconds > 0) {
                $dur = sprintf('%d:%02d', intdiv($durSeconds, 60), $durSeconds % 60);
            }
            $out[] = [
                'title'             => $title,
                'genre'             => $genre,
                'url'               => $url,
                'duration'          => $dur,
                'duration_seconds'  => $durSeconds,
            ];
        }

        return $out;
    }

    /**
     * @return list<array{url:string, alt:string}>
     */
    private function sanitizeImagesJsonForDb(string $json): array
    {
        if (strlen($json) > 120000) {
            return [];
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return [];
        }
        $out = [];
        foreach (array_slice($data, 0, 24) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $url = trim((string) ($row['url'] ?? ''));
            if ($url === '' || !preg_match('#^(/uploads/|https?://)#i', $url)) {
                continue;
            }
            $alt = trim((string) ($row['alt'] ?? ''));
            $out[] = ['url' => $url, 'alt' => $alt];
        }

        return $out;
    }

    /**
     * SQL fragment: profile + banner (+ homepage_image when column exists).
     */
    private function jazzEventImageColumnsSql(): string
    {
        static $fragment = null;
        if ($fragment !== null) {
            return $fragment;
        }
        try {
            $db = DB::getConnection();
            $q  = $db->query("SHOW COLUMNS FROM jazz_events WHERE Field = 'homepage_image'");
            $fragment = ($q && $q->fetch())
                ? 'je.profile_image, je.banner_image, je.homepage_image,'
                : 'je.profile_image, je.banner_image,';
        } catch (\Throwable $e) {
            $fragment = 'je.profile_image, je.banner_image,';
        }

        return $fragment;
    }

    /**
     * @return string[]
     */
    private function getJazzEventsTableColumns(\PDO $db): array
    {
        try {
            $stmt = $db->query('SHOW COLUMNS FROM jazz_events');
            $out  = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                if (!empty($row['Field'])) {
                    $out[] = $row['Field'];
                }
            }

            return $out;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * @return string[]
     */
    private function getEventsTableColumns(\PDO $db): array
    {
        $stmt = $db->query('SHOW COLUMNS FROM events');
        $out = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (isset($row['Field'])) {
                $out[] = $row['Field'];
            }
        }

        return $out;
    }

    /**
     * Insert a parent `events` row for a history walking tour (links to `history_events`).
     */
    public function insertHistoryListingEvent(\PDO $db, string $title, string $description): int
    {
        return $this->insertEventsListingRow($db, $title, $description, 'history', '/events/history', false);
    }

    /**
     * Update listing title/description on `events` for a typed festival event.
     */
    public function updateListingEventTitleDescription(
        \PDO $db,
        int $eventId,
        string $title,
        string $description
    ): void {
        $cols = $this->getEventsTableColumns($db);
        $titleCol = in_array('name', $cols, true)
            ? 'name'
            : (in_array('title', $cols, true) ? 'title' : null);
        if ($titleCol === null) {
            return;
        }

        $sets = ['`' . str_replace('`', '', $titleCol) . '` = :t'];
        $params = ['t' => $title, 'id' => $eventId];
        if (in_array('description', $cols, true)) {
            $sets[] = 'description = :d';
            $params['d'] = $description;
        }

        $sql = 'UPDATE events SET ' . implode(', ', $sets) . ' WHERE id = :id';
        $db->prepare($sql)->execute($params);
    }

    /**
     * Persist editable jazz schedule fields from the admin Events tab.
     *
     * @param array<string, string> $input
     */
    public function updateJazzArtist(int $eventId, array $input): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid artist id.');
        }

        $artist = trim($input['artist'] ?? '');
        $description = trim($input['description'] ?? '');
        if ($artist === '' || $description === '') {
            throw new \InvalidArgumentException('Artist name and description are required.');
        }

        $style = trim($input['style'] ?? '');
        $location = trim($input['location'] ?? '');
        $location = $location !== '' ? $location : null;

        $startTime = $this->parseOptionalDateTime($input['start_time'] ?? null);
        $endTime = $this->parseOptionalDateTime($input['end_time'] ?? null);

        $price = null;
        if (isset($input['price']) && $input['price'] !== '' && is_numeric($input['price'])) {
            $price = (float) $input['price'];
        }
        $seats = null;
        if (isset($input['seats']) && $input['seats'] !== '' && is_numeric($input['seats'])) {
            $seats = (int) $input['seats'];
        }

        $db = DB::getConnection();
        $chk = $db->prepare(
            'SELECT 1 FROM jazz_events je INNER JOIN events e ON e.id = je.event_id
             WHERE je.event_id = :id AND e.type = \'jazz\' LIMIT 1'
        );
        $chk->execute(['id' => $eventId]);
        if (!$chk->fetchColumn()) {
            throw new \RuntimeException('Jazz artist not found.');
        }

        $db->beginTransaction();
        try {
            $this->updateListingEventTitleDescription($db, $eventId, $artist, $description);

            $db->prepare(
                'UPDATE jazz_events SET artist = :a, style = :st, description = :d, location = :l,
                 start_time = :s1, end_time = :s2, price = :p, seats = :se
                 WHERE event_id = :id'
            )->execute([
                'a' => $artist,
                'st' => $style,
                'd' => $description,
                'l' => $location,
                's1' => $startTime,
                's2' => $endTime,
                'p' => $price,
                'se' => $seats,
                'id' => $eventId,
            ]);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Insert an `events` listing row (supports name vs title column). Optionally rewrite link with new id (jazz detail URLs).
     */
    private function insertEventsListingRow(
        \PDO $db,
        string $title,
        string $description,
        string $type,
        string $link,
        bool $permalLinkWithId
    ): int {
        $cols = $this->getEventsTableColumns($db);

        $titleCol = in_array('name', $cols, true)
            ? 'name'
            : (in_array('title', $cols, true) ? 'title' : null);
        if ($titleCol === null) {
            throw new \RuntimeException('The events table must have a name or title column.');
        }

        $fields = [];
        $placeholders = [];
        $params = [];

        $add = function (string $column, $value) use (&$fields, &$placeholders, &$params, $cols): void {
            if (!in_array($column, $cols, true)) {
                return;
            }
            $fields[] = '`' . str_replace('`', '', $column) . '`';
            $placeholders[] = ':' . $column;
            $params[$column] = $value;
        };

        $image = $type === 'history' ? '/img/history-tour.jpg' : '/img/jazz-festival.jpg';

        $add($titleCol, $title);
        $add('description', $description);
        $add('image', $image);
        $add('alt_text', $title);
        $add('type', $type);
        $add('link', $link);
        $add('image_position', 'left');
        $add('sort_order', 0);
        $add('is_active', 1);

        if ($fields === []) {
            throw new \RuntimeException('Could not build INSERT for events (no matching columns).');
        }

        $sql = 'INSERT INTO events (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $eventId = (int) $db->lastInsertId();

        if ($permalLinkWithId && in_array('link', $cols, true)) {
            $upd = $db->prepare('UPDATE events SET link = :link WHERE id = :id');
            $upd->execute([
                'link' => '/events/jazz/' . $eventId,
                'id' => $eventId,
            ]);
        }

        return $eventId;
    }

    private function parseOptionalDateTime(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        $ts = strtotime(str_replace('T', ' ', $value));
        if ($ts === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $ts);
    }

    public function tableExists(\PDO $db, string $name): bool
    {
        try {
            $stmt = $db->prepare(
                'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?'
            );
            $stmt->execute([$name]);

            return (int) $stmt->fetchColumn() > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

}