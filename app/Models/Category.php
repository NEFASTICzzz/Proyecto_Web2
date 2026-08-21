<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Campos masivos para la categoria
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    // Una categoria tiene muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
