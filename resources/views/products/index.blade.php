@extends('layouts.app')

@section('title', 'Catálogo de Productos - TechZone CR')

@section('content')
<div class="container my-4">
    <div class="row">
        <!-- Sidebar de Filtros -->
        <div class="col-lg-3 mb-4">
            <div class="card p-3 border bg-white shadow-sm">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel text-primary me-2"></i>Filtros</h5>
                
                <form action="{{ route('products.index') }}" method="GET">
                    <!-- Búsqueda por palabra clave -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre o palabra clave</label>
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('q') }}">
                    </div>

                    <!-- Categorías -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Categoría</label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rango de precios -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Precio Mínimo (₡)</label>
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="0" value="{{ request('min_price') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Precio Máximo (₡)</label>
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="2000000" value="{{ request('max_price') }}">
                    </div>

                    <!-- Ordenar Por -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Ordenar por</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más Recientes</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search me-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grilla de Productos -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Catálogo de Productos</h4>
                <span class="text-muted small">Total: {{ $products->total() }} productos</span>
            </div>

            @if($products->isEmpty())
                <div class="card p-4 text-center bg-white border shadow-sm">
                    <p class="text-muted mb-3">No se encontraron productos con los criterios seleccionados.</p>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">Ver Todo el Catálogo</a>
                    </div>
                </div>
            @else
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
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
                                        <div>
                                            <span class="fw-bold text-primary">{{ $product->formatted_price }}</span>
                                            @if($product->stock > 0)
                                                <small class="d-block text-success" style="font-size:0.7rem;">Stock: {{ $product->stock }}</small>
                                            @else
                                                <small class="d-block text-danger" style="font-size:0.7rem;">Agotado</small>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Sección Cookie Recientes en Catálogo -->
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div class="mt-4 bg-white p-3 rounded-3 border shadow-sm">
            <h6 class="fw-bold mb-2"><i class="bi bi-clock-history text-primary me-2"></i>Vistos Recientemente</h6>
            <div class="row g-2">
                @foreach($recentlyViewed as $rProd)
                    <div class="col-4 col-md-2">
                        <a href="{{ route('products.show', $rProd->slug) }}" class="text-decoration-none text-dark">
                            <div class="card text-center p-2 h-100 border">
                                <img src="{{ $rProd->image }}" class="img-fluid mb-1" style="height: 60px; object-fit: contain;">
                                <small class="d-block text-truncate fw-semibold">{{ $rProd->name }}</small>
                                <span class="small text-primary">{{ $rProd->formatted_price }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
