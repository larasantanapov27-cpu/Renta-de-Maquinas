<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// CONTROLADORES
use App\Http\Controllers\login;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\EstadoRentaController;
use App\Http\Controllers\RentaController;

// RUTA PRINCIPAL
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('panel')
        : redirect()->route('login');
});

// RUTAS PARA USUARIOS NO AUTENTICADOS
Route::middleware('guest')->group(function () {

    Route::get('/login', [login::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [login::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/registro', [login::class, 'showRegister'])
        ->name('register');

    Route::post('/registro', [login::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

// RUTAS PARA USUARIOS AUTENTICADOS
Route::middleware('auth')->group(function () {

    // Panel principal
    Route::view('/panel', 'layouts.template')
        ->name('panel');

    // Personas
    Route::resource('personas', PersonaController::class);

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Direcciones de clientes
    Route::resource(
        'direcciones_clientes',
        DireccionClienteController::class
    );

    // Proveedores
    Route::resource('proveedores', ProveedorController::class);

    // Estados de renta
    Route::resource('estados_renta', EstadoRentaController::class)
        ->except(['show'])
        ->parameters([
            'estados_renta' => 'estadoRenta',
        ]);

    // Rentas: por ahora habilitamos únicamente el listado.
    Route::resource('rentas', RentaController::class)
    ->only(['index']);

    // Cerrar sesión
    Route::post('/logout', [login::class, 'logout'])
        ->name('logout');
});