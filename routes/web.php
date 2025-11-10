<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\SocialiteController;

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
});

// Panel vendedor 
Route::get('/vendedor', function () {
    return view('paginas.vendedor'); // crea recursos/views/paginas/vendedor.blade.php
});

// Panel delivery 
Route::get('/delivery', function () {
    return view('paginas.delivery'); // crea recursos/views/paginas/delivery.blade.php
});
require __DIR__.'/auth.php';
