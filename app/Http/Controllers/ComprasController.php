<?php

namespace App\Http\Controllers;

use App\Models\Compras;
use App\Models\Proveedor;
use App\Models\Maquina;
use Illuminate\Http\Request;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Compras::with([
            'proveedor.persona',
            'maquina'
        ])
        ->orderBy('id_compra', 'asc')
        ->get();

        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::with('persona')
            ->orderBy('id_proveedor', 'asc')
            ->get();

        $maquinas = Maquina::orderBy('codigo', 'asc')
            ->get();

        return view(
            'compras.create',
            compact('proveedores', 'maquinas')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'id_maquina' => 'required|exists:maquinas,id_maquina|unique:compras,id_maquina',
            'fecha_compra' => 'required|date',
            'precio_compra' => 'required|numeric|min:0',
            'numero_factura' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string'
        ]);

        Compras::create([
            'id_proveedor' => $request->id_proveedor,
            'id_maquina' => $request->id_maquina,
            'fecha_compra' => $request->fecha_compra,
            'precio_compra' => $request->precio_compra,
            'numero_factura' => $request->numero_factura,
            'observaciones' => $request->observaciones
        ]);

        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra registrada correctamente.');
    }

    public function edit($id)
    {
        $compra = Compras::findOrFail($id);

        $proveedores = Proveedor::with('persona')
            ->orderBy('id_proveedor', 'asc')
            ->get();

        $maquinas = Maquina::orderBy('codigo', 'asc')
            ->get();

        return view(
            'compras.edit',
            compact('compra', 'proveedores', 'maquinas')
        );
    }

    public function update(Request $request, $id)
    {
        $compra = Compras::findOrFail($id);

        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'id_maquina' => 'required|exists:maquinas,id_maquina|unique:compras,id_maquina,'
                . $id . ',id_compra',
            'fecha_compra' => 'required|date',
            'precio_compra' => 'required|numeric|min:0',
            'numero_factura' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string'
        ]);

        $compra->update([
            'id_proveedor' => $request->id_proveedor,
            'id_maquina' => $request->id_maquina,
            'fecha_compra' => $request->fecha_compra,
            'precio_compra' => $request->precio_compra,
            'numero_factura' => $request->numero_factura,
            'observaciones' => $request->observaciones
        ]);

        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra actualizada correctamente.');
    }

    public function destroy($id)
    {
        $compra = Compras::findOrFail($id);

        $compra->delete();

        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra eliminada correctamente.');
    }
}
