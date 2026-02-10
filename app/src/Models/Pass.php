<?php
// Pass.php

namespace App\Models;

use App\Enums\PassType;

class Pass
{
    public PassType $type;
    public \DateTimeInterface $validDate;
}
