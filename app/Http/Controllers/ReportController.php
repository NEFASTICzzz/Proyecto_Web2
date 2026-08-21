<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /*
     * ReportController.php - Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Genera los reportes estadisticos de ventas por mes y por cliente exportables a PDF
     */

    // Pagina del panel de reportes
    public function index()
    {
        // Verificamos que sea administrador
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado. Se requieren permisos de Administrador para ver los reportes de la tienda.');
        }

        // 1. Reporte de ventas agrupado por Mes y Año
        $salesByMonth = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw("COUNT(*) as total_orders"),
                DB::raw("SUM(total_amount) as total_sales")
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // 2. Reporte de ventas agrupado por Cliente
        $salesByClient = User::select('users.id', 'users.name', 'users.email')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->get();

        $totalGlobalSales = Order::sum('total_amount');
        $totalOrdersCount = Order::count();

        return view('reports.index', compact('salesByMonth', 'salesByClient', 'totalGlobalSales', 'totalOrdersCount'));
    }

    // Exporta el reporte de ventas por mes a archivo PDF descargable
    public function pdfMonthly()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $salesByMonth = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw("COUNT(*) as total_orders"),
                DB::raw("SUM(total_amount) as total_sales")
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $totalGlobalSales = Order::sum('total_amount');
        $totalOrders = Order::count();

        // Generamos el PDF con Dompdf usando la vista Blade especifica para PDF
        $pdf = Pdf::loadView('reports.pdf_monthly', compact('salesByMonth', 'totalGlobalSales', 'totalOrders'));

        return $pdf->download('Reporte_Ventas_Mensual_TechZone.pdf');
    }

    // Exporta el reporte de ventas por cliente a archivo PDF descargable
    public function pdfClient()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Acceso denegado.');
        }

        $salesByClient = User::select('users.id', 'users.name', 'users.email')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.pdf_client', compact('salesByClient'));

        return $pdf->download('Reporte_Ventas_Por_Cliente_TechZone.pdf');
    }
}
