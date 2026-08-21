<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas por Mes - TechZone CR</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0d6efd; font-size: 22px; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 11px; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #0d6efd; color: white; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-box { margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #0d6efd; font-size: 14px; font-weight: bold; text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TechZone CR - Tienda Virtual</h1>
        <p>Reporte Estadístico de Ventas Mensuales | Curso: Tecnologías y Sistemas Web II (ITI-523)</p>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Fecha de Generación:</strong> {{ date('d/m/Y H:i:s') }}</td>
            <td class="text-right"><strong>Integrantes:</strong> Dylan Sanabria, Dylan Cerda, Cristian Rojas</td>
        </tr>
    </table>

    <h3>Resumen de Ventas por Mes</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Año / Mes</th>
                <th class="text-center">Órdenes Atendidas</th>
                <th class="text-right">Monto Facturado (₡)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesByMonth as $row)
                <tr>
                    <td>{{ $row->month }}</td>
                    <td class="text-center">{{ $row->total_orders }}</td>
                    <td class="text-right">₡{{ number_format($row->total_sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Global de Ventas: ₡{{ number_format($totalGlobalSales, 0, ',', '.') }} ({{ $totalOrders }} órdenes procesadas)
    </div>

    <div class="footer">
        Documento generado automáticamente por el sistema TechZone CR. Válido para la evaluación de la Sesión 14.
    </div>
</body>
</html>
