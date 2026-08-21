<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Creado por Dylan Sanabria, Dylan Cerda y Cristian Rojas
     * Carga de datos iniciales para la tienda TechZone CR
     */
    public function run(): void
    {
        // 1. Creamos el usuario Administrador y un usuario de prueba
        User::create([
            'name' => 'Profesor / Admin TechZone',
            'email' => 'admin@techzone.cr',
            'password' => Hash::make('admin123'),
            'phone' => '8888-9999',
            'address' => 'San Jose, Costa Rica - Oficinas Centrales TechZone',
            'role' => 'admin', // Rol admin para ver reportes
        ]);

        User::create([
            'name' => 'Estudiante Demostración',
            'email' => 'estudiante@tienda.com',
            'password' => Hash::make('12345678'),
            'phone' => '8765-4321',
            'address' => 'Heredia, Costa Rica',
            'role' => 'cliente',
        ]);

        // 2. Creamos las Categorias principales
        $catLaptops = Category::create([
            'name' => 'Laptops & Computadoras',
            'slug' => 'laptops-computadoras',
            'description' => 'Portátiles potentes para trabajo, estudio y gaming de última generación.',
            'icon' => 'bi-laptop',
        ]);

        $catSmartphones = Category::create([
            'name' => 'Smartphones & Celulares',
            'slug' => 'smartphones-celulares',
            'description' => 'Los últimos teléfonos inteligentes de gama alta y media.',
            'icon' => 'bi-phone',
        ]);

        $catAudio = Category::create([
            'name' => 'Audio & Auriculares',
            'slug' => 'audio-auriculares',
            'description' => 'Audífonos con cancelación de ruido y parlantes Bluetooth premium.',
            'icon' => 'bi-headphones',
        ]);

        $catAccesorios = Category::create([
            'name' => 'Accesorios & Periféricos',
            'slug' => 'accesorios-perifericos',
            'description' => 'Teclados mecánicos, mouses gamer, monitores y cargadores rápidos.',
            'icon' => 'bi-keyboard',
        ]);

        // 3. Insertamos Productos Reales
        // Laptops
        Product::create([
            'category_id' => $catLaptops->id,
            'name' => 'MacBook Pro 16" M3 Max',
            'slug' => 'macbook-pro-16-m3-max',
            'description' => 'Potencia pura para desarrolladores y creadores de contenido. Chip Apple M3 Max con CPU de 16 núcleos y GPU de 40 núcleos.',
            'price' => 1850000.00,
            'stock' => 8,
            'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=80',
            'specs' => '36GB RAM Unified | 1TB SSD NVMe | Pantalla Liquid Retina XDR 120Hz',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $catLaptops->id,
            'name' => 'ASUS ROG Zephyrus G16 Gaming',
            'slug' => 'asus-rog-zephyrus-g16',
            'description' => 'Laptop Gamer ultra delgada con chasis de aluminio, procesador Intel Core i9 de 14va generación y tarjeta RTX 4080.',
            'price' => 1420000.00,
            'stock' => 5,
            'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Intel i9 14900HX | 32GB DDR5 | RTX 4080 12GB | 1TB SSD OLED 240Hz',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $catLaptops->id,
            'name' => 'Lenovo ThinkPad X1 Carbon Gen 11',
            'slug' => 'lenovo-thinkpad-x1-carbon-gen-11',
            'description' => 'El estándar de oro para productividad empresarial. Peso pluma con fibra de carbono y batería de hasta 15 horas.',
            'price' => 980000.00,
            'stock' => 12,
            'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Intel Core i7 1365U | 16GB LPDDR5 | 512GB SSD | Pantalla IPS 14" FHD+',
            'is_featured' => false,
        ]);

        // Smartphones
        Product::create([
            'category_id' => $catSmartphones->id,
            'name' => 'iPhone 15 Pro Max 256GB Titanium',
            'slug' => 'iphone-15-pro-max-256gb',
            'description' => 'Construido en titanio de grado aeroespacial. Chip A17 Pro y la cámara con teleobjetivo 5x más avanzada en un celular.',
            'price' => 790000.00,
            'stock' => 15,
            'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Chip A17 Pro | Pantalla Super Retina XDR 6.7" ProMotion | Cámara 48MP',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $catSmartphones->id,
            'name' => 'Samsung Galaxy S24 Ultra 512GB',
            'slug' => 'samsung-galaxy-s24-ultra',
            'description' => 'La revolución de la Inteligencia Artificial con Galaxy AI. S-Pen integrado, marco de titanio y zoom óptico 10x.',
            'price' => 820000.00,
            'stock' => 10,
            'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Snapdragon 8 Gen 3 | 12GB RAM | AMOLED 2X 120Hz | Cámara 200MP',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $catSmartphones->id,
            'name' => 'Google Pixel 8 Pro 128GB',
            'slug' => 'google-pixel-8-pro',
            'description' => 'Fotografía impulsada por IA con el chip Tensor G3. Actualizaciones directas garantizadas durante 7 años.',
            'price' => 540000.00,
            'stock' => 7,
            'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Google Tensor G3 | 12GB RAM | Pantalla OLED LTPO 120Hz | IP68',
            'is_featured' => false,
        ]);

        // Audio
        Product::create([
            'category_id' => $catAudio->id,
            'name' => 'Sony WH-1000XM5 Wireless Headphones',
            'slug' => 'sony-wh-1000xm5',
            'description' => 'Líder de la industria en cancelación activa de ruido con dos procesadores y 8 micrófonos. Sonido de alta resolución.',
            'price' => 210000.00,
            'stock' => 20,
            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Batería 30 Horas | Carga Rápida USB-C | Multipunto Bluetooth 5.2 | LDAC',
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $catAudio->id,
            'name' => 'AirPods Pro 2da Gen USB-C',
            'slug' => 'airpods-pro-2-usbc',
            'description' => 'Cancelación de ruido hasta 2x superior, audio espacial personalizado y estuche MagSafe con parlante integrado.',
            'price' => 155000.00,
            'stock' => 25,
            'image' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Chip H2 | Resistencia al agua IP54 | Carga USB-C / MagSafe',
            'is_featured' => false,
        ]);

        // Accesorios
        Product::create([
            'category_id' => $catAccesorios->id,
            'name' => 'Teclado Mecánico Keychron Q1 Pro Wireless',
            'slug' => 'keychron-q1-pro-wireless',
            'description' => 'Teclado custom inalámbrico de aluminio CNC con switches lubricados Gateron G Pro y soporte QMK/VIA.',
            'price' => 125000.00,
            'stock' => 14,
            'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Switches Red Lubricados | Conexión Bluetooth 5.1 & Cable | RGB por tecla',
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $catAccesorios->id,
            'name' => 'Mouse Gamer Logitech G Pro X Superlight 2',
            'slug' => 'logitech-g-pro-x-superlight-2',
            'description' => 'Ultra liviano de solo 60g utilizado por atletas de esports. Sensor HERO 2 con resolución de 32,000 DPI.',
            'price' => 95000.00,
            'stock' => 18,
            'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=600&auto=format&fit=crop&q=80',
            'specs' => 'Sensor HERO 2 | Switch Híbrido Óptico-Mecánico | Autonomía 95 Horas',
            'is_featured' => false,
        ]);
    }
}
