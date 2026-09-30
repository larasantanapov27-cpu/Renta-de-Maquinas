<?php

namespace App\Http\Controllers;

use App\Models\EstadoRenta;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstadoRentaController extends Controller
{
    // Mostrar el listado.
    public function index()
    {
        $estados = EstadoRenta::withCount('rentas')
            ->orderBy('nombre')
            ->paginate(10);

        return view('estados_renta.index', compact('estados'));
    }

    // Mostrar el formulario de registro.
    public function create()
    {
        return view('estados_renta.create');
    }

    // Guardar un nuevo estado.
    public function store(Request $request)
    {
        $data = $this->validarDatos($request);

        EstadoRenta::create($data);

        return redirect()
            ->route('estados_renta.index')
            ->with('success', 'El estado de renta se registró correctamente.');
    }

    // Mostrar el formulario de edición.
    public function edit(EstadoRenta $estadoRenta)
    {
        return view(
            'estados_renta.edit',
            compact('estadoRenta')
        );
    }

    // Actualizar un estado.
    public function update(Request $request, EstadoRenta $estadoRenta)
    {
        $data = $this->validarDatos($request, $estadoRenta);

        $estadoRenta->update($data);

        return redirect()
            ->route('estados_renta.index')
            ->with('success', 'El estado de renta se actualizó correctamente.');
    }

    // Eliminar un estado que no esté en uso.
    public function destroy(EstadoRenta $estadoRenta)
    {
        if ($estadoRenta->rentas()->exists()) {
            return redirect()
                ->route('estados_renta.index')
                ->with(
                    'error',
                    'No puedes eliminar este estado porque tiene rentas asociadas.'
                );
        }

        try {
            $estadoRenta->delete();
        } catch (QueryException $exception) {
            // MySQL/MariaDB: una llave foránea impide la eliminación.
            if ((int) ($exception->errorInfo[1] ?? 0) === 1451) {
                return redirect()
                    ->route('estados_renta.index')
                    ->with(
                        'error',
                        'No puedes eliminar este estado porque tiene registros asociados.'
                    );
            }

            throw $exception;
        }

        return redirect()
            ->route('estados_renta.index')
            ->with('success', 'El estado de renta se eliminó correctamente.');
    }

    // Validaciones compartidas por registro y edición.
    private function validarDatos(
        Request $request,
        ?EstadoRenta $estadoRenta = null
    ): array {
        // Normalizar el nombre antes de comprobar si está repetido.
        if (is_string($request->input('nombre'))) {
            $request->merge([
                'nombre' => trim($request->input('nombre')),
            ]);
        }

        $nombreUnico = Rule::unique('estados_renta', 'nombre');

        if ($estadoRenta !== null) {
            $nombreUnico->ignore(
                $estadoRenta->getKey(),
                'id_estado_renta'
            );
        }

        return $request->validate([
            'nombre' => [
                'bail',
                'required',
                'string',
                'max:50',
                $nombreUnico,
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'nombre.required' => 'Escribe el nombre del estado.',
            'nombre.string' => 'El nombre debe ser un texto.',
            'nombre.max' => 'El nombre no debe superar 50 caracteres.',
            'nombre.unique' => 'Ya existe un estado con ese nombre.',

            'descripcion.string' => 'La descripción debe ser un texto.',
            'descripcion.max' => 'La descripción no debe superar 255 caracteres.',
        ]);
    }
}