<?php
// Invoice.php

namespace App\Models;

class Invoice
{
    public string $invoiceNumber;
    public float $vatAmount;
    public float $totalAmount;
    public string $clientDetails;
}

