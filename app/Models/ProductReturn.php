<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'customer_name',
        'product_name',
        'returned_quantity',
        'price_per_unit',
    ];

    
}
