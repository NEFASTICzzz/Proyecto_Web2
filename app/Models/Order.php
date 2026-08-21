<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Campos de la orden de compra / factura
    protected $fillable = [
        'user_id',
        'tracking_number',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'contact_phone',
        'status',
    ];

    // La orden pertenece a un usuario registrado
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una orden contiene varios productos comprados (items)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Formateo del monto total
    public function getFormattedTotalAttribute()
    {
        return '₡' . number_format($this->total_amount, 0, ',', '.');
    }
}
