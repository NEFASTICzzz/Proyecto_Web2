@extends('layouts.app')

@section('title', 'Panel de Reportes PDF - TechZone CR')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Reportes de Ventas</h4>
            <small class="text-muted">Módulo Administrativo</small>
        </div>
        <span class="badge bg-secondary">Admin: {{ Auth::user()->name }}</span>
    </div>

    <!-- Cards de Resumen Estadístico -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border bg-white p-3 shadow-sm">
                <span class="text-muted small d-block mb-1">Ventas Totales Registradas</span>
                <h4 class="fw-bold text-primary mb-0">₡{{ number_format($totalGlobalSales, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border bg-white p-3 shadow-sm">
                <span class="text-muted small d-block mb-1">Total de Órdenes Procesadas</span>
                <h4 class="fw-bold text-dark mb-0">{{ $totalOrdersCount }} pedidos</h4>
            </div>
        </div>
    </div>

    <!-- 1. Reporte por Mes -->
    <div class="card border bg-white p-3 shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h6 class="fw-bold text-dark mb-0">1. Ventas por Mes</h6>
            <a href="{{ route('reports.pdf.monthly') }}" class="btn btn-outline-danger btn-sm" target="_blank">
                <i class="bi bi-download me-1"></i> Descargar PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Año y Mes</th>
                        <th class="text-center">Órdenes</th>
                        <th class="text-end">Total Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesByMonth as $month)
                        <tr>
                            <td>{{ $month->month }}</td>
                            <td class="text-center">{{ $month->total_orders }}</td>
                            <td class="text-end fw-semibold">₡{{ number_format($month->total_sales, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted small">No hay ventas registradas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Reporte por Cliente -->
    <div class="card border bg-white p-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h6 class="fw-bold text-dark mb-0">2. Ventas por Cliente</h6>
            <a href="{{ route('reports.pdf.client') }}" class="btn btn-outline-danger btn-sm" target="_blank">
                <i class="bi bi-download me-1"></i> Descargar PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Correo</th>
                        <th class="text-center">Compras</th>
                        <th class="text-end">Total Acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesByClient as $client)
                        <tr>
                            <td>#{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td class="text-muted small">{{ $client->email }}</td>
                            <td class="text-center">{{ $client->total_orders }}</td>
                            <td class="text-end fw-semibold">₡{{ number_format($client->total_spent, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted small">No hay compras de clientes registradas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
