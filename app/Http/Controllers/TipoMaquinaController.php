<?php

namespace App\Http\Controllers;

use App\Models\TipoMaquina;
use Illuminate\Http\Request;

class TipoMaquinaController extends Controller
{
    public function index()
    {
        $tipos = TipoMaquina::orderBy('id_tipo_maquina', 'asc')->get();

        return view('tipos_maquina.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_maquina.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_maquina,nombre',
            'descripcion' => 'nullable|string'
        ]);

        TipoMaquina::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('tipos_maquina.index')
            ->with('success', 'Tipo de máquina registrado correctamente.');
    }

    public function edit($id)
    {
        $tipo = TipoMaquina::findOrFail($id);

        return view('tipos_maquina.edit', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoMaquina::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_maquina,nombre,' .
                $id . ',id_tipo_maquina',

            'descripcion' => 'nullable|string'
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('tipos_maquina.index')
            ->with('success', 'Tipo de máquina actualizado correctamente.');
    }

    public function destroy($id)
    {
        $tipo = TipoMaquina::findOrFail($id);

        $tipo->delete();

        return redirect()
            ->route('tipos_maquina.index')
            ->with('success', 'Tipo de máquina eliminado correctamente.');
    }
}