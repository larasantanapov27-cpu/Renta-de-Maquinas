<?php

namespace App\Http\Controllers;

use App\Models\MotivoBaja;
use Illuminate\Http\Request;

class MotivoBajaController extends Controller
{
    public function create()
    {
        return view('motivos_baja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable|max:255'
        ]);

        MotivoBaja::create($request->all());

        return redirect()
            ->route('bajas_maquinas.index');
    }

    public function edit($id)
    {
        $motivo = MotivoBaja::findOrFail($id);

        return view(
            'motivos_baja.edit',
            compact('motivo')
        );
    }

    public function update(Request $request, $id)
    {
        $motivo = MotivoBaja::findOrFail($id);

        $motivo->update($request->all());

        return redirect()
            ->route('bajas_maquinas.index');
    }

    public function destroy($id)
    {
        $motivo = MotivoBaja::findOrFail($id);

        $motivo->delete();

        return redirect()
            ->route('bajas_maquinas.index');
    }
}