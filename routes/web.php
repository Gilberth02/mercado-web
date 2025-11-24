<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//inicio session con google 
Route::get('/google/redirect', [SocialiteController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [SocialiteController::class, 'callback'])->name('google.callback');

Route::get('/', function () {
    return view('paginas.inicio');
});

// Login
//Route::get('/login', function () {
  //  return view('paginas.login');
//});

// Registro
//Route::get('/registro', function () {
 //   return view('paginas.registro');
//});

// Panel cliente (muestra opciones para el cliente: transformarse en vendedor o delivery)
Route::get('/cliente', function () {
    return view('paginas.cliente');
})->middleware('auth')->name('cliente.redirect');

// Panel vendedor 
Route::get('/vendedor', [ProductoController::class, 'create'])
     ->middleware(['auth', 'role:vendedor'])
     ->name('vendedor.panel');

// Registro de vendedor (formulario y envío)
Route::middleware('auth')->group(function () {
    Route::get('/vendedor/registro', [VendedorController::class, 'showRegistro'])
        ->name('vendedor.registro.show');

    Route::post('/vendedor/registro', [VendedorController::class, 'storeRegistro'])
        ->name('vendedor.registro.store');
});

Route::middleware('auth')->group(function () {
    
    // Ruta para MOSTRAR el formulario de registro de repartidor
    Route::get('/repartidor/registro', [RepartidorController::class, 'showRegistro'])
         ->name('repartidor.registro.show');
         
    // Ruta para PROCESAR ese formulario
    Route::post('/repartidor/registro', [RepartidorController::class, 'storeRegistro'])
         ->name('repartidor.registro.store');
});

// Panel delivery 
// Repartidor panel (handled by controller to show pedidos)
Route::get('/delivery', [RepartidorController::class, 'panel'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.panel');

// Toggle disponibilidad (repartidor)
Route::post('/repartidor/toggle-disponible', [RepartidorController::class, 'toggleDisponible'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.toggle_disponible');
// Rutas para acciones del repartidor: asignar y marcar entregado
Route::post('/repartidor/asignar/{pedido}', [RepartidorController::class, 'asignar'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.asignar');

Route::post('/repartidor/entregar/{pedido}', [RepartidorController::class, 'marcarEntregado'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.entregar');

Route::post('/repartidor/por-recoger/{pedido}', [RepartidorController::class, 'marcarPorRecoger'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.por_recoger');

Route::post('/repartidor/en-camino/{pedido}', [RepartidorController::class, 'marcarEnCamino'])
    ->middleware(['auth', 'role:repartidor'])
    ->name('repartidor.en_camino');
require __DIR__.'/auth.php';

Route::post('/productos', [ProductoController::class, 'store'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('producto.store');

// Toggle activo por parte del vendedor (habilitar / deshabilitar su propio producto)
Route::patch('/vendedor/productos/{producto}/toggle', [ProductoController::class, 'toggleActivo'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('vendedor.productos.toggle');

// Rutas para editar/actualizar/eliminar un producto por parte del vendedor
Route::get('/vendedor/productos/{producto}/edit', [ProductoController::class, 'edit'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('vendedor.productos.edit');

Route::patch('/vendedor/productos/{producto}', [ProductoController::class, 'update'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('vendedor.productos.update');

Route::delete('/vendedor/productos/{producto}', [ProductoController::class, 'destroy'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('vendedor.productos.destroy');

// Tienda pública (lista productos publicados)
Route::get('/tienda', [ProductoController::class, 'indexPublico'])
    ->name('tienda.index');

// Rutas públicas del carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/add/{producto}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/remove/{producto}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/carrito/update/{producto}', [CartController::class, 'update'])->name('cart.update');
Route::post('/carrito/clear', [CartController::class, 'clear'])->name('cart.clear');
// Checkout público: formulario y procesamiento
Route::get('/carrito/checkout', [CartController::class, 'checkoutForm'])->name('cart.checkout.form');
Route::post('/carrito/checkout', [CartController::class, 'processCheckout'])->name('cart.checkout.process');


    
// Rutas para el panel de administración

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Ruta principal del panel 
    // URL: /admin/productos
    Route::get('/productos', [AdminController::class, 'index'])->name('productos.index');
    
    // Ruta para APROBAR un producto 
    
    Route::patch('/productos/{producto}/aprobar', [AdminController::class, 'aprobar'])
         ->name('productos.aprobar');
         
    // Ruta para RECHAZAR un producto (botón)
    
    Route::delete('/productos/{producto}/rechazar', [AdminController::class, 'rechazar'])
         ->name('productos.rechazar');

        // Ruta para alternar activo (habilitar / deshabilitar)
        Route::patch('/productos/{producto}/toggle', [AdminController::class, 'toggleActivo'])
            ->name('productos.toggle');

    Route::get('/repartidores', [AdminController::class, 'showRepartidores'])
            ->name('repartidores.index');

    Route::get('/categorias', [AdminController::class, 'showCategorias'])
            ->name('categorias.index');
         
    // Para PROCESAR el formulario de nueva categoría (POST)
    Route::post('/categorias', [AdminController::class, 'storeCategoria'])
         ->name('categorias.store');

    Route::get('/tienda', [ProductoController::class, 'indexPublico'])->name('tienda.index');

    //carrito
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/add/{producto}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/carrito/remove/{producto}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/carrito/update/{producto}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/carrito/clear', [CartController::class, 'clear'])->name('cart.clear');
    // Checkout
    Route::get('/carrito/checkout', [CartController::class, 'checkoutForm'])->name('cart.checkout.form');
    Route::post('/carrito/checkout', [CartController::class, 'processCheckout'])->name('cart.checkout.process');
});