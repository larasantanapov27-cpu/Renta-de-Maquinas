<?php

namespace App\Http\Controllers;

use App\Models\BajaMaquina;
use App\Models\MotivoBaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BajaMaquinaController extends Controller
{
    public function index()
    {
        $bajas = DB::table('bajas_maquinas')
            ->leftJoin(
                'maquinas',
                'bajas_maquinas.id_maquina',
                '=',
                'maquinas.id_maquina'
            )
            ->leftJoin(
                'motivos_baja',
                'bajas_maquinas.id_motivo_baja',
                '=',
                'motivos_baja.id_motivo_baja'
            )
            ->select(
                'bajas_maquinas.*',
                'maquinas.codigo as maquina_codigo',
                'motivos_baja.nombre as motivo_nombre'
            )
            ->orderByDesc('bajas_maquinas.id_baja')
            ->get();

        $motivos = MotivoBaja::orderBy('nombre')->get();

        return view(
            'bajas_maquinas.index',
            compact('bajas', 'motivos')
        );
    }

    public function create()
    {
        $maquinas = DB::table('maquinas')
            ->orderBy('codigo')
            ->get();

        $motivos = MotivoBaja::orderBy('nombre')->get();

        return view(
            'bajas_maquinas.create',
            compact('maquinas', 'motivos')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_maquina' => 'required|integer|exists:maquinas,id_maquina|unique:bajas_maquinas,id_maquina',
            'id_motivo_baja' => 'required|integer|exists:motivos_baja,id_motivo_baja',
            'fecha_baja' => 'required|date',
            'descripcion' => 'nullable|string'
        ]);

        DB::table('maquinas')->where('id_maquina',$data['id_maquina'])->lockForUpdate()->first();
        abort_unless(\App\Services\EstadoEquipo::libre((int)$data['id_maquina']),422,'El equipo está rentado, en mantenimiento o ya dado de baja.');
        BajaMaquina::create($data);
        \App\Services\EstadoEquipo::cambiar((int)$data['id_maquina'],(int)DB::table('estados_maquina')->where('nombre','Baja')->value('id_estado_maquina'));

        return redirect()
            ->route('panel')->with('success','Baja de maquinaria guardada.');
    }

    public function edit($id)
    {
        $baja = BajaMaquina::findOrFail($id);

        $maquinas = DB::table('maquinas')
            ->orderBy('codigo')
            ->get();

        $motivos = MotivoBaja::orderBy('nombre')->get();

        return view(
            'bajas_maquinas.edit',
            compact('baja', 'maquinas', 'motivos')
        );
    }

    public function update(Request $request, $id)
    {
        $baja = BajaMaquina::findOrFail($id);

        $data=$request->validate(['id_maquina'=>'prohibited','id_motivo_baja'=>'required|integer|exists:motivos_baja,id_motivo_baja','fecha_baja'=>'required|date','descripcion'=>'nullable|string']);
        $baja->update($data);

        return redirect()
            ->route('panel')->with('success','Baja de maquinaria guardada.');
    }

    public function destroy($id)
    {
        return \App\Services\RegistroCrud::borrar('bajas_maquinas', (int)$id);
    }
}
