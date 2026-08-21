<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cookie;

class ProductController extends Controller
{
    /*
     * ProductController.php - Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Administra la navegacion por el catalogo, busqueda, filtros y las cookies de vistos recientemente
     */

    // Pagina de inicio principal (Home)
    public function home(Request $request)
    {
        // Productos destacados para el slider/banner
        $featuredProducts = Product::where('is_featured', true)->take(6)->get();
        // Todas las categorias con contador de productos
        $categories = Category::withCount('products')->get();
        // Productos ultimos agregados
        $latestProducts = Product::latest()->take(8)->get();

        // Leemos la galleta de vistos recientemente
        $recentlyViewed = $this->getRecentlyViewedFromCookie($request);

        return view('home', compact('featuredProducts', 'categories', 'latestProducts', 'recentlyViewed'));
    }

    // Catalogo completo con busqueda y filtrado por nombre, categoria, precio y orden
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. Busqueda por nombre o descripcion si enviaron parametro 'q'
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('specs', 'like', "%{$search}%");
            });
        }

        // 2. Filtrado por categoria si seleccionaron alguna
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 3. Filtrado por rango de precios (minimo y maximo)
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Ordenamiento (precio menor a mayor, mayor a menor, nombre, etc)
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        // Paginamos los resultados de 9 en 9 para que se vea limpio
        $products = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        // Obtenemos los vistos recientemente desde la galleta/cookie
        $recentlyViewed = $this->getRecentlyViewedFromCookie($request);

        return view('products.index', compact('products', 'categories', 'recentlyViewed'));
    }

    // Muestra el detalle completo de un producto y guarda el ID en la cookie de recien vistos
    public function show(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        // Productos relacionados de la misma categoria
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // --- MANEJO DE COOKIE DE PRODUCTOS VISTOS RECIENTEMENTE ---
        // 1. Leemos el array actual almacenado en la cookie 'recently_viewed'
        $rawCookie = $request->cookie('recently_viewed');
        $viewedIds = $rawCookie ? json_decode($rawCookie, true) : [];

        if (!is_array($viewedIds)) {
            $viewedIds = [];
        }

        // 2. Si el producto actual ya estaba en la lista, lo quitamos para volverlo a poner de primero
        if (($key = array_search($product->id, $viewedIds)) !== false) {
            unset($viewedIds[$key]);
        }

        // 3. Agregamos el ID al inicio del arreglo
        array_unshift($viewedIds, $product->id);

        // 4. Limitamos el historial a los ultimos 6 productos vistos para no saturar la cookie
        $viewedIds = array_slice($viewedIds, 0, 6);

        // 5. Preparamos la cookie por 30 dias (43200 minutos)
        $cookie = cookie('recently_viewed', json_encode($viewedIds), 43200);

        // Obtenemos los objetos completos para mostrar en la seccion "Vistos recientemente"
        $recentlyViewed = Product::whereIn('id', array_diff($viewedIds, [$product->id]))->get();

        // Retornamos la vista adjuntando la galleta
        return response()
            ->view('products.show', compact('product', 'relatedProducts', 'recentlyViewed'))
            ->cookie($cookie);
    }

    // Helper privado para recuperar los productos vistos desde la cookie
    private function getRecentlyViewedFromCookie(Request $request)
    {
        $rawCookie = $request->cookie('recently_viewed');
        if (!$rawCookie) {
            return collect();
        }

        $ids = json_decode($rawCookie, true);
        if (!is_array($ids) || empty($ids)) {
            return collect();
        }

        // Retornamos la coleccion respetando el orden guardado
        return Product::whereIn('id', $ids)->get();
    }
}
