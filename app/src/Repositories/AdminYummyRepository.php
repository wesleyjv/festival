<?php

namespace App\Repositories;

use App\Database;
use App\Repositories\Interfaces\IAdminYummyRepository;
use PDO;
use PDOStatement;

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

	/**
	 * Inserts a new restaurant row and returns the new primary key.
	 * Rich-text fields (about, chef_bio) must be pre-sanitized by the service.
	 */
	public function createRestaurant(array $data): int
	{
		$stmt = $this->connection->prepare("
			INSERT INTO restaurants (
				name, slug, address, short_description, about,
				adult_price_cents, child_price_cents, child_max_age,
				seats, session_count, session_duration_minutes,
				session_one_start_time, session_two_start_time, session_three_start_time,
				rating, review_count,
				restaurant_image_path, chef_image_path, chef_name, chef_title, chef_bio,
				about_image_path, reservation_image_path, active
			) VALUES (
				:name, :slug, :address, :short_description, :about,
				:adult_price_cents, :child_price_cents, :child_max_age,
				:seats, :session_count, :session_duration_minutes,
				:session_one_start_time, :session_two_start_time, :session_three_start_time,
				:rating, :review_count,
				:restaurant_image_path, :chef_image_path, :chef_name, :chef_title, :chef_bio,
				:about_image_path, :reservation_image_path, 1
			)
		");

		$stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
		$stmt->bindValue(':slug', $data['slug'], PDO::PARAM_STR);
		$this->bindAllEditableFields($stmt, $data);
		$stmt->execute();

		return (int) $this->connection->lastInsertId();
	}

	/** Updates all editable columns for the given restaurant. */
	public function updateRestaurant(int $restaurantId, array $data): void
	{
		$stmt = $this->connection->prepare("
			UPDATE restaurants SET
				name = :name,
				slug = :slug,
				address = :address,
				short_description = :short_description,
				about = :about,
				adult_price_cents = :adult_price_cents,
				child_price_cents = :child_price_cents,
				child_max_age = :child_max_age,
				seats = :seats,
				session_count = :session_count,
				session_duration_minutes = :session_duration_minutes,
				session_one_start_time = :session_one_start_time,
				session_two_start_time = :session_two_start_time,
				session_three_start_time = :session_three_start_time,
				rating = :rating,
				review_count = :review_count,
				restaurant_image_path = :restaurant_image_path,
				chef_image_path = :chef_image_path,
				chef_name = :chef_name,
				chef_title = :chef_title,
				chef_bio = :chef_bio,
				about_image_path = :about_image_path,
				reservation_image_path = :reservation_image_path
			WHERE id = :id
		");

		$stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
		$stmt->bindValue(':slug', $data['slug'], PDO::PARAM_STR);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$this->bindAllEditableFields($stmt, $data);
		$stmt->execute();
	}

	/**
	 * Deletes menu items and cuisine tags first to satisfy FK constraints, then
	 * deletes the restaurant row.
	 */
	public function deleteRestaurant(int $restaurantId): void
	{
		$stmt = $this->connection->prepare(
			'DELETE FROM restaurant_menu_items WHERE restaurant_id = :id'
		);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		$stmt = $this->connection->prepare(
			'DELETE FROM restaurant_cuisine_tags WHERE restaurant_id = :id'
		);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		$stmt = $this->connection->prepare(
			'DELETE FROM restaurants WHERE id = :id'
		);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/** Flips the `active` flag between 0 and 1 for the given restaurant. */
	public function toggleRestaurantActiveStatus(int $restaurantId): void
	{
		$stmt = $this->connection->prepare(
			'SELECT active FROM restaurants WHERE id = :id LIMIT 1'
		);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row === false) {
			return;
		}

		$newActiveValue = 1 - (int) $row['active'];

		$stmt = $this->connection->prepare(
			'UPDATE restaurants SET active = :active WHERE id = :id'
		);
		$stmt->bindValue(':active', $newActiveValue, PDO::PARAM_INT);
		$stmt->bindValue(':id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Inserts a new menu item when $data has no item_id, otherwise updates the
	 * existing row. Description and image_path must be pre-sanitized by the caller.
	 */
	public function saveMenuItem(int $restaurantId, array $data): void
	{
		$itemId       = isset($data['item_id']) && (int) $data['item_id'] > 0
		                ? (int) $data['item_id']
		                : null;
		$name         = (string) ($data['name'] ?? '');
		$description  = isset($data['description']) && $data['description'] !== '' ? $data['description'] : null;
		$imagePath    = isset($data['image_path'])   && $data['image_path']   !== '' ? $data['image_path']   : null;
		$displayOrder = isset($data['display_order']) && $data['display_order'] !== ''
		                ? (int) $data['display_order']
		                : 0;

		if ($itemId !== null) {
			$stmt = $this->connection->prepare("
				UPDATE restaurant_menu_items
				SET name = :name, description = :description,
				    image_path = :image_path, display_order = :display_order
				WHERE id = :id AND restaurant_id = :restaurant_id
			");
			$stmt->bindValue(':id', $itemId, PDO::PARAM_INT);
		} else {
			$stmt = $this->connection->prepare("
				INSERT INTO restaurant_menu_items
				    (restaurant_id, name, description, image_path, display_order)
				VALUES
				    (:restaurant_id, :name, :description, :image_path, :display_order)
			");
		}

		$stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
		$stmt->bindValue(':name',          $name,         PDO::PARAM_STR);
		$stmt->bindValue(':display_order', $displayOrder, PDO::PARAM_INT);
		$this->bindNullableString($stmt, ':description', $description);
		$this->bindNullableString($stmt, ':image_path',  $imagePath);
		$stmt->execute();
	}

	/** Permanently removes a single menu item row. */
	public function deleteMenuItem(int $menuItemId): void
	{
		$stmt = $this->connection->prepare(
			'DELETE FROM restaurant_menu_items WHERE id = :id'
		);
		$stmt->bindValue(':id', $menuItemId, PDO::PARAM_INT);
		$stmt->execute();
	}

	// ── Private helpers ───────────────────────────────────────────────────────

	/**
	 * Binds all nullable editable fields shared between createRestaurant and
	 * updateRestaurant. Rich-text fields (about, chef_bio) must be pre-sanitized
	 * by the service before calling this method.
	 */
	private function bindAllEditableFields(PDOStatement $stmt, array $data): void
	{
		$this->bindNullableString($stmt, ':address',                  $data['address']              ?? null);
		$this->bindNullableString($stmt, ':short_description',        $data['short_description']    ?? null);
		$this->bindNullableString($stmt, ':about',                    $data['about']                ?? null);
		$this->bindNullableString($stmt, ':session_one_start_time',   $data['session_one_start_time']   ?? null);
		$this->bindNullableString($stmt, ':session_two_start_time',   $data['session_two_start_time']   ?? null);
		$this->bindNullableString($stmt, ':session_three_start_time', $data['session_three_start_time'] ?? null);
		$this->bindNullableString($stmt, ':restaurant_image_path',    $data['restaurant_image_path']    ?? null);
		$this->bindNullableString($stmt, ':chef_image_path',          $data['chef_image_path']      ?? null);
		$this->bindNullableString($stmt, ':chef_name',                $data['chef_name']            ?? null);
		$this->bindNullableString($stmt, ':chef_title',               $data['chef_title']           ?? null);
		$this->bindNullableString($stmt, ':chef_bio',                 $data['chef_bio']             ?? null);
		$this->bindNullableString($stmt, ':about_image_path',         $data['about_image_path']     ?? null);
		$this->bindNullableString($stmt, ':reservation_image_path',   $data['reservation_image_path'] ?? null);

		$this->bindNullableInt($stmt, ':adult_price_cents',         $data['adult_price_cents']         ?? null);
		$this->bindNullableInt($stmt, ':child_price_cents',         $data['child_price_cents']         ?? null);
		$this->bindNullableInt($stmt, ':child_max_age',             $data['child_max_age']             ?? null);
		$this->bindNullableInt($stmt, ':seats',                     $data['seats']                     ?? null);
		$this->bindNullableInt($stmt, ':session_count',             $data['session_count']             ?? null);
		$this->bindNullableInt($stmt, ':session_duration_minutes',  $data['session_duration_minutes']  ?? null);
		$this->bindNullableInt($stmt, ':review_count',              $data['review_count']              ?? null);

		$ratingRaw = $data['rating'] ?? null;
		if ($ratingRaw === null || $ratingRaw === '') {
			$stmt->bindValue(':rating', null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue(':rating', (float) $ratingRaw, PDO::PARAM_STR);
		}
	}

	private function bindNullableString(PDOStatement $stmt, string $param, ?string $value): void
	{
		if ($value === null || $value === '') {
			$stmt->bindValue($param, null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue($param, $value, PDO::PARAM_STR);
		}
	}

	private function bindNullableInt(PDOStatement $stmt, string $param, mixed $value): void
	{
		if ($value === null || $value === '') {
			$stmt->bindValue($param, null, PDO::PARAM_NULL);
		} else {
			$stmt->bindValue($param, (int) $value, PDO::PARAM_INT);
		}
	}
}
