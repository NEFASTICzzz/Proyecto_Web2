<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'quantity',
        'subtotal',
    ];

    // Pertenece a una orden
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Puede pertenecer a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
