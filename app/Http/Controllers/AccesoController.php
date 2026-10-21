<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Rol;
use App\Models\Permiso;
use App\Services\Acceso;
use App\Services\ContinuidadAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class AccesoController extends Controller
{
    public function usuarios() { Gate::authorize('usuarios.consultar'); return view('accesos.usuarios',['usuarios'=>User::with('rol')->orderBy('id')->paginate(25),'roles'=>Rol::where('activo',1)->get()]); }
    public function usuario(Request $request, int $id)
    {
        $data=$request->validate(['operacion'=>['required',Rule::in(['aprobar','rechazar','activar','desactivar','rol'])],'id_rol'=>['nullable','integer','exists:roles,id_rol']]);
        $permiso=match($data['operacion']) {'aprobar','rechazar'=>'usuarios.validar','activar','desactivar'=>'usuarios.cambiar_estado','rol'=>'usuarios.asignar_rol'};
        Gate::authorize($permiso);
        if ($data['operacion']==='aprobar') Gate::authorize('usuarios.asignar_rol');
        ContinuidadAdmin::cambiar(function() use($id,$data) {
            $user=User::findOrFail($id);
            if (in_array($data['operacion'],['aprobar','rechazar'])) abort_unless($user->estado==='pendiente',422,'La cuenta ya fue validada.');
            if (in_array($data['operacion'],['aprobar','rol'])) {
                $rol=Rol::where('activo',1)->find($data['id_rol'] ?? 0);
                if (!$rol) throw \Illuminate\Validation\ValidationException::withMessages(['id_rol'=>'Selecciona un rol activo.']);
                $user->id_rol=$rol->id_rol;
            }
            if ($data['operacion']==='activar') {
                abort_unless($user->estado==='inactivo' && $user->rol()->where('activo',1)->exists(),422,'Solo se reactiva una cuenta inactiva con rol activo.');
            }
            if ($data['operacion']==='desactivar') abort_unless($user->estado==='activo',422,'Solo se desactiva una cuenta activa; las pendientes requieren aprobación o rechazo.');
            $user->estado=match($data['operacion']) {'aprobar','activar'=>'activo','rechazar'=>'rechazado','desactivar'=>'inactivo',default=>$user->estado};
            $user->save();
        });
        return back()->with('success','Cuenta actualizada.');
    }
    public function roles() { Gate::authorize('roles.consultar'); return view('accesos.roles',['roles'=>Rol::all()]); }
    public function rol(Request $request,int $id)
    {
        Gate::authorize('roles.cambiar_estado'); $data=$request->validate(['activo'=>'required|boolean']);
        ContinuidadAdmin::cambiar(fn()=>Rol::findOrFail($id)->update(['activo'=>$data['activo']]));
        return back()->with('success','Estado del rol actualizado.');
    }
    public function permisos() { Gate::authorize('permisos.consultar'); return view('accesos.permisos',['roles'=>Rol::with('permisos')->get(),'grupos'=>Permiso::orderBy('modulo')->orderBy('id_permiso')->get()->groupBy('modulo')]); }
    public function permisosGuardar(Request $request,int $id)
    {
        Gate::authorize('permisos.configurar');
        $data=$request->validate(['permisos'=>'sometimes|array','permisos.*'=>'integer|distinct|exists:permisos,id_permiso']);
        $ids=array_map('intval',$data['permisos'] ?? []);
        ContinuidadAdmin::cambiar(function() use($ids,$id) {
            $rol=Rol::findOrFail($id);
            $seleccion=Permiso::whereIn('id_permiso',$ids)->get();
            abort_if($rol->clave!=='superadmin' && $seleccion->contains(fn($p)=>in_array($p->modulo,['usuarios','roles','permisos'])),422,'La administración de accesos se reserva al SuperAdmin.');
            abort_if($seleccion->contains(fn($p)=>$p->accion==='eliminar' && !in_array($p->clave,\App\Services\RegistroCrud::PERMISOS_BORRADO,true)),422,'No se permite habilitar el borrado de registros históricos.');
            foreach(Permiso::all() as $permiso) {
                $clave=['id_rol'=>$id,'id_permiso'=>$permiso->id_permiso];
                $valores=['activo'=>in_array($permiso->id_permiso,$ids)?1:0,'updated_at'=>now()];
                if (DB::table('rol_permiso')->where($clave)->exists()) DB::table('rol_permiso')->where($clave)->update($valores);
                else DB::table('rol_permiso')->insert($clave+$valores+['created_at'=>now()]);
            }
        });
        return back()->with('success','Permisos actualizados para todos los usuarios del rol.');
    }
}
