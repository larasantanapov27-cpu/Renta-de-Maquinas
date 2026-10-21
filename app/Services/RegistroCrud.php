<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegistroCrud
{
    public const PERMISOS_BORRADO = ['clientes.eliminar','proveedores.eliminar','tarifas.eliminar','catalogos.eliminar'];
    public const LLAVES = [
        'clientes'=>'id_cliente',
        'proveedores'=>'id_proveedor',
        'direcciones_clientes'=>'id_direccion',
        'maquinas'=>'id_maquina',
        'compras'=>'id_compra',
        'detalle_tarifa'=>'id_tarifa',
        'mantenimientos'=>'id_mantenimiento',
        'bajas_maquinas'=>'id_baja',
        'pagos'=>'id_pago',
        'tipos_maquina'=>'id_tipo_maquina',
        'estados_maquina'=>'id_estado_maquina',
        'periodos_renta'=>'id_periodo',
        'metodos_pago'=>'id_metodo_pago',
        'tipos_mantenimiento'=>'id_tipo_mantenimiento',
        'motivos_baja'=>'id_motivo_baja',
        'estados_renta'=>'id_estado_renta',
        'rentas'=>'id_renta', 'personas'=>'id_persona', 'detalle_rentas'=>'id_detalle_renta',
    ];
    // Relaciones existentes; las claves foráneas son iguales al PK del padre.
    public const RELACIONES = [
        'clientes'=>['personas'], 'proveedores'=>['personas'],
        'direcciones_clientes'=>['clientes'], 'maquinas'=>['tipos_maquina','estados_maquina'],
        'compras'=>['proveedores','maquinas'], 'detalle_tarifa'=>['tipos_maquina','periodos_renta'],
        'rentas'=>['clientes','estados_renta'], 'detalle_rentas'=>['rentas','maquinas','detalle_tarifa'],
        'pagos'=>['rentas','metodos_pago'], 'mantenimientos'=>['maquinas','tipos_mantenimiento'],
        'bajas_maquinas'=>['maquinas','motivos_baja'],
    ];
    public static function eliminable(string $modulo): bool
    {
        return in_array($modulo, ['clientes','proveedores','direcciones_clientes','detalle_tarifa','tipos_maquina','periodos_renta','metodos_pago','tipos_mantenimiento','motivos_baja'], true);
    }
    public static function detalle(string $modulo, int $id)
    {
        abort_unless(isset(self::LLAVES[$modulo]) && $modulo !== 'personas',404);
        $registro = DB::table($modulo)->where(self::LLAVES[$modulo],$id)->first();
        abort_unless($registro,404);
        $relacionados = [];
        foreach (self::RELACIONES[$modulo] ?? [] as $padre) {
            // Datos que forman parte del registro; no se publican listas de otros módulos.
            if ($padre === 'personas' || self::consultable($padre)) {
                $key = self::LLAVES[$padre];
                $consulta = DB::table($padre)->where($padre.'.'.$key,$registro->$key);
                if (in_array($padre,['clientes','proveedores'],true)) {
                    $consulta->join('personas','personas.id_persona','=',$padre.'.id_persona')->select($padre.'.*','personas.nombre','personas.apellidos','personas.telefono');
                }
                $relacionados[$padre] = $consulta->get();
            }
        }
        foreach (self::RELACIONES as $hija=>$padres) {
            if (in_array($modulo,$padres,true) && self::consultable($hija)) {
                $relacionados[$hija] = DB::table($hija)->where(self::LLAVES[$modulo],$id)->get();
            }
        }
        return view('crud.detalle',compact('modulo','registro','relacionados'));
    }
    private static function consultable(string $modulo): bool
    {
        if ($modulo === 'detalle_rentas') return Acceso::permite(auth()->user(),'rentas.consultar');
        if (isset(Acceso::CATALOGOS[$modulo])) return Acceso::catalogo(auth()->user(),$modulo) && Acceso::permite(auth()->user(),'catalogos.consultar');
        return Acceso::permite(auth()->user(),Acceso::modulo($modulo).'.consultar');
    }
    public static function borrar(string $modulo, int $id)
    {
        $registro = DB::table($modulo)->where(self::LLAVES[$modulo],$id)->lockForUpdate()->first();
        abort_unless($registro,404);
        if (!self::eliminable($modulo)) {
            throw ValidationException::withMessages(['registro'=>'Este registro conserva el historial del sistema y no puede borrarse. Para retirar maquinaria utiliza el módulo de bajas.']);
        }
        foreach (self::RELACIONES as $hija=>$padres) {
            if (in_array($modulo,$padres,true) && DB::table($hija)->where(self::LLAVES[$modulo],$id)->exists()) {
                throw ValidationException::withMessages(['registro'=>'No se puede borrar: existen registros relacionados en '.str_replace('_',' ',$hija).'.']);
            }
        }
        try { DB::table($modulo)->where(self::LLAVES[$modulo],$id)->delete(); }
        catch (\Illuminate\Database\QueryException $error) {
            Log::error('Borrado rechazado', ['modulo'=>$modulo,'id'=>$id,'exception'=>$error]);
            throw ValidationException::withMessages(['registro'=>'No se pudo borrar el registro. Puede tener relaciones o estar siendo utilizado.']);
        }
        return redirect()->route($modulo.'.index')->with('success','Registro eliminado correctamente.');
    }
}
