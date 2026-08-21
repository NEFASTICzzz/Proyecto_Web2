<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migracion de productos de TechZone CR
     */
    public function up(): void
    {
        // Aca se almacenan todos los productos que se muestran en el catalogo
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Llave foranea a categorias
            $table->string('name'); // Nombre del producto (ej: MacBook Pro M3)
            $table->string('slug')->unique(); // Para la URL del detalle
            $table->text('description'); // Descripcion detallada
            $table->decimal('price', 10, 2); // Precio en colones o dolares
            $table->integer('stock')->default(10); // Cantidad disponible en inventario
            $table->string('image')->nullable(); // Ruta de la imagen del producto
            $table->text('specs')->nullable(); // Especificaciones tecnicas (RAM, SSD, etc)
            $table->boolean('is_featured')->default(false); // Si sale en el banner principal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
