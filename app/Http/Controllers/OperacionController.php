<?php
namespace App\Http\Controllers;
use App\Services\Acceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
class OperacionController extends Controller
{
    public function abrir(Request $request)
    {
        $data=$request->validate(['modulo'=>['required',Rule::in(array_merge(array_keys(Acceso::MODULOS),array_keys(Acceso::CATALOGOS)))],'operacion'=>['required',Rule::in(['modificar','devolver','cambiar_estado'])],'id'=>'required|integer|min:1']);
        $catalogo=isset(Acceso::CATALOGOS[$data['modulo']]);
        if ($catalogo) abort_unless(Acceso::catalogo($request->user(),$data['modulo']),403);
        Gate::authorize(($catalogo?'catalogos':Acceso::modulo($data['modulo'])).'.'.$data['operacion']);
        $ruta=match($data['operacion']) {'devolver'=>'rentas.devolucion','cambiar_estado'=>'maquinas.estado_formulario',default=>$data['modulo'].'.edit'};
        abort_if(($data['operacion']==='devolver' && $data['modulo']!=='rentas') || ($data['operacion']==='cambiar_estado' && $data['modulo']!=='maquinas'),403);
        return redirect()->route($ruta,$data['id']);
    }
}
