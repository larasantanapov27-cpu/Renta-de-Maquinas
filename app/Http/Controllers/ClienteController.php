<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('persona')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $personas = Persona::all();

        return view('clientes.create', compact('personas'));
    }

    public function store(Request $request)
    {
        Cliente::create([
            'id_persona' => $request->id_persona,
            'correo' => $request->correo
        ]);

        return redirect()->route('clientes.index');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        $personas = Persona::all();

        return view('clientes.edit', compact(
            'cliente',
            'personas'
        ));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update([
            'id_persona' => $request->id_persona,
            'correo' => $request->correo
        ]);

        return redirect()->route('clientes.index');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return redirect()->route('clientes.index');
    }
}