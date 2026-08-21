<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creado por el grupo para clasificar los productos (Laptops, Celulares, etc)
     */
    public function up(): void
    {
        // Tabla de categorias de la tienda
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: Laptops & Computadoras
            $table->string('slug')->unique(); // Para la URL bonita
            $table->text('description')->nullable(); // Detalle breve de la categoria
            $table->string('icon')->default('bi-tag'); // Icono de Bootstrap Icons
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
