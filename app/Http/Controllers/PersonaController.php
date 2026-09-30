<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::all();

        return view('personas.index', compact('personas'));
    }

    public function create()
    {
        return view('personas.create');
    }

    public function store(Request $request)
    {
        Persona::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono
        ]);

        return redirect()->route('personas.index');
    }

    public function edit($id)
    {
        $persona = Persona::findOrFail($id);

        return view('personas.edit', compact('persona'));
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $persona->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono
        ]);

        return redirect()->route('personas.index');
    }

    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);

        $persona->delete();

        return redirect()->route('personas.index');
    }
}