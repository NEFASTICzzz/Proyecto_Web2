@extends('layouts.app')

@section('title', 'Pedido Confirmado - Factura #' . $order->tracking_number)

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Confirmación -->
            <div class="card border bg-white shadow-sm p-4 text-center mb-3">
                <div class="mb-2">
                    <i class="bi bi-check-circle text-success fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">¡Gracias por tu compra!</h4>
                <p class="text-muted small mb-2">Tu pedido ha sido procesado exitosamente. Tu número de seguimiento es:</p>
                
                <div class="my-2">
                    <span class="badge bg-primary fs-5 px-3 py-2">
                        <i class="bi bi-truck me-1"></i>{{ $order->tracking_number }}
                    </span>
                </div>
                <small class="text-muted">Conserva este código para dar seguimiento a tu paquete.</small>
            </div>

            <!-- Factura -->
            <div class="card border bg-white shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <h5 class="fw-bold text-primary mb-0"><i class="bi bi-cpu-fill me-1"></i>TechZone CR</h5>
                        <span class="small text-muted">Factura de Compra</span>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success mb-1">{{ $order->payment_status }}</span>
                        <div class="small text-muted">Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Datos del Cliente y Envío -->
                <div class="row g-2 mb-3 p-3 bg-light rounded border small">
                    <div class="col-md-6">
                        <strong>Cliente:</strong> {{ $order->user->name }} (ID #{{ $order->user_id }})<br>
                        <strong>Correo:</strong> {{ $order->user->email }}
                    </div>
                    <div class="col-md-6">
                        <strong>Pago:</strong> {{ $order->payment_method }}<br>
                        <strong>Teléfono:</strong> {{ $order->contact_phone }}<br>
                        <strong>Dirección:</strong> {{ $order->shipping_address }}
                    </div>
                </div>

                <!-- Tabla de Productos Comprados -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center small">₡{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center small">{{ $item->quantity }}</td>
                                    <td class="text-end small fw-semibold">₡{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totales de la Factura -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="p-2 bg-light rounded border small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Subtotal</span>
                                <span>₡{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>IVA (13%)</span>
                                <span>₡{{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Envío</span>
                                <span>₡{{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between fw-bold fs-6 text-primary">
                                <span>Total</span>
                                <span>₡{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Volver a la Tienda
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary btn-sm">
                        <i class="bi bi-printer me-1"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
