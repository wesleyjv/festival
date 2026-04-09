<?php

namespace App\Repositories;

use App\Database;
use App\Models\YummyEvent;
use App\Repositories\Interfaces\IYummyRepository;
use PDO;

class YummyEventRepository implements IYummyRepository
{
	private const YUMMY_EVENT_ID = 97;

	private PDO $connection;

	public function __construct()
	{
		$this->connection = Database::getConnection();
	}

	/**
	 * @return YummyEvent[]
	 */
	public function getAll(int $eventId, ?string $cuisine = null): array
	{
		$sql = "
			SELECT
				r.id,
				r.name AS restaurant_name,
				r.slug,
				r.description,
				r.address,
				r.image_path,
				yer.price,
				yer.rating,
				GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
			FROM yummy_event_restaurants yer
			INNER JOIN restaurants r ON r.id = yer.restaurant_id
			LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
			LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
			WHERE yer.event_id = :event_id
			  AND yer.active = 1
		";

		if ($cuisine !== null && $cuisine !== '') {
			$sql .= "
			  AND r.id IN (
					SELECT rct2.restaurant_id
					FROM restaurant_cuisine_tags rct2
					INNER JOIN cuisine_tags ct2 ON ct2.id = rct2.tag_id
					WHERE ct2.name = :cuisine
			  )
			";
		}

		$sql .= "
			GROUP BY
				r.id, r.name, r.slug, r.description, r.address, r.image_path,
				yer.price, yer.rating
			ORDER BY r.name ASC
		";

		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);

		if ($cuisine !== null && $cuisine !== '') {
			$stmt->bindValue(':cuisine', $cuisine, PDO::PARAM_STR);
		}

		$stmt->execute();

		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map([$this, 'mapRowToYummyEvent'], $rows);
	}

	/**
	 * @return string[]
	 */
	public function getAvailableCuisines(int $eventId): array
	{
		$sql = "
			SELECT DISTINCT ct.name
			FROM cuisine_tags ct
			INNER JOIN restaurant_cuisine_tags rct ON rct.tag_id = ct.id
			INNER JOIN yummy_event_restaurants yer ON yer.restaurant_id = rct.restaurant_id
			WHERE yer.event_id = :event_id
			  AND yer.active = 1
			ORDER BY ct.name ASC
		";

		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	public function getBySlug(int $eventId, string $slug): ?YummyEvent
	{
		$sql = "
			SELECT
				r.id,
				r.name AS restaurant_name,
				r.slug,
				r.description,
				r.address,
				r.image_path,
				yer.price,
				yer.rating,
				GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
			FROM yummy_event_restaurants yer
			INNER JOIN restaurants r ON r.id = yer.restaurant_id
			LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
			LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
			WHERE yer.event_id = :event_id
			  AND yer.active = 1
			  AND r.slug = :slug
			GROUP BY
				r.id, r.name, r.slug, r.description, r.address, r.image_path,
				yer.price, yer.rating
			LIMIT 1
		";

		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(':event_id', $eventId, PDO::PARAM_INT);
		$stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row ? $this->mapRowToYummyEvent($row) : null;
	}

	private function mapRowToYummyEvent(array $row): YummyEvent
	{
		$tags = !empty($row['cuisine_tags'])
			? array_map('trim', explode(',', (string) $row['cuisine_tags']))
			: [];

		return new YummyEvent(
			id: (int) $row['id'],
			restaurantName: (string) $row['restaurant_name'],
			slug: (string) $row['slug'],
			description: $row['description'] ?? null,
			address: $row['address'] ?? null,
			imagePath: $row['image_path'] ?? null,
			price: isset($row['price']) ? (float) $row['price'] : null,
			rating: isset($row['rating']) ? (float) $row['rating'] : null,
			cuisineTags: $tags
		);
	}
}
