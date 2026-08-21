<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que se pueden llenar de forma masiva (fillable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    /**
     * Atributos ocultos en serializaciones
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casteos de tipos de datos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Un usuario puede tener muchas ordenes/compras hechas
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'desc');
    }

    // Helper rapido para verificar si el usuario es administrador
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
