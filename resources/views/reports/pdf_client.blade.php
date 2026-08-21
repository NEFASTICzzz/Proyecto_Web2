<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas por Cliente - TechZone CR</title>
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
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TechZone CR - Tienda Virtual</h1>
        <p>Reporte Estadístico de Ventas Acumuladas por Cliente | ITI-523</p>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Fecha de Generación:</strong> {{ date('d/m/Y H:i:s') }}</td>
            <td class="text-right"><strong>Integrantes:</strong> Dylan Sanabria, Dylan Cerda, Cristian Rojas</td>
        </tr>
    </table>

    <h3>Ranking de Ventas por Cliente</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre del Cliente</th>
                <th>Correo Electrónico</th>
                <th class="text-center">Órdenes Realizadas</th>
                <th class="text-right">Monto Total Consumido (₡)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesByClient as $client)
                <tr>
                    <td class="text-center">#{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td class="text-center">{{ $client->total_orders }}</td>
                    <td class="text-right">₡{{ number_format($client->total_spent, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema TechZone CR. Válido para la evaluación de la Sesión 14.
    </div>
</body>
</html>
