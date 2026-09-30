<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Persona;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::with('persona')->get();

        return view(
            'proveedores.index',
            compact('proveedores')
        );
    }

    public function create()
    {
        $personas = Persona::all();

        return view(
            'proveedores.create',
            compact('personas')
        );
    }

    public function store(Request $request)
    {
        Proveedor::create([
            'id_persona' => $request->id_persona,
            'rfc' => $request->rfc,
            'correo' => $request->correo,
            'direccion' => $request->direccion
        ]);

        return redirect()->route('proveedores.index');
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $personas = Persona::all();

        return view(
            'proveedores.edit',
            compact('proveedor', 'personas')
        );
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $proveedor->update([
            'id_persona' => $request->id_persona,
            'rfc' => $request->rfc,
            'correo' => $request->correo,
            'direccion' => $request->direccion
        ]);

        return redirect()->route('proveedores.index');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $proveedor->delete();

        return redirect()->route('proveedores.index');
    }
}