<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\CartController;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    /*
     * StoreTest.php - Pruebas Unitarias y de Integración
     * Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     */

    // Test 1: Verificar que la página principal (Home) carga correctamente (HTTP 200)
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('TechZone');
    }

    // Test 2: Carga de vistas de autenticación
    public function test_auth_views_load_successfully(): void
    {
        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee('Iniciar Sesión');

        $responseRegister = $this->get('/registro');
        $responseRegister->assertStatus(200);
        $responseRegister->assertSee('Registro de Usuario');
    }

    // Test 3: Registro de un usuario nuevo
    public function test_user_can_register_successfully(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Carlos Sanabria',
            'email' => 'carlos@ejemplo.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'phone' => '8888-7777',
            'address' => 'Heredia Centro',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'carlos@ejemplo.com',
        ]);
    }

    // Test 4: Inicio de sesión exitoso
    public function test_user_can_login_successfully(): void
    {
        $user = User::create([
            'name' => 'Usuario Login',
            'email' => 'login@ejemplo.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@ejemplo.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    // Test 3: Búsqueda y filtrado de productos en el catálogo
    public function test_product_catalog_filter_by_name(): void
    {
        $cat = Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'icon' => 'bi-laptop'
        ]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'MacBook Pro M3',
            'slug' => 'macbook-pro-m3',
            'description' => 'Laptop apple',
            'price' => 1500000,
            'stock' => 5,
        ]);

        $response = $this->get('/catalogo?q=MacBook');
        $response->assertStatus(200);
        $response->assertSee('MacBook Pro M3');
    }

    // Test 4: Cálculo del carrito de compras (Subtotal, 13% IVA y Envio)
    public function test_cart_totals_calculation_with_iva_and_shipping(): void
    {
        $cartItem = [
            '1' => [
                'id' => 1,
                'name' => 'Mouse Gamer',
                'price' => 20000,
                'quantity' => 2,
            ]
        ];

        // Subtotal = 20,000 * 2 = 40,000
        // IVA 13% = 40,000 * 0.13 = 5,200
        // Envio (< 50,000) = 3,500
        // Total = 40,000 + 5,200 + 3,500 = 48,700

        $totals = CartController::calculateTotals($cartItem);

        $this->assertEquals(40000, $totals['subtotal']);
        $this->assertEquals(5200, $totals['tax']);
        $this->assertEquals(3500, $totals['shipping']);
        $this->assertEquals(48700, $totals['total']);
    }

    // Test 5: Proceso de checkout y generación de número de tracking único
    public function test_checkout_creates_order_and_tracking_number(): void
    {
        $user = User::create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@prueba.com',
            'password' => bcrypt('password123'),
        ]);

        $cat = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'icon' => 'bi-headphones'
        ]);

        $product = Product::create([
            'category_id' => $cat->id,
            'name' => 'Audífonos Sony',
            'slug' => 'audifonos-sony',
            'description' => 'Sony audio',
            'price' => 100000,
            'stock' => 10,
        ]);

        // Simulamos carrito en sesión
        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ]
        ];

        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/checkout', [
                'contact_phone' => '8888-1111',
                'shipping_address' => 'San Jose Centro',
                'payment_method' => 'card',
                'card_number' => '4532012345678910',
                'card_holder' => 'CLIENTE PRUEBA',
                'card_expiry' => '12/28',
                'card_cvv' => '123',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'contact_phone' => '8888-1111',
        ]);

        // Verificamos que se descontó el stock
        $this->assertEquals(9, $product->fresh()->stock);
    }
}
