# 🛒 TechZone CR — Tienda Virtual de Productos Tecnológicos

**Proyecto Final — Tecnologías y Sistemas Web II (ITI-523)**  
**Universidad Técnica Nacional**  
**Profesora: Ing. Milena Vargas Blanco**

---

## 👥 Integrantes del Equipo

| Nombre | Rol |
|---|---|
| **Dylan Sanabria** | Seguridad, Autenticación, Perfil de Usuario y Base de Datos |
| **Dylan Cerda** | Catálogo, Búsqueda, Filtros, Cookies y Maquetación Frontend |
| **Cristian Rojas** | Carrito, Pasarela de Pago, Facturación, Tracking, Reportes PDF y Pruebas Unitarias |

---

## 📋 Descripción

**TechZone CR** es una tienda virtual completa para la venta de productos tecnológicos (laptops, smartphones, audio y accesorios) en Costa Rica. Incluye autenticación segura, catálogo con filtros dinámicos, carrito de compras con cálculo automático del **13% de IVA** y costo de envío, pasarela de pago simulada (Tarjeta / PayPal / SINPE), generación de facturas con **número de seguimiento único** (`TRK-XXXXXXXX`), historial de pedidos, cookies de productos vistos recientemente y reportes de ventas exportables a PDF.

---

## 🛠️ Stack Tecnológico

| Componente | Tecnología |
|---|---|
| **Backend** | PHP 8.2+ con Laravel 12 |
| **Base de Datos** | SQLite |
| **Frontend** | HTML5, CSS3, Bootstrap 5.3.2, Bootstrap Icons, JavaScript |
| **Reportes PDF** | Barryvdh/DomPDF |
| **Pruebas** | PHPUnit (9 tests, 24 aserciones) |
| **Servidor Local** | Apache (XAMPP) o `php artisan serve` |

---

## ⚙️ Instalación y Ejecución Local

### Requisitos previos
- PHP 8.2 o superior (con extensión `pdo_sqlite` habilitada)
- Composer 2.0+
- XAMPP / WAMP o la CLI de Laravel

### Pasos

```bash
# 1. Clonar o extraer el proyecto
cd c:\xampp\htdocs\   # o el directorio preferido

# 2. Instalar dependencias de PHP
composer install

# 3. Copiar el archivo de configuración (si no existe .env)
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Ejecutar migraciones y sembrar datos iniciales
php artisan migrate:fresh --seed

# 6. Iniciar el servidor de desarrollo
php artisan serve
```

Abrir en el navegador: **http://127.0.0.1:8000**

---

## 🔑 Cuentas de Demostración

| Tipo | Correo | Contraseña |
|---|---|---|
| **Administrador** (Reportes PDF) | `admin@techzone.cr` | `admin123` |
| **Cliente** (Compras de prueba) | `estudiante@tienda.com` | `12345678` |

---

## 🧩 Funcionalidades Principales

- ✅ Registro e inicio de sesión con contraseñas cifradas (Bcrypt) y protección CSRF
- ✅ Regeneración de sesión al login y destrucción al logout (prevención de Session Fixation)
- ✅ Catálogo con búsqueda por palabra clave, filtros por categoría, rango de precios y ordenamiento
- ✅ Sistema de cookies HTTP (`recently_viewed`) para productos vistos recientemente
- ✅ Carrito de compras con validación de stock en tiempo real
- ✅ Cálculo automático de Subtotal, 13% IVA y Envío (gratis en compras ≥ ₡50.000)
- ✅ Pasarela de pago simulada: Tarjeta de Crédito/Débito, PayPal y SINPE Móvil
- ✅ Generación de factura con número de seguimiento único (`TRK-XXXXXXXX`)
- ✅ Descuento automático de stock al completar la compra
- ✅ Perfil de usuario con historial de pedidos y cambio de contraseña
- ✅ Reportes PDF de ventas por mes y por cliente (solo Administrador)
- ✅ 9 pruebas automatizadas con PHPUnit

---

## 🧪 Pruebas Unitarias

```bash
php artisan test
```

Resultado esperado: **OK (9 tests, 24 assertions)**

---

## 📁 Estructura de Archivos Clave

```
app/Http/Controllers/
├── AuthController.php        ← Registro, Login, Logout, Perfil
├── ProductController.php     ← Catálogo, Filtros, Cookies
├── CartController.php        ← Carrito, Cálculo IVA y Envío
├── CheckoutController.php    ← Pasarela de Pago, Tracking
└── ReportController.php      ← Reportes PDF (Admin)

app/Models/
├── User.php
├── Product.php
├── Category.php
├── Order.php
└── OrderItem.php

resources/views/
├── home.blade.php
├── layouts/app.blade.php
├── auth/ (login, register)
├── products/ (index, show)
├── cart/ (index)
├── checkout/ (index, success)
├── profile/ (index)
└── reports/ (index, pdf_monthly, pdf_client)

tests/Feature/
└── StoreTest.php             ← 9 pruebas automatizadas

database/migrations/          ← 7 migraciones (users, categories, products, orders, order_items...)
```

---

## 📄 Documentación Adicional

Consultar el archivo [DOCUMENTACION.md](DOCUMENTACION.md) para información técnica detallada incluyendo:
- Diagrama de caso de uso (Mermaid)
- Estructura completa de la base de datos
- Instrucciones de hosting y certificado SSL
- Guía de publicación en GitHub

---

> **Proyecto desarrollado por Dylan Sanabria, Dylan Cerda y Cristian Rojas — 2026**
