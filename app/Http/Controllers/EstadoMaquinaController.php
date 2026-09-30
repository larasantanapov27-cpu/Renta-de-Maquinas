<?php

namespace App\Http\Controllers;

use App\Models\EstadoMaquina;
use Illuminate\Http\Request;

class EstadoMaquinaController extends Controller
{
    public function index()
    {
        $estados = EstadoMaquina::orderBy('id_estado_maquina', 'asc')->get();

        return view('estados_maquina.index', compact('estados'));
    }

    public function create()
    {
        return view('estados_maquina.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        EstadoMaquina::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('estados_maquina.index')
            ->with('success', 'Estado de máquina registrado correctamente.');
    }

    public function edit($id)
    {
        $estado = EstadoMaquina::findOrFail($id);

        return view('estados_maquina.edit', compact('estado'));
    }

    public function update(Request $request, $id)
    {
        $estado = EstadoMaquina::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        $estado->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('estados_maquina.index')
            ->with('success', 'Estado de máquina actualizado correctamente.');
    }

    public function destroy($id)
    {
        $estado = EstadoMaquina::findOrFail($id);

        $estado->delete();

        return redirect()
            ->route('estados_maquina.index')
            ->with('success', 'Estado de máquina eliminado correctamente.');
    }
}