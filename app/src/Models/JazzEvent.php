<?php
// JazzEvent.php

namespace App\Models;

class JazzEvent extends Event
{
    public int $eventId;
    public string $artist;
    public string $style;
    public string $description;
    public ?string $profileImage;
    public ?string $bannerImage;
    public ?string $location;
    public ?string $startTime;
    public ?string $endTime;
    public ?float $price;
    public ?int $seats;
    public ?array $images;
    public ?array $tracks;
}
