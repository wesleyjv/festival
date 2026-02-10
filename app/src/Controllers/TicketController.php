<?php

namespace App\Controllers;

class TicketController
{
    public function tickets($vars = [])
    {
        // Render the homepage view
        require __DIR__ . '/../views/tickets/index.php';
    }
}
