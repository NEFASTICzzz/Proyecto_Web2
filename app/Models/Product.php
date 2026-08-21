<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Campos asignables para el producto de la tienda
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'specs',
        'is_featured',
    ];

    // Relacion con la categoria correspondiente
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Helper para formatear el precio bonito en colones/dolares
    public function getFormattedPriceAttribute()
    {
        return '₡' . number_format($this->price, 0, ',', '.');
    }
}
