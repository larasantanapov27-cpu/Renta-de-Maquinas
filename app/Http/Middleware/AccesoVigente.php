<?php
namespace App\Http\Middleware;
use App\Services\Acceso;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AccesoVigente
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        abort_unless($user,401);
        $user->refresh();
        abort_unless(Acceso::activo($user),403,'Tu cuenta requiere aprobación, un rol activo y estado activo.');
        $name = $request->route()->getName();
        if ($name === 'panel' || $name === 'operaciones.abrir' || str_ends_with($name,'.inicio')) return $next($request);
        if (str_starts_with($name,'accesos.')) {
            abort_unless($user->rol()->value('clave') === 'superadmin',403);
            return $next($request);
        }
        [$modulo,$accion] = explode('.', $name,2);
        abort_if($accion==='destroy' || $modulo==='personas',403,'Operación deshabilitada.');
        $accion = ['index'=>'consultar','show'=>'consultar','renta'=>'consultar','create'=>'registrar','store'=>'registrar','edit'=>'modificar','update'=>'modificar','devolucion'=>'devolver','estado_formulario'=>'cambiar_estado','estado'=>'cambiar_estado'][$accion] ?? $accion;
        if (isset(Acceso::CATALOGOS[$modulo])) {
            if ($accion==='consultar' && str_ends_with($name,'.index') && !Acceso::permite($user,'catalogos.consultar') && Acceso::catalogo($user,$modulo) && Acceso::permite($user,'catalogos.modificar')) {
                $operations=collect(['modificar']);
                return response()->view('paneles.operaciones',compact('modulo','operations'));
            }
            abort_unless(Acceso::catalogo($user,$modulo) && Acceso::permite($user,'catalogos.'.$accion),403);
        } else {
            $allowed = Acceso::permite($user,Acceso::modulo($modulo).'.'.$accion);
            // A minimal operation picker is rendered instead of exposing the consultation list.
            if (!$allowed && $accion==='consultar' && str_ends_with($name,'.index')) {
                $operations = collect(['modificar','devolver','cambiar_estado'])->filter(fn($a)=>Acceso::permite($user,Acceso::modulo($modulo).'.'.$a));
                if ($operations->isNotEmpty()) return response()->view('paneles.operaciones',compact('modulo','operations'));
            }
            abort_unless($allowed,403,'No tienes permiso para esta operación.');
        }
        return $request->isMethodSafe() ? $next($request) : DB::transaction(fn()=> $next($request),3);
    }
}
