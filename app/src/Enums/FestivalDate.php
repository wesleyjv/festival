<?php

namespace App\Enums;

enum FestivalDate: string
{
    case Wed23July = '2026-07-23';
    case Thu24July = '2026-07-24';
    case Fri25July = '2026-07-25';
    case Sat26July = '2026-07-26';

    public function label(): string
    {
        return match ($this) {
            self::Wed23July => 'Wed 23 July',
            self::Thu24July => 'Thu 24 July',
            self::Fri25July => 'Fri 25 July',
            self::Sat26July => 'Sat 26 July',
        };
    }
}
