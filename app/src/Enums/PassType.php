<?php
// PassType.php

namespace App\Enums;

/**
 * Type of pass a customer can buy.
 */
enum PassType: string
{
    case DAY_PASS = 'DAY_PASS';
    case ALL_ACCESS = 'ALL_ACCESS';
}

