<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// CONTROLADORES
use App\Http\Controllers\login;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\DetalleTarifaController;
use App\Http\Controllers\PeriodoRentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MetodoPagoController;

// RUTA PRINCIPAL

Route::get('/', function () {

    // Si el usuario inició sesión, se envía al panel
    if (Auth::check()) {
        return redirect()->route('panel');
    }

    // Si no inició sesión, se envía al login
    return redirect()->route('login');

});


// RUTAS PARA USUARIOS NO AUTENTICADOS

Route::middleware('guest')->group(function () {

    // Mostrar formulario de inicio de sesión
    Route::get('/login', [login::class, 'showLogin'])
        ->name('login');

    // Procesar inicio de sesión
    Route::post('/login', [login::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    // Mostrar formulario de registro
    Route::get('/registro', [login::class, 'showRegister'])
        ->name('register');

    // Procesar registro
    Route::post('/registro', [login::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');

});


// RUTAS PARA USUARIOS AUTENTICADOS

Route::middleware('auth')->group(function () {

    // PANEL PRINCIPAL

    Route::view('/panel', 'layouts.template')
        ->name('panel');


    // PERSONAS

    Route::resource('personas', PersonaController::class);


    // CLIENTES

    Route::resource('clientes', ClienteController::class);


    // DIRECCIONES DE CLIENTES

    Route::resource(
        'direcciones_clientes',
        DireccionClienteController::class
    );


    // PROVEEDORES

    Route::resource('proveedores', ProveedorController::class);

    // TARIFAS

    Route::resource('detalle_tarifa', DetalleTarifaController::class);

    // PERIODOS DE RENTA

    Route::resource('periodos_renta',PeriodoRentaController::class  );

    //Pagos asociados a una renta
    Route::get( '/pagos/renta/{id}',[PagoController::class, 'renta'] )->name('pagos.renta');
    // PAGOS
    Route::resource( 'pagos', PagoController::class);
    
    // METODOS PAGO
    Route::resource('metodos_pago',  MetodoPagoController::class);

    // CERRAR SESIÓN

    Route::post('/logout', [login::class, 'logout'])
        ->name('logout');

});
////////////////////////no soy gei/////////////////////