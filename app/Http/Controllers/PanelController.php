<?php
namespace App\Http\Controllers;
use App\Services\Acceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PanelController extends Controller
{
    public function redirect(Request $request) { return redirect()->route(Acceso::inicio($request->user())); }
    public function index(Request $request)
    {
        abort_unless($request->route()->getName() === Acceso::inicio($request->user()),403);
        $rol=$request->user()->rol()->first(); $indicadores=[];
        if (Acceso::permite($request->user(),'usuarios.consultar')) foreach(['pendiente','activo','inactivo'] as $estado) $indicadores['Cuentas '.$estado]=DB::table('users')->where('estado',$estado)->count();
        foreach (['clientes'=>'clientes','maquinas'=>'maquinas','rentas'=>'rentas','pagos'=>'pagos','compras'=>'compras','mantenimientos'=>'mantenimientos'] as $modulo=>$tabla) {
            if (Acceso::permite($request->user(),"$modulo.consultar")) $indicadores[ucfirst($modulo)]=DB::table($tabla)->count();
        }
        return view('paneles.inicio',compact('rol','indicadores'));
    }
}
