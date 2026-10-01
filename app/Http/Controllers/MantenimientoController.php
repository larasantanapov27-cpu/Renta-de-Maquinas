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
        $request->validate([
            'id_maquina' => 'required|integer|exists:maquinas,id_maquina',
            'id_tipo_mantenimiento' => 'required|integer|exists:tipos_mantenimiento,id_tipo_mantenimiento',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'nullable|date',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        Mantenimiento::create($request->all());

        return redirect()
            ->route('mantenimientos.index')
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
        $request->validate([
            'id_maquina' => 'required|integer|exists:maquinas,id_maquina',
            'id_tipo_mantenimiento' => 'required|integer|exists:tipos_mantenimiento,id_tipo_mantenimiento',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'nullable|date',
            'costo' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $mantenimiento = Mantenimiento::findOrFail($id);

        $mantenimiento->update($request->all());

        return redirect()
            ->route('mantenimientos.index')
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        $mantenimiento = Mantenimiento::findOrFail($id);

        $mantenimiento->delete();

        return redirect()
            ->route('mantenimientos.index')
            ->with('success', 'Mantenimiento eliminado correctamente.');
    }
}