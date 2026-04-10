<?php

namespace App\ViewModels;

use App\Models\YummyEvent;

/** Carries all data needed to render the reservation overview and confirmation pages. */
final readonly class ReservationOverviewViewModel
{
    public function __construct(
        /** Full restaurant record, used for name, image, child_max_age, slug, etc. */
        public YummyEvent $restaurant,

        /** 1, 2, or 3 — identifies which session the guest selected. */
        public int $sessionNumber,

        /** Human-readable date shown to the user, e.g. "Wed 23 July". */
        public string $festivalDate,

        /** ISO date string stored in the DB, e.g. "2026-07-23". */
        public string $festivalDateRaw,

        public int $adults,
        public int $children,
        public string $specialRequest,

        /** Per-person price in cents as stored in the restaurants table. */
        public int $adultPriceCents,
        public int $childPriceCents,

        /** adults × adultPriceCents */
        public int $adultTotalCents,

        /** children × childPriceCents */
        public int $childTotalCents,

        /** adultTotalCents + childTotalCents */
        public int $grandTotalCents,

        /** (adults + children) × 1000 — €10 flat fee per person. */
        public int $reservationFeeCents,

        /** Session start time formatted as "HH:MM", e.g. "17:00". */
        public string $sessionStartTime,

        /** Calculated end time (start + session_duration_minutes), e.g. "19:30". */
        public string $sessionEndTime,
    ) {
    }
}
