<?php

namespace App\Http\Controllers;

use App\Models\DireccionCliente;
use App\Models\Cliente;
use Illuminate\Http\Request;

class DireccionClienteController extends Controller
{
    public function index()
    {
        $direcciones = DireccionCliente::with('cliente.persona')->get();

        return view(
            'direcciones_clientes.index',
            compact('direcciones')
        );
    }

    public function create()
    {
        $clientes = Cliente::with('persona')->get();

        return view(
            'direcciones_clientes.create',
            compact('clientes')
        );
    }

    public function store(Request $request)
    {
        DireccionCliente::create([
            'id_cliente' => $request->id_cliente,
            'calle' => $request->calle,
            'numero_exterior' => $request->numero_exterior,
            'numero_interior' => $request->numero_interior,
            'colonia' => $request->colonia,
            'municipio' => $request->municipio,
            'estado' => $request->estado,
            'codigo_postal' => $request->codigo_postal
        ]);

        return redirect()->route(
            'direcciones_clientes.index'
        );
    }

    public function edit($id)
    {
        $direccion = DireccionCliente::findOrFail($id);

        $clientes = Cliente::with('persona')->get();

        return view(
            'direcciones_clientes.edit',
            compact('direccion', 'clientes')
        );
    }

    public function update(Request $request, $id)
    {
        $direccion = DireccionCliente::findOrFail($id);

        $direccion->update([
            'id_cliente' => $request->id_cliente,
            'calle' => $request->calle,
            'numero_exterior' => $request->numero_exterior,
            'numero_interior' => $request->numero_interior,
            'colonia' => $request->colonia,
            'municipio' => $request->municipio,
            'estado' => $request->estado,
            'codigo_postal' => $request->codigo_postal
        ]);

        return redirect()->route(
            'direcciones_clientes.index'
        );
    }

    public function destroy($id)
    {
        $direccion = DireccionCliente::findOrFail($id);

        $direccion->delete();

        return redirect()->route(
            'direcciones_clientes.index'
        );
    }
}