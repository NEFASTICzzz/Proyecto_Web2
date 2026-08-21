@extends('layouts.app')

@section('title', $product->name . ' - TechZone CR')

@section('content')
<div class="container my-4">
    <!-- Migas de Pan / Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Catálogo</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Tarjeta Principal del Producto -->
    <div class="card border bg-white p-4 mb-4 shadow-sm">
        <div class="row align-items-center">
            <!-- Imagen del Producto -->
            <div class="col-lg-5 mb-3 mb-lg-0 text-center">
                <div class="p-3 bg-light rounded border">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 320px; object-fit: contain;">
                </div>
            </div>

            <!-- Detalles del Producto -->
            <div class="col-lg-7">
                <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                <h3 class="fw-bold text-dark mb-2">{{ $product->name }}</h3>

                <div class="mb-3">
                    <span class="fs-3 fw-bold text-primary">{{ $product->formatted_price }}</span>
                    <span class="text-muted small ms-2">(+ 13% IVA al procesar pago)</span>
                </div>

                <p class="text-muted mb-3">{{ $product->description }}</p>

                @if($product->specs)
                    <div class="mb-3 p-3 bg-light rounded border">
                        <strong class="d-block mb-1 text-dark small"><i class="bi bi-info-circle me-1"></i> Especificaciones Técnicas:</strong>
                        <p class="small mb-0 text-muted">{{ $product->specs }}</p>
                    </div>
                @endif

                <!-- Disponibilidad de Stock -->
                <div class="mb-3">
                    @if($product->stock > 0)
                        <span class="badge bg-success">
                            Disponible ({{ $product->stock }} unidades)
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Agotado
                        </span>
                    @endif
                </div>

                <!-- Formulario Agregar al Carrito -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        <div style="width: 100px;">
                            <label class="form-label small text-muted mb-1">Cantidad</label>
                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-cart-plus me-1"></i> Agregar al Carrito
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Productos Relacionados de la misma Categoría -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-grid me-2 text-primary"></i>Productos Relacionados</h5>
            <div class="row g-3">
                @foreach($relatedProducts as $relProduct)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border bg-white shadow-sm">
                            <div class="product-img-wrapper border-bottom">
                                <img src="{{ $relProduct->image }}" alt="{{ $relProduct->name }}">
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-truncate mb-1" style="font-size: 0.9rem;">{{ $relProduct->name }}</h6>
                                <span class="fw-bold text-primary d-block mb-2">{{ $relProduct->formatted_price }}</span>
                                <a href="{{ route('products.show', $relProduct->slug) }}" class="btn btn-sm btn-outline-primary w-100">Ver Detalle</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Productos Vistos Recientemente (Cookies) -->
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div class="bg-white p-3 rounded-3 border shadow-sm mb-4">
            <h6 class="fw-bold mb-2 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Vistos Recientemente en tu Navegador
            </h6>
            <div class="row g-2">
                @foreach($recentlyViewed as $rProd)
                    <div class="col-4 col-md-2">
                        <a href="{{ route('products.show', $rProd->slug) }}" class="text-decoration-none text-dark">
                            <div class="card text-center p-2 h-100 border">
                                <img src="{{ $rProd->image }}" class="img-fluid mb-1" style="height: 60px; object-fit: contain;">
                                <small class="fw-bold d-block text-truncate">{{ $rProd->name }}</small>
                                <span class="small text-primary fw-bold">{{ $rProd->formatted_price }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
