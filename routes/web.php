<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\login;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\DetalleTarifaController;
use App\Http\Controllers\PeriodoRentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\EstadoRentaController;
use App\Http\Controllers\RentaController;

// CONTROLADORES DE MANTENIMIENTO
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\TipoMantenimientoController;
use App\Http\Controllers\BajaMaquinaController;
use App\Http\Controllers\MotivoBajaController;

// CONTROLADORES DE MAQUINAS Y COMPRAS
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\TipoMaquinaController;
use App\Http\Controllers\EstadoMaquinaController;


/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('panel');
    }

    return redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| USUARIOS NO AUTENTICADOS
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // LOGIN
    Route::get('/login', [login::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [login::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');


    // REGISTRO
    Route::get('/registro', [login::class, 'showRegister'])
        ->name('register');

    Route::post('/registro', [login::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');

});


/*
|--------------------------------------------------------------------------
| USUARIOS AUTENTICADOS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | PANEL PRINCIPAL
    |--------------------------------------------------------------------------
    */

    Route::view('/panel', 'layouts.template')
        ->name('panel');


    /*
    |--------------------------------------------------------------------------
    | PERSONAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'personas',
        PersonaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'clientes',
        ClienteController::class
    );


    /*
    |--------------------------------------------------------------------------
    | DIRECCIONES DE CLIENTES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'direcciones_clientes',
        DireccionClienteController::class
    );


    /*
    |--------------------------------------------------------------------------
    | PROVEEDORES
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'proveedores',
        ProveedorController::class
    );


    /*
    |--------------------------------------------------------------------------
    | MAQUINAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'maquinas',
        MaquinaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE MAQUINA
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'tipos_maquina',
        TipoMaquinaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | ESTADOS DE MAQUINA
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'estados_maquina',
        EstadoMaquinaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | COMPRAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'compras',
        ComprasController::class
    );


    /*
    |--------------------------------------------------------------------------
    | TARIFAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'detalle_tarifa',
        DetalleTarifaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | PERIODOS DE RENTA
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'periodos_renta',
        PeriodoRentaController::class
    );


    /*
    |--------------------------------------------------------------------------
    | MANTENIMIENTOS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'mantenimientos',
        MantenimientoController::class
    );


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE MANTENIMIENTO
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'tipos-mantenimiento',
        TipoMantenimientoController::class
    )
    ->except(['index', 'show'])
    ->names('tipos_mantenimiento');


    /*
    |--------------------------------------------------------------------------
    | BAJAS DE MAQUINAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'bajas-maquinas',
        BajaMaquinaController::class
    )
    ->names('bajas_maquinas');


    /*
    |--------------------------------------------------------------------------
    | MOTIVOS DE BAJA
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'motivos-baja',
        MotivoBajaController::class
    )
    ->except(['index', 'show'])
    ->names('motivos_baja');


    /*
    |--------------------------------------------------------------------------
    | PAGOS ASOCIADOS A UNA RENTA
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pagos/renta/{id}',
        [PagoController::class, 'renta']
    )
    ->name('pagos.renta');


    /*
    |--------------------------------------------------------------------------
    | PAGOS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'pagos',
        PagoController::class
    );


    /*
    |--------------------------------------------------------------------------
    | METODOS DE PAGO
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'metodos_pago',
        MetodoPagoController::class
    );


    /*
    |--------------------------------------------------------------------------
    | ESTADOS DE RENTA
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'estados_renta',
        EstadoRentaController::class
    )
    ->except(['show'])
    ->parameters([
        'estados_renta' => 'estadoRenta',
    ]);


    /*
    |--------------------------------------------------------------------------
    | RENTAS
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'rentas',
        RentaController::class
    )
    ->only(['index']);


    /*
    |--------------------------------------------------------------------------
    | CERRAR SESION
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [login::class, 'logout']
    )
    ->name('logout');

});