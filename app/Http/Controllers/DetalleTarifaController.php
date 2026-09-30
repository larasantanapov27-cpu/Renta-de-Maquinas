<?php

namespace App\Http\Controllers;

use App\Models\DetalleTarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleTarifaController extends Controller
{
    // Mostrar todas las tarifas
    public function index()
    {
        $tarifas = DB::table('detalle_tarifa as dt')
            ->join(
                'tipos_maquina as tm',
                'dt.id_tipo_maquina',
                '=',
                'tm.id_tipo_maquina'
            )
            ->join(
                'periodos_renta as pr',
                'dt.id_periodo',
                '=',
                'pr.id_periodo'
            )
            ->select(
                'dt.*',
                'tm.nombre as tipo_maquina',
                'pr.nombre as periodo'
            )
            ->orderBy('dt.id_tarifa')
            ->get();

        return view('detalle_tarifa.index', compact('tarifas'));
    }

    // Mostrar formulario para registrar tarifa
    public function create()
    {
        $tiposMaquina = DB::table('tipos_maquina')
            ->orderBy('nombre')
            ->get();

        $periodos = DB::table('periodos_renta')
            ->orderBy('id_periodo')
            ->get();

        return view(
            'detalle_tarifa.create',
            compact('tiposMaquina', 'periodos')
        );
    }

    // Guardar nueva tarifa
    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_maquina' => 'required|exists:tipos_maquina,id_tipo_maquina',
            'id_periodo' => 'required|exists:periodos_renta,id_periodo',
            'precio' => 'required|numeric|min:0',
            'deposito' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        DetalleTarifa::create($request->only([
            'id_tipo_maquina',
            'id_periodo',
            'precio',
            'deposito',
            'fecha_inicio',
            'fecha_fin',
        ]));

        return redirect()
            ->route('detalle_tarifa.index')
            ->with('success', 'Tarifa registrada correctamente.');
    }

    // Mostrar formulario para editar
    public function edit($id)
    {
        $tarifa = DetalleTarifa::findOrFail($id);

        $tiposMaquina = DB::table('tipos_maquina')
            ->orderBy('nombre')
            ->get();

        $periodos = DB::table('periodos_renta')
            ->orderBy('id_periodo')
            ->get();

        return view(
            'detalle_tarifa.edit',
            compact('tarifa', 'tiposMaquina', 'periodos')
        );
    }

    // Actualizar tarifa
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tipo_maquina' => 'required|exists:tipos_maquina,id_tipo_maquina',
            'id_periodo' => 'required|exists:periodos_renta,id_periodo',
            'precio' => 'required|numeric|min:0',
            'deposito' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $tarifa = DetalleTarifa::findOrFail($id);

        $tarifa->update($request->only([
            'id_tipo_maquina',
            'id_periodo',
            'precio',
            'deposito',
            'fecha_inicio',
            'fecha_fin',
        ]));

        return redirect()
            ->route('detalle_tarifa.index')
            ->with('success', 'Tarifa actualizada correctamente.');
    }

    // Eliminar tarifa
    public function destroy($id)
    {
        $tarifa = DetalleTarifa::findOrFail($id);
        $tarifa->delete();

        return redirect()
            ->route('detalle_tarifa.index')
            ->with('success', 'Tarifa eliminada correctamente.');
    }
}