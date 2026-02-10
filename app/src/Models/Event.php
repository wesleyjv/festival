<?php
// Event.php

namespace App\Models;

/**
 * Base abstract event type.
 */
abstract class Event
{
    public int $id;
    public string $name;
    public string $description;
    public string $image;

    public function __construct(int $id, string $name, string $description, string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }
}

