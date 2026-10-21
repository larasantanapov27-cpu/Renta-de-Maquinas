<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// LOGIN Y HOME
use App\Http\Controllers\login;
use App\Http\Controllers\HomeController;

// PERSONAS, CLIENTES Y PROVEEDORES
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DireccionClienteController;
use App\Http\Controllers\ProveedorController;

// TARIFAS, RENTAS Y PAGOS
use App\Http\Controllers\DetalleTarifaController;
use App\Http\Controllers\PeriodoRentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\EstadoRentaController;
use App\Http\Controllers\RentaController;

// MANTENIMIENTO Y BAJAS
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\TipoMantenimientoController;
use App\Http\Controllers\BajaMaquinaController;
use App\Http\Controllers\MotivoBajaController;

// MAQUINAS Y COMPRAS
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

Route::post('/logout', [login::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth','acceso'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HOME / PANEL PRINCIPAL
    |--------------------------------------------------------------------------
    */

    Route::get('/panel', [\App\Http\Controllers\PanelController::class, 'redirect'])
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
        ->parameters([
            'estados_renta' => 'estadoRenta',
        ]);


    /*
    |--------------------------------------------------------------------------
    | RENTAS
    |--------------------------------------------------------------------------
    */

    Route::get('/rentas/{id}/devolucion', [RentaController::class, 'devolucion'])->name('rentas.devolucion');
    Route::patch('/rentas/{id}/devolver', [RentaController::class, 'devolver'])->name('rentas.devolver');
    Route::get('/maquinas/{id}/estado', [MaquinaController::class, 'estadoFormulario'])->name('maquinas.estado_formulario');
    Route::patch('/maquinas/{id}/estado', [MaquinaController::class, 'estado'])->name('maquinas.estado');
    Route::get('/operaciones/abrir', [\App\Http\Controllers\OperacionController::class, 'abrir'])->name('operaciones.abrir');
    Route::resource(
        'rentas',
        RentaController::class
    )
        ->except(['destroy']);


    /*
    |--------------------------------------------------------------------------
    | CERRAR SESION
    |--------------------------------------------------------------------------
    */

    foreach (\App\Services\Acceso::INICIOS as $clave=>$nombre) {
        Route::get('/inicio/'.$clave, [\App\Http\Controllers\PanelController::class,'index'])->name($nombre);
    }
    Route::get('/accesos/usuarios',[\App\Http\Controllers\AccesoController::class,'usuarios'])->name('accesos.usuarios');
    Route::patch('/accesos/usuarios/{id}',[\App\Http\Controllers\AccesoController::class,'usuario'])->name('accesos.usuario');
    Route::get('/accesos/roles',[\App\Http\Controllers\AccesoController::class,'roles'])->name('accesos.roles');
    Route::patch('/accesos/roles/{id}',[\App\Http\Controllers\AccesoController::class,'rol'])->name('accesos.rol');
    Route::get('/accesos/permisos',[\App\Http\Controllers\AccesoController::class,'permisos'])->name('accesos.permisos');
    Route::put('/accesos/permisos/{id}',[\App\Http\Controllers\AccesoController::class,'permisosGuardar'])->name('accesos.permisos.guardar');
});
