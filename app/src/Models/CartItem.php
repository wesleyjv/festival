<?php
namespace App\Models;

class CartItem
{
    public int $quantity;
    public float $price;
    public ?Session $session = null;
}