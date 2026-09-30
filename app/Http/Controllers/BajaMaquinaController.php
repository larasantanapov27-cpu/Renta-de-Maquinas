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
        $request->validate([
            'id_maquina' => 'required',
            'id_motivo_baja' => 'required',
            'fecha_baja' => 'required|date',
            'descripcion' => 'nullable'
        ]);

        BajaMaquina::create($request->all());

        return redirect()
            ->route('bajas_maquinas.index');
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

        $baja->update($request->all());

        return redirect()
            ->route('bajas_maquinas.index');
    }

    public function destroy($id)
    {
        $baja = BajaMaquina::findOrFail($id);

        $baja->delete();

        return redirect()
            ->route('bajas_maquinas.index');
    }
}