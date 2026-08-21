<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabla de ordenes / facturas de la tienda
     */
    public function up(): void
    {
        // Aqui guardamos la factura final de cada compra realizada por los usuarios
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que hizo la compra
            $table->string('tracking_number')->unique(); // Numero de seguimiento (ej: TRK-983421)
            $table->decimal('subtotal', 10, 2); // Subtotal sin impuestos ni envio
            $table->decimal('tax_amount', 10, 2); // Monto del IVA (13%)
            $table->decimal('shipping_amount', 10, 2); // Costo del envio
            $table->decimal('total_amount', 10, 2); // Monto final total de la compra
            $table->string('payment_method'); // Tarjeta de Credito, PayPal, etc.
            $table->string('payment_status')->default('Completado'); // Estado del pago
            $table->text('shipping_address'); // Direccion de envio ingresada en el checkout
            $table->string('contact_phone'); // Telefono para el repartidor
            $table->string('status')->default('Procesando'); // Procesando, Enviado, Entregado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
