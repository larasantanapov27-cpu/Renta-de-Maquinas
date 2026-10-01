<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoRentaController extends Controller
{
    public function index()
    {
        $periodos = DB::table('periodos_renta')
            ->orderBy('id_periodo')
            ->get();

        return view('periodos_renta.index', compact('periodos'));
    }

    public function create()
    {
        return view('periodos_renta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:periodos_renta,nombre',
        ]);

        DB::table('periodos_renta')->insert([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('periodos_renta.index')
            ->with('success', 'Período registrado correctamente.');
    }

    public function edit($id)
    {
        $periodo = DB::table('periodos_renta')
            ->where('id_periodo', $id)
            ->first();

        abort_if(!$periodo, 404);

        return view('periodos_renta.edit', compact('periodo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:periodos_renta,nombre,' .
                        $id . ',id_periodo',
        ]);

        DB::table('periodos_renta')
            ->where('id_periodo', $id)
            ->update([
                'nombre' => $request->nombre,
            ]);

        return redirect()
            ->route('periodos_renta.index')
            ->with('success', 'Período actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usado = DB::table('detalle_tarifa')
            ->where('id_periodo', $id)
            ->exists();

        if ($usado) {
            return redirect()
                ->route('periodos_renta.index')
                ->with(
                    'error',
                    'No se puede eliminar porque el período está siendo utilizado en una tarifa.'
                );
        }

        DB::table('periodos_renta')
            ->where('id_periodo', $id)
            ->delete();

        return redirect()
            ->route('periodos_renta.index')
            ->with('success', 'Período eliminado correctamente.');
    }
} 