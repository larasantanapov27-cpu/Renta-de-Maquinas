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
            'codigo' => 'required|max:30|unique:maquinas,codigo',
            'id_tipo_maquina' => 'required|exists:tipos_maquina,id_tipo_maquina',
            'id_estado_maquina' => 'required|exists:estados_maquina,id_estado_maquina',
            'marca' => 'required|max:100',
            'modelo' => 'nullable|max:100',
            'numero_serie' => 'nullable|max:100',
            'fecha_ingreso' => 'required|date',
            'descripcion' => 'nullable',
            'observaciones' => 'nullable'
        ]);

        $disponible = EstadoMaquina::where('nombre','Disponible')->value('id_estado_maquina');
        if ((int)$request->id_estado_maquina !== (int)$disponible) {
            \Illuminate\Support\Facades\Gate::authorize('maquinas.cambiar_estado');
            abort_unless(EstadoMaquina::whereKey($request->id_estado_maquina)->where('nombre','Fuera de servicio')->exists(),422,'Registra inicialmente Disponible o Fuera de servicio.');
        }
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
            ->route('panel')
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
            'codigo' => 'required|max:30|unique:maquinas,codigo,' .
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

        $maquina = Maquina::whereKey($id)->lockForUpdate()->firstOrFail();
        if ((int)$request->id_estado_maquina !== (int)$maquina->id_estado_maquina) {
            \Illuminate\Support\Facades\Gate::authorize('maquinas.cambiar_estado');
            \App\Services\EstadoEquipo::cambiar((int)$id,(int)$request->id_estado_maquina);
        }
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
            ->route('panel')
            ->with('success', 'Máquina actualizada correctamente.');
    }


    public function estadoFormulario($id)
    {
        return view('maquinas.estado',['maquina'=>Maquina::findOrFail($id),'estados'=>EstadoMaquina::all()]);
    }
    public function estado(Request $request, $id)
    {
        $data=$request->validate(['id_estado_maquina'=>'required|integer|exists:estados_maquina,id_estado_maquina']);
        \App\Services\EstadoEquipo::cambiar((int)$id,(int)$data['id_estado_maquina']);
        return redirect()->route('panel')->with('success','Estado actualizado.');
    }

    public function destroy($id)
    {
        return \App\Services\RegistroCrud::borrar('maquinas', (int)$id);
    }
}
