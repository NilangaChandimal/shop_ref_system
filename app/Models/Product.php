<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'original_price',
        'displayed_price',
        'shop_price',
        'discount',
        'selling_price',
        'unit',
        'quantity',
        'image',
    ];

    public static function boot()
{
    parent::boot();

    static::creating(function ($product) {
        $product->selling_price = $product->displayed_price * ((100 - $product->discount) / 100);
        $product->profit = $product->selling_price - $product->original_price;
    });

    static::updating(function ($product) {
        $product->selling_price = $product->displayed_price * ((100 - $product->discount) / 100);
        $product->profit = $product->selling_price - $product->original_price;
    });
}
public function sales()
{
    return $this->belongsToMany(Sale::class)->withPivot('quantity');
}


}
