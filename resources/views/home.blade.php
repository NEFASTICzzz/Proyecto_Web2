@extends('layouts.app')

@section('title', 'TechZone CR - Tienda de Tecnología')

@section('content')
<div class="container my-4">
    <!-- Banner Principal -->
    <div class="p-4 p-md-5 mb-4 bg-white rounded-3 border shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 fw-bold text-dark mb-2">Bienvenido a TechZone CR</h1>
                <p class="lead text-muted fs-6 mb-4">
                    Tienda virtual de productos tecnológicos en Costa Rica. Encuentra laptops, accesorios, audio y celulares con envíos a todo el país y pagos seguros.
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="bi bi-grid-3x3-gap-fill me-1"></i> Ver Catálogo Completo
                </a>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bi bi-laptop display-1 text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Categorías -->
    <div class="mb-4">
        <h4 class="fw-bold mb-3"><i class="bi bi-tags text-primary me-2"></i>Categorías</h4>
        <div class="row g-3">
            @foreach($categories as $cat)
                <div class="col-6 col-md-3">
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                        <div class="card text-center p-3 h-100 border bg-white shadow-sm">
                            <div class="fs-2 text-primary mb-1">
                                <i class="bi {{ $cat->icon }}"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $cat->name }}</h6>
                            <span class="text-muted small">{{ $cat->products_count }} productos</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Productos Destacados -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Productos Destacados</h4>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
        </div>
        <div class="row g-3">
            @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border bg-white shadow-sm">
                        <div class="product-img-wrapper border-bottom">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge bg-secondary mb-1 align-self-start" style="font-size:0.7rem;">{{ $product->category->name }}</span>
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                            <p class="small text-muted mb-2 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.85rem;">
                                {{ $product->description }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="fw-bold text-primary">{{ $product->formatted_price }}</span>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                    Ver Detalle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Sección de Productos Vistos Recientemente (Cookies) -->
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div class="mb-4 bg-white p-3 rounded-3 border shadow-sm">
            <h5 class="fw-bold mb-2 text-dark">
                <i class="bi bi-clock-history text-primary me-2"></i>Productos Vistos Recientemente
                <span class="badge bg-light text-muted border ms-2 small">Historial de Cookies</span>
            </h5>
            <div class="row g-2">
                @foreach($recentlyViewed as $rProduct)
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="card h-100 border text-center p-2">
                            <img src="{{ $rProduct->image }}" alt="{{ $rProduct->name }}" class="img-fluid mb-2" style="height: 90px; object-fit: contain;">
                            <span class="small fw-bold text-truncate d-block mb-1">{{ $rProduct->name }}</span>
                            <span class="small text-primary fw-bold mb-2">{{ $rProduct->formatted_price }}</span>
                            <a href="{{ route('products.show', $rProduct->slug) }}" class="btn btn-sm btn-outline-secondary py-0" style="font-size: 0.75rem;">Ver</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
