@extends('layouts.app')

@section('title', 'Mi Perfil y Pedidos - TechZone CR')

@section('content')
<div class="container my-4">
    <div class="row g-3">
        <!-- Tarjeta del Perfil -->
        <div class="col-lg-4">
            <div class="card border bg-white p-3 shadow-sm">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width:60px; height:60px; font-size: 1.5rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>

                <!-- Formulario de Edición de Datos Personales -->
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <h6 class="fw-bold mb-2 border-bottom pb-2 small">Datos Personales</h6>

                    <div class="mb-2">
                        <label class="form-label small text-muted">Nombre</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted">Teléfono</label>
                        <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Dirección</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <h6 class="fw-bold mb-2 border-bottom pb-2 small">Cambiar Contraseña</h6>
                    <div class="mb-2">
                        <input type="password" name="current_password" class="form-control form-control-sm" placeholder="Contraseña actual">
                    </div>
                    <div class="mb-2">
                        <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Nueva contraseña">
                    </div>
                    <div class="mb-3">
                        <input type="password" name="new_password_confirmation" class="form-control form-control-sm" placeholder="Confirmar contraseña">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <!-- Historial de Pedidos -->
        <div class="col-lg-8">
            <div class="card border bg-white p-3 shadow-sm">
                <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-clock-history text-primary me-2"></i>Historial de Pedidos</h5>

                @if($orders->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted small mb-2">No has realizado ninguna compra todavía.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Ver Catálogo</a>
                    </div>
                @else
                    <div class="accordion" id="ordersAccordion">
                        @foreach($orders as $index => $order)
                            <div class="accordion-item mb-2 border">
                                <h2 class="accordion-header" id="heading{{ $order->id }}">
                                    <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}">
                                        <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                                            <div>
                                                <strong class="text-primary small">Tracking: {{ $order->tracking_number }}</strong>
                                                <small class="text-muted d-block">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <div>
                                                <span class="badge bg-success me-2">{{ $order->status }}</span>
                                                <span class="fw-bold small">₡{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $order->id }}" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                    <div class="accordion-body small bg-white">
                                        <p class="mb-1"><strong>Método:</strong> {{ $order->payment_method }} | <strong>Teléfono:</strong> {{ $order->contact_phone }}</p>
                                        <p class="mb-2"><strong>Dirección:</strong> {{ $order->shipping_address }}</p>

                                        <ul class="list-group list-group-flush mb-2">
                                            @foreach($order->items as $item)
                                                <li class="list-group-item d-flex justify-content-between px-0 py-1">
                                                    <span>{{ $item->quantity }}x {{ $item->product_name }}</span>
                                                    <span class="fw-semibold">₡{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <div class="text-end">
                                            <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                Ver Factura
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
