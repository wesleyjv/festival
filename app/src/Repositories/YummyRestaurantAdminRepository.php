<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Config\FestivalEventConfig;
use App\DB;
use PDO;

/**
 * Admin CRUD for restaurants participating in the Yummy festival event.
 */
final class YummyRestaurantAdminRepository
{
    /**
     * @return list<array<string, mixed>>
     */
    public function listForAdmin(): array
    {
        try {
            $db = DB::getConnection();
            $eid = FestivalEventConfig::yummyEventId();
            $sql = '
                SELECT r.id AS restaurant_id, r.name, r.slug, r.description, r.address, r.image_path,
                       yer.price, yer.rating, yer.active
                FROM yummy_event_restaurants yer
                INNER JOIN restaurants r ON r.id = yer.restaurant_id
                WHERE yer.event_id = :eid
                ORDER BY yer.active DESC, r.name ASC
            ';
            $stmt = $db->prepare($sql);
            $stmt->execute(['eid' => $eid]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (\Throwable $e) {
            error_log('YummyRestaurantAdminRepository::listForAdmin: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * @param array<string, string> $input
     */
    public function create(array $input): int
    {
        $name = trim($input['name'] ?? '');
        if ($name === '') {
            throw new \InvalidArgumentException('Restaurant name is required.');
        }

        $slugIn = trim($input['slug'] ?? '');
        $slug = $slugIn !== '' ? $this->uniqueSlug($this->normalizeSlug($slugIn)) : $this->uniqueSlug($this->normalizeSlug($name));

        $description = trim($input['description'] ?? '');
        $address = trim($input['address'] ?? '');
        $imagePath = trim($input['image_path'] ?? '');

        $price = null;
        if (isset($input['price']) && $input['price'] !== '' && is_numeric($input['price'])) {
            $price = (float) $input['price'];
        }
        $rating = null;
        if (isset($input['rating']) && $input['rating'] !== '' && is_numeric($input['rating'])) {
            $rating = (float) $input['rating'];
        }

        $db = DB::getConnection();
        $eid = FestivalEventConfig::yummyEventId();

        $db->beginTransaction();
        try {
            $db->prepare(
                'INSERT INTO restaurants (name, slug, description, address, image_path)
                 VALUES (:name, :slug, :desc, :addr, :img)'
            )->execute([
                'name' => $name,
                'slug' => $slug,
                'desc' => $description !== '' ? $description : null,
                'addr' => $address !== '' ? $address : null,
                'img' => $imagePath !== '' ? $imagePath : null,
            ]);
            $rid = (int) $db->lastInsertId();

            $db->prepare(
                'INSERT INTO yummy_event_restaurants (event_id, restaurant_id, price, rating, active)
                 VALUES (:eid, :rid, :price, :rating, 1)'
            )->execute([
                'eid' => $eid,
                'rid' => $rid,
                'price' => $price,
                'rating' => $rating,
            ]);

            $db->commit();

            return $rid;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, string> $input
     */
    public function update(int $restaurantId, array $input): void
    {
        if ($restaurantId <= 0) {
            throw new \InvalidArgumentException('Invalid restaurant id.');
        }

        $this->assertLinkedToYummy($restaurantId);

        $name = trim($input['name'] ?? '');
        if ($name === '') {
            throw new \InvalidArgumentException('Restaurant name is required.');
        }

        $slugIn = trim($input['slug'] ?? '');
        $slug = $slugIn !== ''
            ? $this->uniqueSlug($this->normalizeSlug($slugIn), $restaurantId)
            : $this->uniqueSlug($this->normalizeSlug($name), $restaurantId);

        $description = trim($input['description'] ?? '');
        $address = trim($input['address'] ?? '');
        $imagePath = trim($input['image_path'] ?? '');

        $price = null;
        if (isset($input['price']) && $input['price'] !== '' && is_numeric($input['price'])) {
            $price = (float) $input['price'];
        }
        $rating = null;
        if (isset($input['rating']) && $input['rating'] !== '' && is_numeric($input['rating'])) {
            $rating = (float) $input['rating'];
        }

        $db = DB::getConnection();
        $eid = FestivalEventConfig::yummyEventId();

        $db->beginTransaction();
        try {
            $db->prepare(
                'UPDATE restaurants SET name = :name, slug = :slug, description = :desc,
                 address = :addr, image_path = :img WHERE id = :id'
            )->execute([
                'name' => $name,
                'slug' => $slug,
                'desc' => $description !== '' ? $description : null,
                'addr' => $address !== '' ? $address : null,
                'img' => $imagePath !== '' ? $imagePath : null,
                'id' => $restaurantId,
            ]);

            $db->prepare(
                'UPDATE yummy_event_restaurants SET price = :price, rating = :rating
                 WHERE restaurant_id = :rid AND event_id = :eid'
            )->execute([
                'price' => $price,
                'rating' => $rating,
                'rid' => $restaurantId,
                'eid' => $eid,
            ]);

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function deactivate(int $restaurantId): void
    {
        if ($restaurantId <= 0) {
            throw new \InvalidArgumentException('Invalid restaurant id.');
        }

        $this->assertLinkedToYummy($restaurantId);

        $db = DB::getConnection();
        $db->prepare(
            'UPDATE yummy_event_restaurants SET active = 0
             WHERE restaurant_id = :rid AND event_id = :eid'
        )->execute([
            'rid' => $restaurantId,
            'eid' => FestivalEventConfig::yummyEventId(),
        ]);
    }

    public function reactivate(int $restaurantId): void
    {
        if ($restaurantId <= 0) {
            throw new \InvalidArgumentException('Invalid restaurant id.');
        }

        $this->assertLinkedToYummy($restaurantId);

        $db = DB::getConnection();
        $db->prepare(
            'UPDATE yummy_event_restaurants SET active = 1
             WHERE restaurant_id = :rid AND event_id = :eid'
        )->execute([
            'rid' => $restaurantId,
            'eid' => FestivalEventConfig::yummyEventId(),
        ]);
    }

    private function assertLinkedToYummy(int $restaurantId): void
    {
        $db = DB::getConnection();
        $st = $db->prepare(
            'SELECT 1 FROM yummy_event_restaurants WHERE restaurant_id = :r AND event_id = :e LIMIT 1'
        );
        $st->execute(['r' => $restaurantId, 'e' => FestivalEventConfig::yummyEventId()]);
        if (!$st->fetchColumn()) {
            throw new \RuntimeException('Restaurant is not part of this Yummy event.');
        }
    }

    private function normalizeSlug(string $raw): string
    {
        $s = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($raw)));

        return trim($s, '-') ?: 'restaurant';
    }

    private function uniqueSlug(string $base, ?int $exceptRestaurantId = null): string
    {
        $slug = $base;
        $n = 2;
        while ($this->slugTaken($slug, $exceptRestaurantId)) {
            $slug = $base . '-' . $n;
            $n++;
        }

        return $slug;
    }

    private function slugTaken(string $slug, ?int $exceptRestaurantId): bool
    {
        $db = DB::getConnection();
        if ($exceptRestaurantId === null) {
            $st = $db->prepare('SELECT 1 FROM restaurants WHERE slug = :s LIMIT 1');
            $st->execute(['s' => $slug]);

            return (bool) $st->fetchColumn();
        }

        $st = $db->prepare('SELECT 1 FROM restaurants WHERE slug = :s AND id != :id LIMIT 1');
        $st->execute(['s' => $slug, 'id' => $exceptRestaurantId]);

        return (bool) $st->fetchColumn();
    }
}
