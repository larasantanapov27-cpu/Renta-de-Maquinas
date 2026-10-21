<?php

namespace App\Http\Controllers;

use App\Models\MotivoBaja;
use Illuminate\Http\Request;

class MotivoBajaController extends Controller
{
    public function index() { return view('accesos.catalogo',['registros'=>\Illuminate\Support\Facades\DB::table('motivos_baja')->get(),'modulo'=>'motivos_baja','llave'=>'id_motivo_baja']); }

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

        MotivoBaja::create($request->only(['nombre','descripcion']));

        return redirect()
            ->route('panel')->with('success','Motivo de baja guardado.');
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

        $data = $request->validate(['nombre'=>'required|string|max:100','descripcion'=>'nullable|string|max:255']);
        $motivo->update($data);

        return redirect()
            ->route('panel')->with('success','Motivo de baja guardado.');
    }

    public function destroy($id)
    {
        return \App\Services\RegistroCrud::borrar('motivos_baja', (int)$id);
    }
}
