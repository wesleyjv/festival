<?php

namespace App\Services;

use App\Enums\CuisineType;
use App\Models\ShoppingCart;
use App\Repositories\Interfaces\IYummyRepository;
use App\Services\Interfaces\IYummyService;
use App\ViewModels\ReservationOverviewViewModel;
use App\ViewModels\YummyDetailViewModel;
use App\ViewModels\YummyOverviewViewModel;

/** Assembles the view models needed by the public Yummy event pages. */
class YummyService implements IYummyService
{
	/** ISO date values accepted for festival_date, mapped to their display labels. */
	private const FESTIVAL_DATES = [
		'2026-07-23' => 'Wed 23 July',
		'2026-07-24' => 'Thu 24 July',
		'2026-07-25' => 'Fri 25 July',
		'2026-07-26' => 'Sat 26 July',
	];

	/** Reservation fee in cents charged per person regardless of age. */
	private const RESERVATION_FEE_CENTS_PER_PERSON = 1000;

	/** Only this fraction of a restaurant's seats may be sold to individual reservations. */
	private const SELLABLE_SEATS_RATIO = 0.9;


	public function __construct(
		private readonly IYummyRepository $yummyRepository,
		private readonly ContentService $contentService
	) {
	}

	/**
	 * Silently drops an unrecognised cuisine filter rather than showing an empty list or an error.
	 * CuisineType::isValid guards against arbitrary values being passed to the repository.
	 */
	public function getOverviewViewModel(?string $selectedCuisine = null): YummyOverviewViewModel
	{
		if ($selectedCuisine !== null && $selectedCuisine !== '' && !CuisineType::isValid($selectedCuisine)) {
			$selectedCuisine = null;
		}

		$content     = $this->contentService->getPageContent('yummy');
		$restaurants = $this->yummyRepository->findAllActiveRestaurants($selectedCuisine);
		$cuisines    = $this->yummyRepository->findAllAvailableCuisines();

		return new YummyOverviewViewModel(
			content: $content,
			restaurants: $restaurants,
			cuisines: $cuisines,
			selectedCuisine: $selectedCuisine
		);
	}

	/**
	 * Fetches the restaurant and its menu items by slug and assembles the detail view model.
	 * Returns null when no active restaurant matches the slug.
	 */
	public function getRestaurantDetailViewModel(string $slug): ?YummyDetailViewModel
	{
		$restaurant = $this->yummyRepository->findActiveRestaurantBySlug($slug);

		if ($restaurant === null) {
			return null;
		}

		$menuItems   = $this->yummyRepository->findMenuItemsByRestaurantId($restaurant->id);
		$pageContent = $this->contentService->getPageContent('yummy');

		return new YummyDetailViewModel(
			restaurant: $restaurant,
			menuItems: $menuItems,
			pageContent: $pageContent
		);
	}

	/** Looks up an active restaurant's URL slug by its primary key; returns null when not found or inactive. */
	public function getRestaurantSlugById(int $restaurantId): ?string
	{
		return $this->yummyRepository->findRestaurantById($restaurantId)?->slug;
	}

	/**
	 * Returns the number of seats still available for individual reservations in the
	 * given session, applying the same 90% capacity rule as the reservation flow.
	 *
	 * @throws \InvalidArgumentException for any invalid parameter.
	 */
	public function getRemainingSeats(int $restaurantId, string $festivalDate, int $sessionNumber): int
	{
		if (!in_array($sessionNumber, [1, 2, 3], true)) {
			throw new \InvalidArgumentException('Invalid session number. Choose 1, 2, or 3.');
		}
		if (!array_key_exists($festivalDate, self::FESTIVAL_DATES)) {
			throw new \InvalidArgumentException('Invalid festival date.');
		}

		$restaurant = $this->yummyRepository->findRestaurantById($restaurantId);
		if ($restaurant === null) {
			throw new \InvalidArgumentException('Restaurant not found.');
		}

		return $this->computeRemainingSeats($restaurant, $sessionNumber, $festivalDate);
	}

	/**
	 * Validates reservation parameters, fetches the restaurant, calculates session
	 * times and price totals, and returns an assembled ReservationOverviewViewModel.
	 *
	 * @throws \InvalidArgumentException for any invalid or missing parameter.
	 */
	public function buildReservationOverviewViewModel(array $params): ReservationOverviewViewModel
	{
		$restaurantId  = isset($params['restaurant_id'])  ? (int) $params['restaurant_id']  : 0;
		$sessionNumber = isset($params['session_number']) ? (int) $params['session_number'] : 0;
		$adults        = isset($params['adults'])         ? (int) $params['adults']         : 0;
		$children      = isset($params['children'])       ? (int) $params['children']       : 0;
		$specialRequest = trim($params['special_request'] ?? '');
		$festivalDateRaw = $params['festival_date'] ?? '';

		if ($restaurantId <= 0) {
			throw new \InvalidArgumentException('Invalid restaurant.');
		}
		if (!in_array($sessionNumber, [1, 2, 3], true)) {
			throw new \InvalidArgumentException('Invalid session number. Choose 1, 2, or 3.');
		}
		if ($adults + $children <= 0) {
			throw new \InvalidArgumentException('At least one guest is required.');
		}
		if (!array_key_exists($festivalDateRaw, self::FESTIVAL_DATES)) {
			throw new \InvalidArgumentException('Invalid festival date.');
		}

		$restaurant = $this->yummyRepository->findRestaurantById($restaurantId);
		if ($restaurant === null) {
			throw new \InvalidArgumentException('Restaurant not found.');
		}

		[$startTime, $endTime] = $this->resolveSessionTimes($restaurant, $sessionNumber);

		$this->ensureCapacityAvailable($restaurant, $sessionNumber, $festivalDateRaw, $adults, $children);

		$adultPriceCents = $restaurant->adultPriceCents ?? 0;
		$childPriceCents = $restaurant->childPriceCents ?? 0;
		$adultTotalCents = $adults   * $adultPriceCents;
		$childTotalCents = $children * $childPriceCents;

		return new ReservationOverviewViewModel(
			restaurant:           $restaurant,
			sessionNumber:        $sessionNumber,
			festivalDate:         self::FESTIVAL_DATES[$festivalDateRaw],
			festivalDateRaw:      $festivalDateRaw,
			adults:               $adults,
			children:             $children,
			specialRequest:       $specialRequest,
			adultPriceCents:      $adultPriceCents,
			childPriceCents:      $childPriceCents,
			adultTotalCents:      $adultTotalCents,
			childTotalCents:      $childTotalCents,
			grandTotalCents:      $adultTotalCents + $childTotalCents,
			reservationFeeCents:  ($adults + $children) * self::RESERVATION_FEE_CENTS_PER_PERSON,
			sessionStartTime:     $startTime,
			sessionEndTime:       $endTime,
		);
	}

	/**
	 * Validates all reservation parameters, calculates the fee, and persists the
	 * reservation via the repository.
	 *
	 * @return int The newly inserted reservation's primary key.
	 * @throws \InvalidArgumentException for any invalid or missing parameter.
	 */
	public function saveReservation(array $params): int
	{
		$restaurantId  = isset($params['restaurant_id'])  ? (int) $params['restaurant_id']  : 0;
		$sessionNumber = isset($params['session_number']) ? (int) $params['session_number'] : 0;
		$adults        = isset($params['adults'])         ? (int) $params['adults']         : 0;
		$children      = isset($params['children'])       ? (int) $params['children']       : 0;
		$festivalDateRaw = $params['festival_date'] ?? '';
		$specialRequest  = trim($params['special_request'] ?? '') ?: null;
		$userId          = isset($params['user_id']) && $params['user_id'] !== null
		                   ? (int) $params['user_id'] : null;

		if ($restaurantId <= 0) {
			throw new \InvalidArgumentException('Invalid restaurant.');
		}
		if (!in_array($sessionNumber, [1, 2, 3], true)) {
			throw new \InvalidArgumentException('Invalid session number.');
		}
		if ($adults + $children <= 0) {
			throw new \InvalidArgumentException('At least one guest is required.');
		}
		if (!array_key_exists($festivalDateRaw, self::FESTIVAL_DATES)) {
			throw new \InvalidArgumentException('Invalid festival date.');
		}

		return $this->yummyRepository->saveReservation([
			'restaurant_id'        => $restaurantId,
			'session_number'       => $sessionNumber,
			'festival_date'        => $festivalDateRaw,
			'adults'               => $adults,
			'children'             => $children,
			'special_request'      => $specialRequest,
			'reservation_fee_cents'=> ($adults + $children) * self::RESERVATION_FEE_CENTS_PER_PERSON,
			'user_id'              => $userId,
		]);
	}

	// ── Private helpers ───────────────────────────────────────────────────────

	/**
	 * Returns the number of seats still available for individual reservations in the
	 * given session. Only SELLABLE_SEATS_RATIO of the restaurant's seats are sellable
	 * to individual reservations; the rest is reserved for walk-ins/groups.
	 */
	private function computeRemainingSeats($restaurant, int $sessionNumber, string $festivalDateRaw): int
	{
		$usableSeats = (int) floor(($restaurant->seats ?? 0) * self::SELLABLE_SEATS_RATIO);
		$reserved    = $this->yummyRepository->countReservedSeatsForSession($restaurant->id, $festivalDateRaw, $sessionNumber);

		return $usableSeats - $reserved;
	}

	/**
	 * Throws when the requested guest count would exceed the session's remaining capacity.
	 *
	 * @throws \InvalidArgumentException when there is not enough remaining capacity.
	 */
	private function ensureCapacityAvailable($restaurant, int $sessionNumber, string $festivalDateRaw, int $adults, int $children): void
	{
		$remaining = $this->computeRemainingSeats($restaurant, $sessionNumber, $festivalDateRaw);

		if ($adults + $children > $remaining) {
			if ($remaining <= 0) {
				throw new \InvalidArgumentException('This session is fully booked.');
			}
			throw new \InvalidArgumentException("Only {$remaining} seat(s) left for this session.");
		}
	}

	/**
	 * Picks the correct start time for the given session number and calculates the
	 * end time by adding session_duration_minutes.
	 *
	 * @return array{string, string} [startTime, endTime] formatted as "HH:MM"
	 * @throws \InvalidArgumentException when the session has no configured start time.
	 */
	private function resolveSessionTimes($restaurant, int $sessionNumber): array
	{
		$startTimes = [
			1 => $restaurant->sessionOneStartTime,
			2 => $restaurant->sessionTwoStartTime,
			3 => $restaurant->sessionThreeStartTime,
		];

		$rawStart = $startTimes[$sessionNumber] ?? null;
		if (empty($rawStart)) {
			throw new \InvalidArgumentException('The selected session is not available for this restaurant.');
		}

		$startDt = \DateTime::createFromFormat('H:i:s', $rawStart)
		         ?: \DateTime::createFromFormat('H:i', $rawStart);

		if ($startDt === false) {
			throw new \InvalidArgumentException('Invalid session start time stored in the database.');
		}

		$endDt = clone $startDt;
		if ($restaurant->sessionDurationMinutes !== null) {
			$endDt->modify('+' . $restaurant->sessionDurationMinutes . ' minutes');
		}

		return [$startDt->format('H:i'), $endDt->format('H:i')];
	}

	/**
	 * Validates params, saves the reservation, creates a template ticket row,
	 * links the ticket to the reservation, and pushes it into the session cart.
	 *
	 * Calling buildReservationOverviewViewModel() first ensures all validation
	 * runs before any writes happen — same guard used by the controller.
	 */
	public function createAndCartReservation(array $params): void
	{
		// Validate params and compute formatted name parts.
		$vm = $this->buildReservationOverviewViewModel($params);

		// Re-check capacity right before persisting to close the gap between
		// building the overview and confirming the reservation.
		$this->ensureCapacityAvailable($vm->restaurant, $vm->sessionNumber, $vm->festivalDateRaw, $vm->adults, $vm->children);

		// Persist the reservation row.
		$reservationId = $this->saveReservation($params);

		// Build a human-readable ticket name.
		$ticketName = sprintf(
			'Yummy – %s | %s | Session %d %s–%s',
			$vm->restaurant->restaurantName,
			$vm->festivalDate,
			$vm->sessionNumber,
			$vm->sessionStartTime,
			$vm->sessionEndTime
		);

		// Insert template ticket (event_id = 0, no order/user, empty code).
		$ticketId = $this->yummyRepository->createReservationTicket([
			'name'  => $ticketName,
			'price' => $vm->reservationFeeCents / 100,
		]);

		// Link the ticket back to the reservation row.
		$this->yummyRepository->updateReservationTicketId($reservationId, $ticketId);

		// Load the full Ticket model so ShoppingCart has all properties populated.
		$ticketService = new TicketService();
		$ticket        = $ticketService->getTicketById($ticketId);

		if ($ticket === null) {
			throw new \RuntimeException('Could not load the reservation ticket after insertion.');
		}

		// Add to session cart — quantity 1 (fee covers all guests).
		$cart = $_SESSION['cart'] ?? new ShoppingCart();
		$cart->addItem($ticket, 1);
		$_SESSION['cart'] = $cart;
	}
}
