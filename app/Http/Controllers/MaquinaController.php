<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Models\TipoMaquina;
use App\Models\EstadoMaquina;
use Illuminate\Http\Request;

class MaquinaController extends Controller
{
    public function index()
    {
        $maquinas = Maquina::with([
            'tipoMaquina',
            'estadoMaquina'
        ])->get();

        return view('maquinas.index', compact('maquinas'));
    }


    public function create()
    {
        $tiposMaquina = TipoMaquina::orderBy('nombre', 'asc')->get();

        $estadosMaquina = EstadoMaquina::orderBy('nombre', 'asc')->get();

        return view('maquinas.create', compact(
            'tiposMaquina',
            'estadosMaquina'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|max:50|unique:maquinas,codigo',
            'id_tipo_maquina' => 'required|exists:tipos_maquina,id_tipo_maquina',
            'id_estado_maquina' => 'required|exists:estados_maquina,id_estado_maquina',
            'marca' => 'required|max:100',
            'modelo' => 'nullable|max:100',
            'numero_serie' => 'nullable|max:100',
            'fecha_ingreso' => 'required|date',
            'descripcion' => 'nullable',
            'observaciones' => 'nullable'
        ]);

        Maquina::create([
            'codigo' => $request->codigo,
            'id_tipo_maquina' => $request->id_tipo_maquina,
            'id_estado_maquina' => $request->id_estado_maquina,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'numero_serie' => $request->numero_serie,
            'fecha_ingreso' => $request->fecha_ingreso,
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones
        ]);

        return redirect()
            ->route('maquinas.index')
            ->with('success', 'Máquina registrada correctamente.');
    }


    public function edit($id)
    {
        $maquina = Maquina::findOrFail($id);

        $tiposMaquina = TipoMaquina::orderBy('nombre', 'asc')->get();

        $estadosMaquina = EstadoMaquina::orderBy('nombre', 'asc')->get();

        return view('maquinas.edit', compact(
            'maquina',
            'tiposMaquina',
            'estadosMaquina'
        ));
    }


    public function update(Request $request, $id)
    {
        $maquina = Maquina::findOrFail($id);

        $request->validate([
            'codigo' => 'required|max:50|unique:maquinas,codigo,' .
                        $maquina->id_maquina . ',id_maquina',

            'id_tipo_maquina' => 'required|exists:tipos_maquina,id_tipo_maquina',

            'id_estado_maquina' => 'required|exists:estados_maquina,id_estado_maquina',

            'marca' => 'required|max:100',

            'modelo' => 'nullable|max:100',

            'numero_serie' => 'nullable|max:100',

            'fecha_ingreso' => 'required|date',

            'descripcion' => 'nullable',

            'observaciones' => 'nullable'
        ]);

        $maquina->update([
            'codigo' => $request->codigo,
            'id_tipo_maquina' => $request->id_tipo_maquina,
            'id_estado_maquina' => $request->id_estado_maquina,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'numero_serie' => $request->numero_serie,
            'fecha_ingreso' => $request->fecha_ingreso,
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones
        ]);

        return redirect()
            ->route('maquinas.index')
            ->with('success', 'Máquina actualizada correctamente.');
    }


    public function destroy($id)
    {
        $maquina = Maquina::findOrFail($id);

        $maquina->delete();

        return redirect()
            ->route('maquinas.index')
            ->with('success', 'Máquina eliminada correctamente.');
    }
}