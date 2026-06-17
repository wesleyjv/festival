<?php

namespace App\Repositories;

use App\Database;
use App\Models\YummyEvent;
use App\Models\YummyMenuItem;
use App\Repositories\Interfaces\IYummyRepository;
use PDO;

/** Fetches restaurant data directly from the `restaurants` table. */
class YummyEventRepository implements IYummyRepository
{
	private PDO $connection;

	public function __construct()
	{
		$this->connection = Database::getConnection();
	}

	/**
	 * Returns only active restaurants — inactive ones are excluded from the public listing.
	 *
	 * @return YummyEvent[]
	 */
	public function findAllActiveRestaurants(?string $cuisineFilter = null): array
	{
		$sql = "
			SELECT
				r.id,
				r.name AS restaurant_name,
				r.slug,
				r.address,
				r.short_description,
				r.about,
				r.adult_price_cents,
				r.child_price_cents,
				r.child_max_age,
				r.seats,
				r.session_count,
				r.session_duration_minutes,
				r.session_one_start_time,
				r.session_two_start_time,
				r.session_three_start_time,
				r.rating,
				r.review_count,
				r.restaurant_image_path,
				r.chef_image_path,
				r.chef_name,
				r.chef_title,
				r.chef_bio,
				r.about_image_path,
				r.reservation_image_path,
				GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
			FROM restaurants r
			LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
			LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
			WHERE r.active = 1
		";

		if ($cuisineFilter !== null && $cuisineFilter !== '') {
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
				r.id, r.name, r.slug, r.address, r.short_description, r.about,
				r.adult_price_cents, r.child_price_cents, r.child_max_age,
				r.seats, r.session_count, r.session_duration_minutes,
				r.session_one_start_time, r.session_two_start_time, r.session_three_start_time,
				r.rating, r.review_count, r.restaurant_image_path, r.chef_image_path,
				r.chef_name, r.chef_title, r.chef_bio, r.about_image_path, r.reservation_image_path
			ORDER BY r.name ASC
		";

		$stmt = $this->connection->prepare($sql);

		if ($cuisineFilter !== null && $cuisineFilter !== '') {
			$stmt->bindValue(':cuisine', $cuisineFilter, PDO::PARAM_STR);
		}

		$stmt->execute();

		return array_map([$this, 'mapRowToYummyEvent'], $stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	/**
	 * Returns the distinct cuisine tag names present across active restaurants.
	 *
	 * @return string[]
	 */
	public function findAllAvailableCuisines(): array
	{
		$sql = "
			SELECT DISTINCT ct.name
			FROM cuisine_tags ct
			INNER JOIN restaurant_cuisine_tags rct ON rct.tag_id = ct.id
			INNER JOIN restaurants r ON r.id = rct.restaurant_id
			WHERE r.active = 1
			ORDER BY ct.name ASC
		";

		$stmt = $this->connection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}

	/** Looks up an active restaurant by its URL slug; returns null when not found or inactive. */
	public function findActiveRestaurantBySlug(string $slug): ?YummyEvent
	{
		$stmt = $this->connection->prepare($this->buildSingleRestaurantSql('r.slug = :param'));
		$stmt->bindValue(':param', $slug, PDO::PARAM_STR);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row ? $this->mapRowToYummyEvent($row) : null;
	}

	/** Looks up an active restaurant by its primary key; returns null when not found or inactive. */
	public function findRestaurantById(int $restaurantId): ?YummyEvent
	{
		$stmt = $this->connection->prepare($this->buildSingleRestaurantSql('r.id = :param'));
		$stmt->bindValue(':param', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row ? $this->mapRowToYummyEvent($row) : null;
	}

	/**
	 * Returns menu items for the restaurant ordered by display_order ascending.
	 *
	 * @return YummyMenuItem[]
	 */
	public function findMenuItemsByRestaurantId(int $restaurantId): array
	{
		$sql = "
			SELECT id, restaurant_id, name, description, image_path, display_order
			FROM restaurant_menu_items
			WHERE restaurant_id = :restaurant_id
			ORDER BY display_order ASC
		";

		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(':restaurant_id', $restaurantId, PDO::PARAM_INT);
		$stmt->execute();

		return array_map([$this, 'mapRowToYummyMenuItem'], $stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	/**
	 * Inserts a row into yummy_reservations and returns the new primary key.
	 * Nullable fields (special_request, user_id) are bound as NULL when absent.
	 */
	public function saveReservation(array $data): int
	{
		$stmt = $this->connection->prepare("
			INSERT INTO yummy_reservations
			    (restaurant_id, session_number, festival_date, adults, children,
			     special_request, reservation_fee_cents, user_id)
			VALUES
			    (:restaurant_id, :session_number, :festival_date, :adults, :children,
			     :special_request, :reservation_fee_cents, :user_id)
		");

		$stmt->bindValue(':restaurant_id',        $data['restaurant_id'],        PDO::PARAM_INT);
		$stmt->bindValue(':session_number',        $data['session_number'],        PDO::PARAM_INT);
		$stmt->bindValue(':festival_date',         $data['festival_date'],         PDO::PARAM_STR);
		$stmt->bindValue(':adults',                $data['adults'],                PDO::PARAM_INT);
		$stmt->bindValue(':children',              $data['children'],              PDO::PARAM_INT);
		$stmt->bindValue(':reservation_fee_cents', $data['reservation_fee_cents'], PDO::PARAM_INT);

		$specialRequest = $data['special_request'] ?? null;
		$stmt->bindValue(
			':special_request',
			($specialRequest !== null && $specialRequest !== '') ? $specialRequest : null,
			($specialRequest !== null && $specialRequest !== '') ? PDO::PARAM_STR : PDO::PARAM_NULL
		);

		$userId = $data['user_id'] ?? null;
		$stmt->bindValue(
			':user_id',
			$userId !== null ? (int) $userId : null,
			$userId !== null ? PDO::PARAM_INT : PDO::PARAM_NULL
		);

		$stmt->execute();

		return (int) $this->connection->lastInsertId();
	}

	/**
	 * Inserts a template ticket row into the tickets table for a Yummy reservation.
	 * event_id = 0 (no real event), order_id / user_id = NULL, ticket_code = '' (template).
	 */
	public function createReservationTicket(array $ticketData): int
	{
		$stmt = $this->connection->prepare("
			INSERT INTO tickets (event_id, name, price, order_id, user_id, ticket_code, is_scanned)
			VALUES (0, :name, :price, NULL, NULL, :ticket_code, 0)
		");

		$stmt->bindValue(':name',        $ticketData['name'],  PDO::PARAM_STR);
		$stmt->bindValue(':price',       $ticketData['price']); // PDO casts float correctly
		$stmt->bindValue(':ticket_code', 'YMY-' . strtoupper(bin2hex(random_bytes(6))), PDO::PARAM_STR);
		$stmt->execute();

		return (int) $this->connection->lastInsertId();
	}

	/**
	 * Links a yummy_reservations row to its template ticket.
	 */
	public function updateReservationTicketId(int $reservationId, int $ticketId): void
	{
		$stmt = $this->connection->prepare("
			UPDATE yummy_reservations SET ticket_id = :ticketId WHERE id = :reservationId
		");

		$stmt->bindValue(':ticketId',      $ticketId,      PDO::PARAM_INT);
		$stmt->bindValue(':reservationId', $reservationId, PDO::PARAM_INT);
		$stmt->execute();
	}

	/**
	 * Returns SUM(adults + children) across non-cancelled reservations for the given
	 * restaurant/date/session. Returns 0 when there are none.
	 */
	public function countReservedSeatsForSession(int $restaurantId, string $festivalDate, int $sessionNumber): int
	{
		$stmt = $this->connection->prepare("
			SELECT COALESCE(SUM(adults + children), 0)
			FROM yummy_reservations
			WHERE restaurant_id = :restaurant_id
			  AND festival_date = :festival_date
			  AND session_number = :session_number
			  AND status != 'cancelled'
		");

		$stmt->bindValue(':restaurant_id',  $restaurantId,  PDO::PARAM_INT);
		$stmt->bindValue(':festival_date',  $festivalDate,  PDO::PARAM_STR);
		$stmt->bindValue(':session_number', $sessionNumber, PDO::PARAM_INT);
		$stmt->execute();

		return (int) $stmt->fetchColumn();
	}

	// ── Private helpers ───────────────────────────────────────────────────────

	/**
	 * Returns the full SELECT … FROM restaurants … WHERE r.active = 1 AND {$whereClause}
	 * GROUP BY … LIMIT 1 SQL string used by both slug- and id-based single-row lookups.
	 * The caller must bind a `:param` placeholder for the WHERE condition value.
	 */
	private function buildSingleRestaurantSql(string $whereClause): string
	{
		return "
			SELECT
				r.id,
				r.name AS restaurant_name,
				r.slug,
				r.address,
				r.short_description,
				r.about,
				r.adult_price_cents,
				r.child_price_cents,
				r.child_max_age,
				r.seats,
				r.session_count,
				r.session_duration_minutes,
				r.session_one_start_time,
				r.session_two_start_time,
				r.session_three_start_time,
				r.rating,
				r.review_count,
				r.restaurant_image_path,
				r.chef_image_path,
				r.chef_name,
				r.chef_title,
				r.chef_bio,
				r.about_image_path,
				r.reservation_image_path,
				GROUP_CONCAT(ct.name ORDER BY ct.name SEPARATOR ', ') AS cuisine_tags
			FROM restaurants r
			LEFT JOIN restaurant_cuisine_tags rct ON rct.restaurant_id = r.id
			LEFT JOIN cuisine_tags ct ON ct.id = rct.tag_id
			WHERE r.active = 1
			  AND {$whereClause}
			GROUP BY
				r.id, r.name, r.slug, r.address, r.short_description, r.about,
				r.adult_price_cents, r.child_price_cents, r.child_max_age,
				r.seats, r.session_count, r.session_duration_minutes,
				r.session_one_start_time, r.session_two_start_time, r.session_three_start_time,
				r.rating, r.review_count, r.restaurant_image_path, r.chef_image_path,
				r.chef_name, r.chef_title, r.chef_bio, r.about_image_path, r.reservation_image_path
			LIMIT 1
		";
	}

	private function mapRowToYummyEvent(array $row): YummyEvent
	{
		$cuisineTags = !empty($row['cuisine_tags'])
			? array_map('trim', explode(',', (string) $row['cuisine_tags']))
			: [];

		return new YummyEvent(
			id:                      (int) $row['id'],
			restaurantName:          (string) $row['restaurant_name'],
			slug:                    (string) $row['slug'],
			address:                 $row['address'] ?? null,
			shortDescription:        $row['short_description'] ?? null,
			about:                   $row['about'] ?? null,
			adultPriceCents:         isset($row['adult_price_cents'])      ? (int) $row['adult_price_cents']      : null,
			childPriceCents:         isset($row['child_price_cents'])      ? (int) $row['child_price_cents']      : null,
			childMaxAge:             isset($row['child_max_age'])          ? (int) $row['child_max_age']          : null,
			seats:                   isset($row['seats'])                  ? (int) $row['seats']                  : null,
			sessionCount:            isset($row['session_count'])          ? (int) $row['session_count']          : null,
			sessionDurationMinutes:  isset($row['session_duration_minutes']) ? (int) $row['session_duration_minutes'] : null,
			sessionOneStartTime:     $row['session_one_start_time']   ?? null,
			sessionTwoStartTime:     $row['session_two_start_time']   ?? null,
			sessionThreeStartTime:   $row['session_three_start_time'] ?? null,
			rating:                  isset($row['rating'])       ? (float) $row['rating']       : null,
			reviewCount:             isset($row['review_count']) ? (int)   $row['review_count'] : null,
			restaurantImagePath:     $row['restaurant_image_path']  ?? null,
			chefImagePath:           $row['chef_image_path']        ?? null,
			chefName:                $row['chef_name']               ?? null,
			chefTitle:               $row['chef_title']              ?? null,
			chefBio:                 $row['chef_bio']                ?? null,
			aboutImagePath:          $row['about_image_path']        ?? null,
			reservationImagePath:    $row['reservation_image_path']  ?? null,
			cuisineTags:             $cuisineTags
		);
	}

	private function mapRowToYummyMenuItem(array $row): YummyMenuItem
	{
		return new YummyMenuItem(
			id:           (int) $row['id'],
			restaurantId: (int) $row['restaurant_id'],
			name:         (string) $row['name'],
			description:  $row['description'] ?? null,
			imagePath:    $row['image_path']   ?? null,
			displayOrder: (int) $row['display_order']
		);
	}
}
