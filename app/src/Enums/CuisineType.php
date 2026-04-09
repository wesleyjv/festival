<?php

namespace App\Enums;

enum CuisineType: string
{
    case French = 'French';
    case Seafood = 'Seafood';
    case Dutch = 'Dutch';
    case Vegan = 'Vegan';
    case FineDining = 'Fine Dining';
    case International = 'International';

    public static function values(): array
    {
        return array_map(
            static fn(self $case) => $case->value,
            self::cases()
        );
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}