<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /*
     * CheckoutController.php - Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Procesa la pasarela de pago simulada, asigna numero de seguimiento y genera la factura
     */

    // Muestra la vista de checkout con los campos del cliente y opciones de pago
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Tu carrito está vacío. Agrega productos antes de pagar.');
        }

        $totals = CartController::calculateTotals($cart);
        $user = Auth::user();

        return view('checkout.index', compact('cart', 'totals', 'user'));
    }

    // Procesa la compra y simula la respuesta de la pasarela de pago (Tarjeta / PayPal)
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'El carrito está vacío.');
        }

        // Validamos la informacion de envio y metodo de pago
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:card,paypal,sinpe',
        ]);

        // Si selecciono tarjeta de credito, validamos los datos del plastico
        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => 'required|string|min:15|max:19',
                'card_holder' => 'required|string|max:255',
                'card_expiry' => 'required|string',
                'card_cvv' => 'required|string|min:3|max:4',
            ], [
                'card_number.min' => 'Ingresa un número de tarjeta válido (16 dígitos).',
            ]);
        }

        $totals = CartController::calculateTotals($cart);

        // --- GENERAMOS EL NUMERO DE SEGUIMIENTO UNICO ---
        // Ejemplo: TRK-983421
        $trackingNumber = 'TRK-' . strtoupper(Str::random(8));

        // Creamos la Factura / Orden en la base de datos
        $order = Order::create([
            'user_id' => Auth::id(),
            'tracking_number' => $trackingNumber,
            'subtotal' => $totals['subtotal'],
            'tax_amount' => $totals['tax'],
            'shipping_amount' => $totals['shipping'],
            'total_amount' => $totals['total'],
            'payment_method' => $this->getPaymentName($request->payment_method),
            'payment_status' => 'Pagado y Aprobado',
            'shipping_address' => $request->shipping_address,
            'contact_phone' => $request->contact_phone,
            'status' => 'En Preparación',
        ]);

        // Guardamos cada item comprado y descontamos el stock del producto
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            // Descontamos stock del inventario
            $prod = Product::find($item['id']);
            if ($prod) {
                $prod->decrement('stock', $item['quantity']);
            }
        }

        // Limpiamos la sesion del carrito despues de la compra exitosa
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id)->with('success', '¡Compra procesada con éxito!');
    }

    // Pantalla de confirmacion del pedido con detalle de factura y numero de seguimiento
    public function success($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    // Traduce la clave a texto bonito para la factura
    private function getPaymentName($key)
    {
        switch ($key) {
            case 'card':
                return 'Tarjeta de Crédito / Débito (Visa/Mastercard)';
            case 'paypal':
                return 'PayPal Checkout Seguro';
            case 'sinpe':
                return 'SINPE Móvil / Transferencia';
            default:
                return 'Pago Electrónico';
        }
    }
}
