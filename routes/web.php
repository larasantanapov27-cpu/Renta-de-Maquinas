
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\login;

Route::get('/', function () {
    return redirect()->route('login');
});
//////////////////////////////////// RUTAS EDITADAS POR GABRIEL//////////////////////////
// Rutas para usuarios que no han iniciado sesión
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

// Rutas para usuarios que ya iniciaron sesión
Route::middleware('auth')->group(function () {

    Route::view('/panel', 'layouts.template')
        ->name('panel');

    Route::post('/logout', [login::class, 'logout'])
        ->name('logout');
});

////////////////////////GEY EL QUE LO LEA//////////////////////////
///////////////////GEY EL QUE LO SIGA LEYENDO////////////////////////
