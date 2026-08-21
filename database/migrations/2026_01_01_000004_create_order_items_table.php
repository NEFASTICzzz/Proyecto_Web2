<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabla pivote para guardar los productos comprados en cada orden
     */
    public function up(): void
    {
        // Detalle de cada item dentro de la factura
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relacion con la orden
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // El producto comprado
            $table->string('product_name'); // Guardamos el nombre por si el producto cambia despues
            $table->decimal('price', 10, 2); // Precio unitario al momento de comprar
            $table->integer('quantity'); // Cantidad comprada
            $table->decimal('subtotal', 10, 2); // price * quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
