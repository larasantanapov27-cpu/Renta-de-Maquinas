<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\login;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionClienteController;
use App\Http\Controllers\ProveedorController;

use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\TipoMaquinaController;
use App\Http\Controllers\EstadoMaquinaController;


// RUTA PRINCIPAL

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('panel');
    }

    return redirect()->route('login');

});


// USUARIOS NO AUTENTICADOS

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


// USUARIOS AUTENTICADOS

Route::middleware('auth')->group(function () {


    // PANEL

    Route::view('/panel', 'layouts.template')
        ->name('panel');


    // PERSONAS

    Route::resource(
        'personas',
        PersonaController::class
    );


    // CLIENTES

    Route::resource(
        'clientes',
        ClienteController::class
    );


    // DIRECCIONES DE CLIENTES

    Route::resource(
        'direcciones_clientes',
        DireccionClienteController::class
    );


    // PROVEEDORES

    Route::resource(
        'proveedores',
        ProveedorController::class
    );


    // MAQUINAS

    Route::resource(
        'maquinas',
        MaquinaController::class
    );


    // TIPOS DE MAQUINA

    Route::resource(
        'tipos_maquina',
        TipoMaquinaController::class
    );


    // ESTADOS DE MAQUINA

    Route::resource(
        'estados_maquina',
        EstadoMaquinaController::class
    );


    // COMPRAS

    Route::resource(
        'compras',
        ComprasController::class
    );


    // CERRAR SESION

    Route::post('/logout', [login::class, 'logout'])
        ->name('logout');

});