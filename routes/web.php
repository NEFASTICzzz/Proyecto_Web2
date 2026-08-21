<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Rutas Principales de TechZone CR
| Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
|--------------------------------------------------------------------------
*/

// --- RUTAS PUBLICAS ---
// Pagina Principal / Home
Route::get('/', [ProductController::class, 'home'])->name('home');

// Pagina de Bienvenida por defecto de Laravel
Route::get('/laravel', function () {
    return view('welcome');
})->name('laravel.welcome');

// Catalogo y Detalle de Productos
Route::get('/catalogo', [ProductController::class, 'index'])->name('products.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('products.show');

// Carrito de Compras (Session)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/actualizar/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/carrito/eliminar/{id}', [CartController::class, 'remove'])->name('cart.remove');

// --- RUTAS DE AUTENTICACION (INVITADOS) ---
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- RUTAS DE USUARIOS AUTENTICADOS ---
Route::middleware('auth')->group(function () {
    // Cerrar Sesion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Perfil e Historial de Pedidos
    Route::get('/perfil', [AuthController::class, 'profile'])->name('profile');
    Route::post('/perfil', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Proceso de Checkout y Facturacion
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/exito/{id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Panel de Reportes PDF (Solo Admin)
    Route::get('/admin/reportes', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/admin/reportes/pdf-mes', [ReportController::class, 'pdfMonthly'])->name('reports.pdf.monthly');
    Route::get('/admin/reportes/pdf-cliente', [ReportController::class, 'pdfClient'])->name('reports.pdf.client');
});
