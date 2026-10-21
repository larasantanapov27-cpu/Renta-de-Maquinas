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
        $request->validate(['id_cliente'=>'required|integer|exists:clientes,id_cliente','calle'=>'required|string|max:150','numero_exterior'=>'nullable|string|max:20','numero_interior'=>'nullable|string|max:20','colonia'=>'nullable|string|max:100','municipio'=>'nullable|string|max:100','estado'=>'nullable|string|max:100','codigo_postal'=>'nullable|string|max:10']);
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
        )->with('success','Dirección guardada correctamente.');
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

        $request->validate(['id_cliente'=>'required|integer|exists:clientes,id_cliente','calle'=>'required|string|max:150','numero_exterior'=>'nullable|string|max:20','numero_interior'=>'nullable|string|max:20','colonia'=>'nullable|string|max:100','municipio'=>'nullable|string|max:100','estado'=>'nullable|string|max:100','codigo_postal'=>'nullable|string|max:10']);
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
        )->with('success','Dirección guardada correctamente.');
    }

    public function destroy($id)
    {
        return \App\Services\RegistroCrud::borrar('direcciones_clientes', (int)$id);
    }
}
