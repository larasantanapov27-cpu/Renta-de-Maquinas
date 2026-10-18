<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class EstadoEquipo
{
    public static function libre(int $id): bool
    {
        return !DB::table('detalle_rentas as d')->join('rentas as r','r.id_renta','=','d.id_renta')->join('estados_renta as e','e.id_estado_renta','=','r.id_estado_renta')->where('d.id_maquina',$id)->whereNull('d.fecha_devolucion_real')->whereIn('e.nombre',['Activa','Programada'])->exists()
            && !DB::table('mantenimientos')->where('id_maquina',$id)->whereNull('fecha_salida')->exists()
            && !DB::table('bajas_maquinas')->where('id_maquina',$id)->exists();
    }
    public static function cambiar(int $id,int $estado): void
    {
        $maquina=DB::table('maquinas')->where('id_maquina',$id)->lockForUpdate()->first(); abort_unless($maquina,404);
        $nombre=DB::table('estados_maquina')->where('id_estado_maquina',$estado)->value('nombre');
        if ($nombre==='Disponible' && !self::libre($id)) throw ValidationException::withMessages(['id_estado_maquina'=>'El equipo sigue rentado, reservado, en mantenimiento o dado de baja.']);
        if ($nombre==='Rentada' && !DB::table('detalle_rentas as d')->join('rentas as r','r.id_renta','=','d.id_renta')->join('estados_renta as e','e.id_estado_renta','=','r.id_estado_renta')->where('d.id_maquina',$id)->whereNull('d.fecha_devolucion_real')->where('e.nombre','Activa')->exists()) throw ValidationException::withMessages(['id_estado_maquina'=>'El estado Rentada requiere una renta activa.']);
        if ($nombre==='Baja' && !DB::table('bajas_maquinas')->where('id_maquina',$id)->exists()) throw ValidationException::withMessages(['id_estado_maquina'=>'Registra primero la baja de la máquina.']);
        if (DB::table('bajas_maquinas')->where('id_maquina',$id)->exists() && $nombre!=='Baja') throw ValidationException::withMessages(['id_estado_maquina'=>'Una baja definitiva no se puede reactivar.']);
        DB::table('maquinas')->where('id_maquina',$id)->update(['id_estado_maquina'=>$estado]);
    }
}
