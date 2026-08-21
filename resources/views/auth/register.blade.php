@extends('layouts.app')

@section('title', 'Registro de Usuario - TechZone CR')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border bg-white p-4 shadow-sm">
                <div class="text-center mb-3">
                    <h4 class="fw-bold text-dark mb-1">Registro de Usuario</h4>
                    <p class="text-muted small">Crea una cuenta para comprar en la tienda</p>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small text-muted">Nombre Completo (*)</label>
                        <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Carlos Sanabria Rojas">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted">Correo Electrónico (*)</label>
                        <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="correo@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Contraseña (*)</label>
                            <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" required placeholder="Mínimo 6 caracteres">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Confirmar Contraseña (*)</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" required placeholder="Repetir clave">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted">Teléfono</label>
                        <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone') }}" placeholder="8888-8888">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Dirección de Entrega</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Provincia, Cantón y señas">{{ old('address') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        Registrarse
                    </button>

                    <div class="text-center small text-muted">
                        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
