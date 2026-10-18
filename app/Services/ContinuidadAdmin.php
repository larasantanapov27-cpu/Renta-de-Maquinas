<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ContinuidadAdmin
{
    // All security mutations acquire the same locks, in this order, including pivot edits.
    public static function cambiar(callable $change): void
    {
        DB::transaction(function() use ($change) {
            DB::table('roles')->orderBy('id_rol')->lockForUpdate()->get();
            DB::table('users')->orderBy('id')->lockForUpdate()->get();
            DB::table('rol_permiso')->orderBy('id_rol')->orderBy('id_permiso')->lockForUpdate()->get();
            $change();
            $rol=DB::table('roles')->where('clave','superadmin')->where('activo',1)->first();
            $ok=$rol && DB::table('users')->where('id_rol',$rol->id_rol)->where('estado','activo')->exists();
            if ($ok) {
                $claves=DB::table('rol_permiso as rp')->join('permisos as p','p.id_permiso','=','rp.id_permiso')->where('rp.id_rol',$rol->id_rol)->where('rp.activo',1)->pluck('p.clave')->all();
                $ok=count(array_diff(Acceso::ESENCIALES,$claves))===0;
            }
            if (!$ok) throw ValidationException::withMessages(['continuidad'=>'Debe permanecer al menos un SuperAdmin activo con todos los permisos esenciales de administración.']);
        },3);
    }
}
