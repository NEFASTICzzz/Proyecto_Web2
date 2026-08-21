@extends('layouts.app')

@section('title', 'Carrito de Compras - TechZone CR')

@section('content')
<div class="container my-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-cart3 text-primary me-2"></i>Carrito de Compras</h4>

    @if(empty($cart))
        <div class="card p-5 text-center bg-white border shadow-sm">
            <i class="bi bi-cart-x text-muted display-4 mb-3"></i>
            <h5>Tu carrito está vacío</h5>
            <p class="text-muted small">Agrega productos desde el catálogo para iniciar tu compra.</p>
            <div class="mt-2">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-bag-plus me-1"></i> Ir al Catálogo
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            <!-- Tabla de Ítems en el Carrito -->
            <div class="col-lg-8">
                <div class="card border bg-white shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th style="width: 120px;">Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded me-2" style="width: 45px; height: 45px; object-fit: contain;">
                                                <div>
                                                    <a href="{{ route('products.show', $item['slug']) }}" class="text-dark fw-semibold text-decoration-none d-block small">
                                                        {{ $item['name'] }}
                                                    </a>
                                                    <small class="text-muted">ID: #{{ $item['id'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small">₡{{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item['quantity'] }}" min="1">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Actualizar">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end fw-bold text-primary small">
                                            ₡{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen de Costos y Totales -->
            <div class="col-lg-4">
                <div class="card border bg-white shadow-sm p-3">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Resumen del Pedido</h6>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">₡{{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">IVA (13%)</span>
                        <span class="fw-semibold">₡{{ number_format($totals['tax'], 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Envío</span>
                        @if($totals['shipping'] == 0)
                            <span class="badge bg-success">Gratis</span>
                        @else
                            <span class="fw-semibold">₡{{ number_format($totals['shipping'], 0, ',', '.') }}</span>
                        @endif
                    </div>

                    @if($totals['subtotal'] < 50000)
                        <div class="alert alert-light border py-2 small mb-3 text-muted">
                            <i class="bi bi-info-circle me-1"></i> Compras superiores a ₡50.000 tienen <strong>Envío Gratis</strong>.
                        </div>
                    @endif

                    <hr class="my-2">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark">Total</span>
                        <span class="fs-5 fw-bold text-primary">₡{{ number_format($totals['total'], 0, ',', '.') }}</span>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-credit-card me-1"></i> Proceder al Pago
                        </a>
                    @else
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                Iniciar Sesión para Pagar
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm text-center">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
