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

// Panel cliente 
Route::get('/cliente', function () {
    return view('paginas.cliente'); // crea recursos/views/paginas/cliente.blade.php
})->middleware('auth'); //autentificacion para personas autorizadas

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
Route::get('/delivery', function () {
    return view('paginas.delivery'); // 
});
require __DIR__.'/auth.php';

Route::get('/delivery', function () {
    return view('paginas.delivery');
})->middleware(['auth', 'role:repartidor'])
  ->name('repartidor.panel'); // 

Route::post('/productos', [ProductoController::class, 'store'])
    ->middleware(['auth', 'role:vendedor'])
    ->name('producto.store');

// Tienda pública (lista productos publicados)
Route::get('/tienda', [ProductoController::class, 'indexPublico'])
    ->name('tienda.index');

// Rutas públicas del carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/add/{producto}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/remove/{producto}', [CartController::class, 'remove'])->name('cart.remove');


    
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
});