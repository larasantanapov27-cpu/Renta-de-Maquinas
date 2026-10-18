<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class Acceso
{
    public const INICIOS = ['superadmin'=>'superadmin.inicio', 'rentas'=>'rentas.inicio', 'mantenimiento'=>'mantenimiento.inicio', 'administrativo'=>'administrativo.inicio'];
    public const ESENCIALES = ['usuarios.consultar','usuarios.validar','usuarios.cambiar_estado','usuarios.asignar_rol','roles.consultar','roles.cambiar_estado','permisos.consultar','permisos.configurar'];
    public const MODULOS = ['clientes'=>'Clientes','direcciones_clientes'=>'Direcciones de clientes','maquinas'=>'Maquinaria','rentas'=>'Rentas y devoluciones','pagos'=>'Pagos','detalle_tarifa'=>'Tarifas','mantenimientos'=>'Mantenimientos','proveedores'=>'Proveedores','compras'=>'Compras','bajas_maquinas'=>'Bajas de maquinaria'];
    public const CATALOGOS = ['tipos_maquina'=>['maquinas','rentas','compras','mantenimientos','bajas_maquinas','detalle_tarifa'], 'estados_maquina'=>['maquinas','mantenimientos'], 'periodos_renta'=>['detalle_tarifa','rentas'], 'estados_renta'=>['rentas'], 'metodos_pago'=>['pagos'], 'tipos_mantenimiento'=>['mantenimientos'], 'motivos_baja'=>['bajas_maquinas']];
    public static function modulo(string $modulo): string { return ['detalle_tarifa'=>'tarifas','direcciones_clientes'=>'clientes'][$modulo] ?? $modulo; }
    public static function activo(?User $user): bool
    {
        return $user && $user->estado === 'activo' && $user->rol()->where('activo',1)->exists();
    }
    public static function permite(?User $user, string $clave): bool
    {
        if (!self::activo($user)) return false;
        if (in_array(explode('.', $clave)[0], ['usuarios','roles','permisos']) && $user->rol()->value('clave') !== 'superadmin') return false;
        return DB::table('rol_permiso as rp')->join('permisos as p','p.id_permiso','=','rp.id_permiso')->where('rp.id_rol',$user->id_rol)->where('rp.activo',1)->where('p.clave',$clave)->exists();
    }
    public static function inicio(User $user): string
    {
        $rol = $user->rol()->first();
        $ruta = self::INICIOS[$rol?->clave] ?? null;
        abort_unless($ruta && $rol->ruta_inicio === $ruta, 403, 'El rol no tiene un panel permitido configurado.');
        return $ruta;
    }
    public static function catalogo(User $user, string $catalogo): bool
    {
        foreach (self::CATALOGOS[$catalogo] ?? [] as $modulo) {
            foreach (['consultar','registrar','modificar','cambiar_estado','devolver'] as $accion) {
                if (self::permite($user, self::modulo($modulo).'.'.$accion)) return true;
            }
        }
        return false;
    }
    public static function enlaces(User $user): array
    {
        $links = [];
        foreach (self::MODULOS as $modulo=>$nombre) {
            foreach (['consultar'=>'index','registrar'=>'create'] as $accion=>$ruta) {
                if (self::permite($user,self::modulo($modulo).'.'.$accion)) $links[] = ['ruta'=>"$modulo.$ruta",'nombre'=>$nombre.($accion==='registrar'?' · Registrar':'')];
            }
            if (!self::permite($user,self::modulo($modulo).'.consultar') && collect(['modificar','devolver','cambiar_estado'])->contains(fn($a)=>self::permite($user,self::modulo($modulo).'.'.$a))) {
                $links[]=['ruta'=>"$modulo.index",'nombre'=>$nombre.' · Operaciones'];
            }
        }
        foreach (self::CATALOGOS as $modulo=>$padres) {
            if (!self::catalogo($user,$modulo)) continue;
            $nombre=ucfirst(str_replace('_',' ',$modulo));
            if (self::permite($user,'catalogos.consultar')) $links[]=['ruta'=>"$modulo.index",'nombre'=>$nombre];
            elseif (self::permite($user,'catalogos.modificar')) $links[]=['ruta'=>"$modulo.index",'nombre'=>$nombre.' · Operaciones'];
            if (self::permite($user,'catalogos.registrar')) $links[]=['ruta'=>"$modulo.create",'nombre'=>$nombre.' · Registrar'];
        }
        foreach (['usuarios','roles','permisos'] as $modulo) if (self::permite($user,"$modulo.consultar")) $links[]=['ruta'=>"accesos.$modulo",'nombre'=>ucfirst($modulo)];
        return $links;
    }
}
