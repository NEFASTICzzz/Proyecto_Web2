@extends('layouts.app')

@section('title', 'Iniciar Sesión - TechZone CR')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border bg-white p-4 shadow-sm">
                <div class="text-center mb-3">
                    <h4 class="fw-bold text-dark mb-1">Iniciar Sesión</h4>
                    <p class="text-muted small">Ingresa a tu cuenta de TechZone</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small text-muted">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="usuario@correo.com">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-sm" required placeholder="••••••••">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Recordar sesión</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Ingresar
                    </button>

                    <div class="text-center small text-muted">
                        ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate</a>
                    </div>
                </form>

                <!-- Credenciales demo -->
                <div class="mt-3 p-2 bg-light rounded border small text-muted">
                    <strong class="d-block text-dark mb-1">Cuentas de Prueba:</strong>
                    <div><strong>Admin:</strong> admin@techzone.cr / admin123</div>
                    <div><strong>Cliente:</strong> estudiante@tienda.com / 12345678</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
