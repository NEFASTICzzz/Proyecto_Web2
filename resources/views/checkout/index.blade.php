@extends('layouts.app')

@section('title', 'Finalizar Compra / Checkout - TechZone CR')

@section('content')
<div class="container my-4">
    <h4 class="fw-bold mb-3"><i class="bi bi-credit-card text-primary me-2"></i>Proceso de Compra y Pago</h4>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-3">
            <!-- Datos de Envío y Selección de Método de Pago -->
            <div class="col-lg-7">
                <!-- 1. Información de Entrega -->
                <div class="card border bg-white p-3 mb-3 shadow-sm">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">1. Información de Entrega</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Cliente</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $user->name }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Teléfono de Contacto (*)</label>
                        <input type="text" name="contact_phone" class="form-control form-control-sm @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $user->phone ?? '8888-8888') }}" required placeholder="Ej: 8888-8888">
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Dirección de Entrega (*)</label>
                        <textarea name="shipping_address" class="form-control form-control-sm @error('shipping_address') is-invalid @enderror" rows="2" required placeholder="Provincia, Cantón y señas">{{ old('shipping_address', $user->address ?? 'San José, San Pedro') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 2. Pasarela de Pago Segura -->
                <div class="card border bg-white p-3 shadow-sm">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">2. Método de Pago</h6>
                    
                    <!-- Radio Buttons de Métodos de Pago -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="payCard" value="card" checked onclick="togglePaymentView('card')">
                            <label class="btn btn-outline-primary w-100 p-2 text-center" for="payCard">
                                <i class="bi bi-credit-card d-block mb-1"></i>
                                <span class="small d-block fw-semibold">Tarjeta</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="payPaypal" value="paypal" onclick="togglePaymentView('paypal')">
                            <label class="btn btn-outline-primary w-100 p-2 text-center" for="payPaypal">
                                <i class="bi bi-paypal d-block mb-1"></i>
                                <span class="small d-block fw-semibold">PayPal</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="paySinpe" value="sinpe" onclick="togglePaymentView('sinpe')">
                            <label class="btn btn-outline-primary w-100 p-2 text-center" for="paySinpe">
                                <i class="bi bi-phone d-block mb-1"></i>
                                <span class="small d-block fw-semibold">SINPE Móvil</span>
                            </label>
                        </div>
                    </div>

                    <!-- Formulario de Tarjeta -->
                    <div id="cardDetails" class="p-3 bg-light rounded border">
                        <span class="small fw-bold text-dark d-block mb-2"><i class="bi bi-lock me-1"></i>Datos de Tarjeta</span>
                        <div class="mb-2">
                            <label class="form-label small text-muted">Número de Tarjeta</label>
                            <input type="text" name="card_number" class="form-control form-control-sm" placeholder="4532 0123 4567 8910" value="4532012345678910" maxlength="19">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small text-muted">Titular de la Tarjeta</label>
                                <input type="text" name="card_holder" class="form-control form-control-sm" placeholder="Nombre completo" value="{{ strtoupper($user->name) }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small text-muted">Expiración</label>
                                <input type="text" name="card_expiry" class="form-control form-control-sm text-center" placeholder="MM/YY" value="12/28">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small text-muted">CVV</label>
                                <input type="password" name="card_cvv" class="form-control form-control-sm text-center" placeholder="123" value="987" maxlength="4">
                            </div>
                        </div>
                    </div>

                    <!-- Vista Informativa de PayPal -->
                    <div id="paypalDetails" class="p-3 bg-light rounded border d-none text-center">
                        <i class="bi bi-paypal text-primary fs-3 mb-1"></i>
                        <h6 class="fw-bold mb-1">Pago vía PayPal</h6>
                        <p class="small text-muted mb-0">Al confirmar, el sistema simulará la autorización y el cobro correspondiente.</p>
                    </div>

                    <!-- Vista Informativa de SINPE Móvil -->
                    <div id="sinpeDetails" class="p-3 bg-light rounded border d-none text-center">
                        <i class="bi bi-phone text-success fs-3 mb-1"></i>
                        <h6 class="fw-bold mb-1">Transferencia SINPE Móvil</h6>
                        <p class="small text-muted mb-0">Transferir al número <strong>8888-9999</strong> a nombre de <strong>TechZone CR</strong>.</p>
                    </div>
                </div>
            </div>

            <!-- Resumen de la Factura y Confirmación -->
            <div class="col-lg-5">
                <div class="card border bg-white p-3 shadow-sm sticky-top" style="top: 80px;">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Detalle de la Factura</h6>

                    <!-- Lista de Ítems resumida -->
                    <div class="mb-3" style="max-height: 180px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between align-items-center mb-1 small">
                                <span class="text-truncate" style="max-width: 200px;">
                                    {{ $item['quantity'] }}x {{ $item['name'] }}
                                </span>
                                <span class="fw-semibold">₡{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-2">

                    <!-- Desglose de Impuestos y Envíos -->
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Subtotal</span>
                        <span>₡{{ number_format($totals['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">IVA (13%)</span>
                        <span>₡{{ number_format($totals['tax'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Envío</span>
                        @if($totals['shipping'] == 0)
                            <span class="badge bg-success">Gratis</span>
                        @else
                            <span>₡{{ number_format($totals['shipping'], 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="p-2 bg-light rounded border d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark">Total</span>
                        <span class="fs-5 fw-bold text-primary">₡{{ number_format($totals['total'], 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Confirmar y Realizar Pago
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Alterna la visibilidad entre formularios de tarjeta, paypal y sinpe
    function togglePaymentView(method) {
        document.getElementById('cardDetails').classList.add('d-none');
        document.getElementById('paypalDetails').classList.add('d-none');
        document.getElementById('sinpeDetails').classList.add('d-none');

        if (method === 'card') {
            document.getElementById('cardDetails').classList.remove('d-none');
        } else if (method === 'paypal') {
            document.getElementById('paypalDetails').classList.remove('d-none');
        } else if (method === 'sinpe') {
            document.getElementById('sinpeDetails').classList.remove('d-none');
        }
    }
</script>
@endsection
