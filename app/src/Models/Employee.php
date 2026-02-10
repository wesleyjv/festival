<?php
// Employee.php

namespace App\Models;

class Employee extends User
{
    public function scanTicket(string $code): void
    {
        // Ticket scanning logic handled in services; this is a domain stub.
    }
}
