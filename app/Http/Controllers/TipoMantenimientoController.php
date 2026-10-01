<?php

namespace App\Http\Controllers;

use App\Models\TipoMantenimiento;
use Illuminate\Http\Request;

class TipoMantenimientoController extends Controller
{
    // Mostrar formulario para crear tipo de mantenimiento
    public function create()
    {
        return view('tipos_mantenimiento.create');
    }


    // Guardar nuevo tipo de mantenimiento
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255'
        ]);

        TipoMantenimiento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('tipos_mantenimiento.create')
            ->with('success', 'Tipo de mantenimiento registrado correctamente');
    }


    // Mostrar formulario de edición
    public function edit($id)
    {
        $tipo = TipoMantenimiento::findOrFail($id);

        return view('tipos_mantenimiento.edit', compact('tipo'));
    }


    // Actualizar tipo de mantenimiento
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $tipo = TipoMantenimiento::findOrFail($id);

        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()
            ->route('tipos_mantenimiento.create')
            ->with('success', 'Tipo de mantenimiento actualizado correctamente');
    }


    // Eliminar tipo de mantenimiento
    public function destroy($id)
    {
        $tipo = TipoMantenimiento::findOrFail($id);

        $tipo->delete();

        return redirect()
            ->route('tipos_mantenimiento.create')
            ->with('success', 'Tipo de mantenimiento eliminado correctamente');
    }
}