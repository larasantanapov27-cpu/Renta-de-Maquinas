<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\TipoMantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MantenimientoController extends Controller
{
    public function index()
    {
        $mantenimientos = Mantenimiento::leftJoin(
                'maquinas',
                'mantenimientos.id_maquina',
                '=',
                'maquinas.id_maquina'
            )
            ->leftJoin(
                'tipos_mantenimiento',
                'mantenimientos.id_tipo_mantenimiento',
                '=',
                'tipos_mantenimiento.id_tipo_mantenimiento'
            )
            ->select(
                'mantenimientos.*',
                'maquinas.codigo as maquina_codigo',
                'tipos_mantenimiento.nombre as tipo_nombre'
            )
            ->orderByDesc('mantenimientos.id_mantenimiento')
            ->get();

        $tipos = TipoMantenimiento::orderBy('nombre')->get();

        return view(
            'mantenimientos.index',
            compact('mantenimientos', 'tipos')
        );
    }

    public function create()
    {
        $maquinas = DB::table('maquinas')
            ->orderBy('codigo')
            ->get();

        $tipos = TipoMantenimiento::orderBy('nombre')->get();

        return view(
            'mantenimientos.create',
            compact('maquinas', 'tipos')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_maquina' => 'required|integer|exists:maquinas,id_maquina',
            'id_tipo_mantenimiento' => 'required|integer|exists:tipos_mantenimiento,id_tipo_mantenimiento',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'nullable|date|after_or_equal:fecha_entrada|before_or_equal:now',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $this->guardar($data);

        return redirect()
            ->route('panel')
            ->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function edit($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $maquinas = DB::table('maquinas')
            ->orderBy('codigo')
            ->get();

        $tipos = TipoMantenimiento::orderBy('nombre')->get();

        return view(
            'mantenimientos.edit',
            compact('mantenimiento', 'maquinas', 'tipos')
        );
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'id_maquina' => 'required|integer|exists:maquinas,id_maquina',
            'id_tipo_mantenimiento' => 'required|integer|exists:tipos_mantenimiento,id_tipo_mantenimiento',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'nullable|date|after_or_equal:fecha_entrada|before_or_equal:now',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $mantenimiento = Mantenimiento::findOrFail($id);

        $this->guardar($data, (int)$id);

        return redirect()
            ->route('panel')
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $mantenimiento->delete();

        return redirect()
            ->route('panel')
            ->with('success', 'Mantenimiento eliminado correctamente.');
    }
    private function guardar(array $data, ?int $id=null): void
    {
        $equipo=DB::table('maquinas')->where('id_maquina',$data['id_maquina'])->lockForUpdate()->first();
        $registro=$id ? Mantenimiento::lockForUpdate()->findOrFail($id):null;
        abort_if($registro && (int)$registro->id_maquina!==(int)$data['id_maquina'],422,'No se puede trasladar el mantenimiento a otra máquina.');
        $rentada=DB::table('detalle_rentas as d')->join('rentas as r','r.id_renta','=','d.id_renta')->join('estados_renta as e','e.id_estado_renta','=','r.id_estado_renta')->where('d.id_maquina',$data['id_maquina'])->whereNull('d.fecha_devolucion_real')->whereIn('e.nombre',['Activa','Programada'])->exists();
        $otro=DB::table('mantenimientos')->where('id_maquina',$data['id_maquina'])->whereNull('fecha_salida')->when($id,fn($q)=>$q->where('id_mantenimiento','!=',$id))->exists();
        abort_if($rentada || $otro || DB::table('bajas_maquinas')->where('id_maquina',$data['id_maquina'])->exists(),422,'El equipo está rentado, reservado, en otro mantenimiento o dado de baja.');
        $data['costo']=$data['costo'] ?? 0;
        if ($registro) $registro->update($data); else Mantenimiento::create($data);
        $nombre=empty($data['fecha_salida'])?'Mantenimiento':'Disponible';
        $estado=DB::table('estados_maquina')->where('nombre',$nombre)->value('id_estado_maquina');
        \App\Services\EstadoEquipo::cambiar((int)$data['id_maquina'],(int)$estado);
    }
}
