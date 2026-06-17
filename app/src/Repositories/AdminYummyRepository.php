<?php

namespace App\Repositories;

use App\Database;
use App\Repositories\Interfaces\IAdminYummyRepository;
use PDO;

/** Executes admin read queries against the restaurants and restaurant_menu_items tables. */
class AdminYummyRepository implements IAdminYummyRepository
{
	private PDO $connection;

	public function __construct()
	{
		$this->connection = Database::getConnection();
	}

	/** Includes inactive restaurants so the admin can see and reactivate them. */
	public function findAllRestaurantsForAdmin(): array
	{
		$stmt = $this->connection->prepare("
			SELECT
				id,
				name AS restaurant_name,
				slug,
				address,
				short_description,
				adult_price_cents,
				child_price_cents,
				rating,
				active
			FROM restaurants
			ORDER BY name ASC
		");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/** Returns a single restaurant row with all editable fields, or null when not found. */
	public function findRestaurantById(int $restaurantId): ?array
	{
		$stmt = $this->connection->prepare("
			SELECT
				id,
				name AS restaurant_name,
				slug,
				address,
				short_description,
				about,
				adult_price_cents,
				child_price_cents,
				child_max_age,
				seats,
				session_count,
				session_duration_minutes,
				session_one_start_time,
				session_two_start_time,
				session_three_start_time,
				rating,
				review_count,
				restaurant_image_path,
				chef_image_path,
				chef_name,
				chef_title,
				chef_bio,
				about_image_path,
				reservation_image_path,
				active
			FROM restaurants
			WHERE id = :id
			LIMIT 1
		");
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row ?: null;
	}

	/**
	 * Returns all menu items for the given restaurant, ordered by display_order.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function findMenuItemsByRestaurantId(int $restaurantId): array
	{
		$stmt = $this->connection->prepare("
			SELECT id, restaurant_id, name, description, image_path, display_order
			FROM restaurant_menu_items
			WHERE restaurant_id = :restaurant_id
			ORDER BY display_order ASC
		");
		$stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/** Returns a single menu item row by its primary key, or null when not found. */
	public function findMenuItemById(int $itemId): ?array
	{
		$stmt = $this->connection->prepare("
			SELECT id, restaurant_id, name, description, image_path, display_order
			FROM restaurant_menu_items
			WHERE id = :id
			LIMIT 1
		");
		$stmt->bindValue(':id', $itemId, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row ?: null;
	}
}
