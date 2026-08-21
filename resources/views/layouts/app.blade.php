<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechZone CR - Tu Tienda de Tecnología')</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="TechZone CR - La mejor tienda virtual de tecnología en Costa Rica. Laptops, smartphones, audio y accesorios al mejor precio.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Estilos Simples y Limpios -->
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-wrapper {
            flex: 1;
        }
        .product-img-wrapper {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            padding: 10px;
        }
        .product-img-wrapper img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar Principal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary fs-3" href="{{ route('home') }}">
                <i class="bi bi-cpu-fill me-2"></i>Tech<span class="text-white">Zone</span> <span class="badge bg-primary fs-6">CR</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Buscador rápido en el Header -->
                <form class="d-flex mx-auto my-2 my-lg-0 w-50" action="{{ route('products.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control border-end-0 bg-light" placeholder="Buscar laptops, celulares, audífonos..." value="{{ request('q') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item me-2">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active fw-bold' : '' }}" href="{{ route('products.index') }}">Catálogo</a>
                    </li>
                    
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item me-2">
                                <a class="nav-link text-warning fw-bold" href="{{ route('reports.index') }}">
                                    <i class="bi bi-graph-up me-1"></i> Reportes PDF
                                </a>
                            </li>
                        @endif
                    @endauth

                    <!-- Carrito de Compras -->
                    @php
                        $cart = session()->get('cart', []);
                        $cartCount = array_sum(array_column($cart, 'quantity'));
                    @endphp
                    <li class="nav-item me-3 position-relative">
                        <a class="btn btn-outline-light position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- Autenticación / Menú de Usuario -->
                    @guest
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Ingresar</a>
                            <a class="btn btn-primary" href="{{ route('register') }}">Registrarse</a>
                        </li>
                    @else
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person-circle me-2"></i> Mi Perfil y Pedidos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Notificaciones Flash -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <div class="main-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <h6 class="fw-bold mb-2"><i class="bi bi-cpu-fill text-primary me-2"></i>TechZone CR</h6>
                    <p class="small text-muted mb-0">Proyecto Final - Tecnologías y Sistemas Web II (ITI-523).<br>Desarrollado por Dylan Sanabria, Dylan Cerda y Cristian Rojas.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold mb-2">Enlaces</h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-muted">Inicio</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Catálogo</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Carrito de Compras</a></li>
                        <li><a href="{{ route('laravel.welcome') }}" class="text-decoration-none text-muted" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i>Laravel Default</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-2">Seguridad y Pagos</h6>
                    <p class="small text-muted mb-1">Métodos de pago: Tarjeta de Crédito/Débito, PayPal y SINPE Móvil.</p>
                    <p class="small text-muted mb-0"><i class="bi bi-shield-check text-success me-1"></i> Transacciones y contraseñas cifradas.</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex flex-column flex-md-row justify-content-between small text-muted">
                <span>&copy; {{ date('Y') }} TechZone CR - Todos los derechos reservados.</span>
                <span>Universidad / ITI-523</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
