<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodos = DB::table('metodos_pago')
            ->orderBy('id_metodo_pago')
            ->get();

        return view('metodos_pago.index', compact('metodos'));
    }

    public function create()
    {
        return view('metodos_pago.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:metodos_pago,nombre',
        ]);

        DB::table('metodos_pago')->insert([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('panel')
            ->with('success', 'Método de pago registrado correctamente.');
    }

    public function edit($id)
    {
        $metodo = DB::table('metodos_pago')
            ->where('id_metodo_pago', $id)
            ->first();

        abort_if(!$metodo, 404);

        return view('metodos_pago.edit', compact('metodo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:metodos_pago,nombre,'
                . $id . ',id_metodo_pago',
        ]);

        DB::table('metodos_pago')
            ->where('id_metodo_pago', $id)
            ->update([
                'nombre' => $request->nombre,
            ]);

        return redirect()
            ->route('panel')
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        return \App\Services\RegistroCrud::borrar('metodos_pago', (int)$id);
    }
}
