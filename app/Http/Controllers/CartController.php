<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /*
     * CartController.php - Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Administra la sesion del carrito de compras, calculo automatico de IVA (13%) y costo de envio
     */

    // Muestra el carrito de compras con el desglose de totales
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        $totals = $this->calculateTotals($cart);

        return view('cart.index', compact('cart', 'totals'));
    }

    // Agrega un producto al carrito
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $quantity = $request->input('quantity', 1);

        // Validamos que haya stock suficiente
        if ($product->stock < 1) {
            return back()->with('error', 'Lo sentimos, este producto se encuentra agotado.');
        }

        // Si ya esta en el carrito, aumentamos la cantidad
        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'No puedes agregar más unidades que las disponibles en stock (' . $product->stock . ').');
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            // Si es nuevo en el carrito, guardamos sus datos clave
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'slug' => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', '¡Producto añadido al carrito correctamente!');
    }

    // Actualiza la cantidad de un producto en el carrito
    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $product = Product::find($id);

        if ($quantity <= 0) {
            return $this->remove($id);
        }

        if ($product && $quantity > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible. Máximo: ' . $product->stock);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = (int)$quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Carrito actualizado correctamente.');
    }

    // Elimina un producto del carrito
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('info', 'Producto eliminado del carrito.');
    }

    // Helper estatico/publico para calcular subtotal, IVA del 13% y envio
    public static function calculateTotals(array $cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculo de IVA (13% en Costa Rica)
        $tax = $subtotal * 0.13;

        // Envio gratis en compras mayores a 50,000 colones. Si no, cobramos 3,500 colones de tarifa plana
        $shipping = 0;
        if ($subtotal > 0 && $subtotal < 50000) {
            $shipping = 3500;
        }

        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'item_count' => count($cart),
        ];
    }
}
